<?php

namespace App\Http\Controllers\Public;

use Browser;
use Throwable;
use Inertia\Inertia;
use App\Enums\Status;
use Inertia\Response;
use App\Models\Invoice;
use Stripe\StripeClient;
use App\Traits\HashIdTrait;
use App\Enums\PaymentMethod;
use Illuminate\Http\Request;
use App\Enums\CardanoNetwork;
use App\Models\InvoicePayment;
use App\Models\InvoiceActivity;
use Illuminate\Http\JsonResponse;
use App\Traits\LogExceptionTrait;
use App\Services\AdaPriceService;
use App\Libraries\CardanoSlotTimer;
use App\ThirdParty\BlockfrostClient;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\RedirectResponse;

class PublicInvoiceController extends Controller
{
    use HashIdTrait;
    use LogExceptionTrait;

    public function view(string $encodedId, Request $request): Response
    {
        // Load requested invoice
        /** @var Invoice $invoice */
        $invoice = Invoice::query()
            ->where('id', $this->decodeId($encodedId))
            ->with([
                'user',
                'customer',
                'billingAddress',
                'shippingAddress',
                'items',
            ])
            ->firstOrFail();

        // Parse billing address
        $billingAddress = array_values(array_filter([
            $invoice?->billingAddress?->name,
            $invoice?->billingAddress?->line1,
            $invoice?->billingAddress?->line2,
            $invoice?->billingAddress?->city,
            $invoice?->billingAddress?->state,
            $invoice?->billingAddress?->postal_code,
            $invoice?->billingAddress?->country,
        ]));

        // Parse shipping address
        $shippingAddress = array_values(array_filter([
            $invoice->shippingAddress?->name,
            $invoice->shippingAddress?->line1,
            $invoice->shippingAddress?->line2,
            $invoice->shippingAddress?->city,
            $invoice->shippingAddress?->state,
            $invoice->shippingAddress?->postal_code,
            $invoice->shippingAddress?->country,
        ]));

        // Log invoice activity
        $this->logInvoiceViewedActivity($invoice, $request, 'Invoice was viewed', true);

        // Proceed if invoice is in published status
        if ($invoice->status === Status::PUBLISHED) {

            // Load available payment methods
            $availablePaymentMethods = $this->loadAvailablePaymentMethods($invoice);

            // Check if stripe payment was cancelled
            $stripePaymentCancelled = $request->has('stripe_cancelled');

            // Check if stripe payment was completed
            $stripePaymentCompleted = false;
            try {
                if ($request->has('stripe_session_id')) {
                    $stripe = new StripeClient(decrypt($invoice->user->stripe_config['secret_key']));
                    $checkoutSession = $stripe->checkout->sessions->retrieve($request->get('stripe_session_id'));
                    $stripePaymentCompleted = ($checkoutSession->payment_status === 'paid' && $checkoutSession->status === 'complete');
                    if ($stripePaymentCompleted && $invoice->status === Status::PUBLISHED) {
                        $invoice->update([
                            'status' => Status::PAYMENT_PROCESSING,
                        ]);
                        $this->logInvoiceViewedActivity(
                            $invoice,
                            $request,
                            sprintf('Stripe payment (%s) notification received', $checkoutSession->payment_intent),
                            false,
                        );
                    }
                }
            } catch (Throwable) { }

            // If crypto payment is enabled
            if (in_array(PaymentMethod::CRYPTO->value, $availablePaymentMethods)) {

                // Workout target cardano network
                $targetCardanoNetworkName = config('cardanomercury.target_cardano_network');
                $targetCardanoNetwork = [
                    'id' => ($targetCardanoNetworkName === CardanoNetwork::MAINNET ? 1 : 0),
                    'name' => $targetCardanoNetworkName,
                ];

                // Load protocol parameters
                $cryptoProtocolParameters = $this->loadCryptoProtocolParameters($invoice, $targetCardanoNetworkName);

                // Perform currency conversion
                $adaInvoiceCurrencyValue = AdaPriceService::convert($invoice->currency);

                // Set crypto payment deadline
                $cryptoPaymentDeadline = CardanoSlotTimer::unixToSlot(
                    time() + config('cardanomercury.crypto_payment_deadline_seconds'),
                    $targetCardanoNetworkName,
                );

                // Set payment address
                $cryptoPaymentAddress = $invoice->user->crypto_config['payment_address'];

                // Setup draft invoice payment
                $draftInvoicePayment = InvoicePayment::query()
                    ->where('invoice_id', $invoice->id)
                    ->where('payment_method', PaymentMethod::CRYPTO->value)
                    ->where('status', Status::DRAFT)
                    ->first();
                if (!$draftInvoicePayment) {
                    $draftInvoicePayment = new InvoicePayment;
                    $draftInvoicePayment->fill([
                        'invoice_id' => $invoice->id,
                        'payment_date' => now()->toDateString(),
                        'payment_method' => PaymentMethod::CRYPTO,
                        'payment_currency' => 'ADA',
                        'payment_amount' => $invoice->total,
                        'crypto_asset_name' => 'ADA',
                        'status' => Status::DRAFT,
                    ]);
                }
                $draftInvoicePayment->fill([
                    'crypto_asset_ada_price' => $adaInvoiceCurrencyValue,
                    'crypto_asset_quantity' => round(($invoice->total / $adaInvoiceCurrencyValue), 6),
                    'crypto_payment_ttl' => $cryptoPaymentDeadline,
                    'crypto_payment_recipient_address' => $cryptoPaymentAddress,
                ]);
                $draftInvoicePayment->save();

            } else {

                // Defaults
                $targetCardanoNetwork = null;
                $adaInvoiceCurrencyValue = null;
                $cryptoPaymentAddress = null;
                $cryptoPaymentDeadline = null;
                $cryptoProtocolParameters = null;

            }

        } else {

            // Defaults
            $availablePaymentMethods = null;
            $stripePaymentCancelled = null;
            $stripePaymentCompleted = null;
            $targetCardanoNetwork = null;
            $adaInvoiceCurrencyValue = null;
            $cryptoPaymentAddress = null;
            $cryptoPaymentDeadline = null;
            $cryptoProtocolParameters = null;

        }

        // Render public invoice view
        return Inertia::render('Invoice/PublicView', compact(
            'invoice',
            'billingAddress',
            'shippingAddress',
            'availablePaymentMethods',
            'stripePaymentCancelled',
            'stripePaymentCompleted',
            'targetCardanoNetwork',
            'adaInvoiceCurrencyValue',
            'cryptoPaymentAddress',
            'cryptoPaymentDeadline',
            'cryptoProtocolParameters',
        ));
    }

