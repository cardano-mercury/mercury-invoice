<?php

namespace App\Http\Controllers\Public;

use App\Jobs\InvoicePaidWebhookNotificationJob;
use Exception;
use Throwable;
use Stripe\Stripe;
use App\Models\User;
use App\Enums\Status;
use App\Models\Invoice;
use App\Traits\HashIdTrait;
use App\Enums\PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use UnexpectedValueException;
use App\Models\InvoicePayment;
use App\Models\InvoiceActivity;
use Stripe\Event as StripeEvent;
use App\Traits\LogExceptionTrait;
use Stripe\Webhook as StripeWebhook;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use App\Enums\WebhookEventTargetName;
use App\Mail\InvoicePaidNotificationMail;
use Stripe\Exception\SignatureVerificationException;
use Stripe\Checkout\Session as StripeCheckoutSession;

class PublicWebhookHandler extends Controller
{
    use HashIdTrait;
    use LogExceptionTrait;

    public function handleStripeWebhook(string $encodedUserId, Request $request): Response
    {
        // Initialize
        $payload = $request->getContent();
        $stripeSignature = $request->header('stripe-signature', 'missing-or-unknown');

        // Anticipate errors
        try {

            // Load the specified user
            $user = User::query()
                ->where('id', $this->decodeId($encodedUserId))
                ->first();
            if (!$user) {
                throw new Exception('User not found');
            }

            // Decrypt the stripe config
            $stripeSecretKey = decrypt($user->stripe_config['secret_key']);
            $stripeEndpointSecret = decrypt($user->stripe_config['endpoint_secret']);

            // Initialize the stripe client
            Stripe::setApiKey($stripeSecretKey);

            // Set signature tolerance (https://docs.stripe.com/webhooks?locale=en-GB#replay-attacks)
            $tolerance = app()->environment('local', 'staging')
                ? (60 * 60 * 24)                    // Increased tolerance is useful for testing in dev/staging
                : StripeWebhook::DEFAULT_TOLERANCE; // Use stripe library default for production

            // Validate the stripe event
            try {
                $event = StripeWebhook::constructEvent(
                    $payload,
                    $stripeSignature,
                    $stripeEndpointSecret,
                    $tolerance,
                );
            } catch (UnexpectedValueException $exception) {
                throw new Exception('Error parsing payload', $exception->getCode(), $exception);
            } catch(SignatureVerificationException $exception) {
                throw new Exception('Error verifying webhook signature', $exception->getCode(), $exception);
            }

            // Handle successful payment
            if ($event->type === 'checkout.session.completed') {
                $this->handleSuccessfulInvoiceStripePayment($event);
            }

            // Signal okay to stripe
            return response('Webhook handled by Cardano Mercury: Invoice', 200);

        } catch (Throwable $exception) {

            // Log error
            $this->logException(
                'Failed to handle incoming Stripe webhook event',
                $exception,
                [
                    'encodedUserId' => $encodedUserId,
                    'stripe-signature' => $stripeSignature,
                    'stripe-event' => $request->all(),
                ]
            );

            // Signal error to stripe
            return response('Bad Request', 400);

        }
    }

    private function handleSuccessfulInvoiceStripePayment(StripeEvent $event): void
    {
        /** @var StripeCheckoutSession $checkoutSession */
        $checkoutSession = $event->data->object;

        // Load the invoice related to the stripe event
        /** @var Invoice $invoice */
        $invoice = Invoice::query()
            ->where('id', $this->decodeId($checkoutSession->client_reference_id))
            ->whereIn('status', [Status::PUBLISHED, Status::PAYMENT_PROCESSING])
            ->with([
                'user',
                'user.webhooks' => static function ($query) {
                    $query->whereHas('eventTargets', static function ($query) {
                        $query->where('event_name', WebhookEventTargetName::INVOICE_PAID);
                    });
                },
                'customer',
                'recipients',
            ])
            ->first();

        // Continue if related invoice is found
        if ($invoice)
        {
            // Validate checkout session
            $validationErrors = [];
            if ($checkoutSession->payment_status !== 'paid') {
                $validationErrors[] = sprintf('Incorrect payment status (expected paid, received %s)', $checkoutSession->payment_status);
            }
            if ($checkoutSession->status !== 'complete') {
                $validationErrors[] = sprintf('Incorrect status (expected complete, received %s)', $checkoutSession->status);
            }
            if ($checkoutSession->mode !== 'payment') {
                $validationErrors[] = sprintf('Incorrect mode (expected payment, received %s)', $checkoutSession->mode);
            }
            $expectedPaymentAmount = (int) (round($invoice->total, 2) * 100);
            if ($checkoutSession->amount_total !== $expectedPaymentAmount) {
                $validationErrors[] = sprintf('Incorrect payment amount (expected %d, received %d)', $expectedPaymentAmount, $checkoutSession->amount_total);
            }
            if ($checkoutSession->currency !== strtolower($invoice->currency)) {
                $validationErrors[] = sprintf('Incorrect payment currency (expected %s, received %s)', strtolower($invoice->currency), $checkoutSession->currency);
            }

            // Proceed if no validation errors occurred
            if (!count($validationErrors)) {

                // Create invoice payment record (if it doesn't already exist)
                $invoicePayment = InvoicePayment::query()
                    ->where('invoice_id', $invoice->id)
                    ->where('payment_method', PaymentMethod::STRIPE)
                    ->where('payment_reference', $checkoutSession->payment_intent)
                    ->first();
                if (!$invoicePayment) {
                    $invoicePayment = new InvoicePayment;
                    $invoicePayment->fill([
                        'invoice_id' => $invoice->id,
                        'payment_date' => now()->toDateString(),
                        'payment_method' => PaymentMethod::STRIPE,
                        'payment_currency' => strtoupper($checkoutSession->currency),
                        'payment_amount' => ($checkoutSession->amount_total / 100),
                        'payment_reference' => $checkoutSession->payment_intent,
                        'status' => Status::PAID,
                    ]);
                    $invoicePayment->save();
                }

                // Update invoice
                $invoice->update([
                    'status' => Status::PAID,
                    'last_notified' => now()->toDateTimeString(),
                ]);

                // Notify the user
                Mail::to($invoice->user->email, $invoice->user->name)
                    ->send(new InvoicePaidNotificationMail($invoice, $invoicePayment, $invoice->user->name, true));

                // Notify invoice recipients
                foreach ($invoice->recipients as $recipient) {
                    Mail::to($recipient->address, $recipient->name)
                        ->send(new InvoicePaidNotificationMail($invoice, $invoicePayment, $recipient->name, false));
                }

                // Notify webhooks (if applicable)
                if ($invoice->user->webhooks->count()) {
                    foreach ($invoice->user->webhooks as $webhook) {
                        dispatch(new InvoicePaidWebhookNotificationJob(
                            $invoice,
                            $invoicePayment,
                            $webhook,
                        ));
                    }
                }

                // Log activity
                InvoiceActivity::create([
                    'invoice_id' => $invoice->id,
                    'activity' => sprintf(
                        'Stripe payment (%s) successfully processed, invoice is now paid',
                        $checkoutSession->payment_intent,
                    ),
                ]);

            } else {

                // Log activity
                InvoiceActivity::create([
                    'invoice_id' => $invoice->id,
                    'activity' => sprintf(
                        'Unable to process Stripe payment (%s): %s',
                        $checkoutSession->payment_intent,
                        implode(', ', $validationErrors),
                    ),
                ]);

            }
        }
    }
}