    public function payViaStripe(string $encodedId, Request $request): RedirectResponse
    {
        try {

            /** @var Invoice $invoice */
            $invoice = Invoice::query()
                ->where('id', $this->decodeId($encodedId))
                ->where('status', Status::PUBLISHED)
                ->with([
                    'user',
                    'items',
                ])
                ->firstOrFail();

            if (empty($invoice->user->stripe_config['secret_key']) || empty($invoice->user->stripe_config['endpoint_secret'])) {
                abort(422, 'Stripe payment method not enabled');
            }

            $stripe = new StripeClient(decrypt($invoice->user->stripe_config['secret_key']));

            $lineItems = $invoice->items->map(static function ($item) use($invoice) {
                $productData = [
                    'name' => $item->description
                ];
                if (!empty($item->sku)) {
                    $productData['description'] = $item->sku;
                }
                $unitAmount = (float) $item->unit_price;
                if ((float) $item->tax_rate > 0) {
                    $unitAmount += $unitAmount * ((float) $item->tax_rate / 100);
                }
                $unitAmount = (int) (round($unitAmount, 2) * 100); // Convert the unit price (inc tax) to base unit as Stripe needs it
                return [
                    'price_data' => [
                        'currency' => strtolower($invoice->currency),
                        'product_data' => $productData,
                        'unit_amount' => $unitAmount,
                    ],
                    'quantity' => (int) $item->quantity,
                ];
            })->toArray();

            $checkoutSession = $stripe->checkout->sessions->create([
                'client_reference_id' => $invoice->invoice_reference,
                'line_items' => $lineItems,
                'mode' => 'payment',
                'success_url' => route('public.invoice.view', $invoice->invoice_reference) . '?stripe_session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => route('public.invoice.view', $invoice->invoice_reference) . '?stripe_cancelled=true',
            ]);

            $this->logInvoiceViewedActivity($invoice, $request, 'Pay via Stripe initiated', true);

            return redirect()->away($checkoutSession->url);

        } catch (Throwable $exception) {

            $this->logException(
                'Failed to create stripe checkout session',
                $exception,
                compact('encodedId')
            );

            abort(500, 'Failed to create stripe checkout session');

        }
    }

    public function payViaCrypto(string $encodedId, Request $request): JsonResponse
    {
        try {

            /** @var Invoice $invoice */
            $invoice = Invoice::query()
                ->where('id', $this->decodeId($encodedId))
                ->where('status', Status::PUBLISHED)
                ->with([
                    'user',
                ])
                ->first();

            if (!$invoice) {
                return response()->json([
                    'error' => 'Invoice not found',
                ]);
            }

            if (empty($invoice->user->crypto_config['api_key']) || empty($invoice->user->crypto_config['cardano_network'])) {
                return response()->json([
                    'error' => 'Crypto payment method not enabled',
                ]);
            }

            $draftInvoicePayment = InvoicePayment::query()
                ->where('invoice_id', $invoice->id)
                ->where('payment_method', PaymentMethod::CRYPTO->value)
                ->where('status', Status::DRAFT)
                ->first();

            if (!$draftInvoicePayment) {
                return response()->json([
                    'error' => 'Draft invoice payment record not found',
                ]);
            }

            $draftInvoicePayment->update([
                'payment_reference' => $request->get('payment_reference'),
                'crypto_wallet_name' => $request->get('crypto_wallet_name'),
                'status' => Status::PAYMENT_PROCESSING,
            ]);

            $invoice->update([
                'status' => Status::PAYMENT_PROCESSING,
            ]);

            return response()->json([
                'success' => true,
            ]);

        } catch (Throwable $exception) {

            $this->logException(
                'Failed to process crypto payment session',
                $exception,
                compact('encodedId')
            );

            return response()->json([
                'error' => 'Failed to process crypto payment session',
            ]);

        }
    }

    private function logInvoiceViewedActivity(Invoice $invoice, Request $request, string $activityMessage, bool $withDeviceInfo): void
    {
        if ($withDeviceInfo) {
            $userIp = $request->header('x-vapor-source-ip');
            if (empty($userIp)) {
                $userIp = $request->ip();
            }
            $ipParts = explode('.', $userIp);
            $maskedIp = sprintf('%s.xxx.xxx.%s', $ipParts[0], $ipParts[3]);

            $activity = sprintf(
                '%s by customer in %s (%s) on %s browser (%s IP Address)',
                $activityMessage,
                Browser::deviceType(),
                Browser::platformName(),
                Browser::browserFamily(),
                $maskedIp,
            );
        } else {
            $activity = $activityMessage;
        }

        InvoiceActivity::create([
            'invoice_id' => $invoice->id,
            'activity' => $activity,
        ]);
    }

    private function loadAvailablePaymentMethods($invoice): array
    {
        $availablePaymentMethods = [];

        $user = $invoice->user;

        if (!empty($user->stripe_config['secret_key']) && !empty($user->stripe_config['endpoint_secret'])) {
            $availablePaymentMethods[] = PaymentMethod::STRIPE->value;
        }

        if (!empty($user->crypto_config['api_key']) && !empty($user->crypto_config['cardano_network'])) {
            $availablePaymentMethods[] = PaymentMethod::CRYPTO->value;
        }

        return $availablePaymentMethods;
    }

    private function loadCryptoProtocolParameters(Invoice $invoice, CardanoNetwork $cardanoNetwork): array
    {
        return Cache::remember(sprintf('%s:protocol-parameters', $cardanoNetwork->value), 86400, function () use ($cardanoNetwork, $invoice) {
            try {
                $blockFrostClient = new BlockfrostClient($cardanoNetwork, decrypt($invoice->user->crypto_config['api_key']));
                $protocolParameters = $blockFrostClient->get('epochs/latest/parameters');
                return [
                    'linearFee' => [
                        'minFeeA' => (string) $protocolParameters['min_fee_a'],
                        'minFeeB' => (string) $protocolParameters['min_fee_b'],
                    ],
                    'minUtxo' => (string) $protocolParameters['min_utxo'],
                    'poolDeposit' => (string) $protocolParameters['pool_deposit'],
                    'keyDeposit' => (string) $protocolParameters['key_deposit'],
                    'maxValSize' => (string) $protocolParameters['max_val_size'],
                    'maxTxSize' => $protocolParameters['max_tx_size'],
                    'costPerWord' => (string) $protocolParameters['coins_per_utxo_size'],
                ];
            } catch (Throwable) {
                return [
                    'linearFee' => [
                        'minFeeA' => '44',
                        'minFeeB' => '155381',
                    ],
                    'minUtxo' => '4310',
                    'poolDeposit' => '500000000',
                    'keyDeposit' => '2000000',
                    'maxValSize' => '5000',
                    'maxTxSize' => 16384,
                    'costPerWord' => '4310',
                ];
            }
        });
    }
}
