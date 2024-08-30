<?php

namespace App\Jobs;

use App\Enums\PaymentMethod;
use Throwable;
use App\Enums\Status;
use App\Enums\CardanoNetwork;
use App\Models\InvoicePayment;
use App\Models\InvoiceActivity;
use Illuminate\Support\Facades\Mail;
use App\ThirdParty\BlockfrostClient;
use App\Enums\WebhookEventTargetName;
use App\Mail\InvoicePaidNotificationMail;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

class ProcessCryptoPaymentJob implements ShouldQueue
{
    use Queueable;

    private InvoicePayment $invoicePayment;

    private const MAX_PROCESS_ATTEMPTS = 24; // Track Cardano payment tx for at least 2 hours (if tracking cron runs every 5 minutes)

    /**
     * Create a new job instance.
     */
    public function __construct(InvoicePayment $invoicePayment)
    {
        $this->invoicePayment = $invoicePayment;

        $this->invoicePayment->load([
            'invoice',
            'invoice.user',
            'invoice.user.webhooks' => static function ($query) {
                $query->whereHas('eventTargets', static function ($query) {
                    $query->where('event_name', WebhookEventTargetName::INVOICE_PAID);
                });
            },
            'invoice.customer',
            'invoice.recipients',
        ]);
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Check if this is a Crypto invoice payment
        if ($this->invoicePayment->payment_method !== PaymentMethod::CRYPTO ||
            $this->invoicePayment->status !== Status::PAYMENT_PROCESSING
        ) {
            $this->invoicePayment->update([
                'crypto_payment_last_checked' => time(),
                'crypto_payment_process_attempts' => ($this->invoicePayment->crypto_payment_process_attempts + 1),
                'crypto_payment_last_error' => sprintf(
                    'Payment method (%s) is not a Crypto payment method or current status (%s) is invalid',
                    $this->invoicePayment->payment_method->value,
                    $this->invoicePayment->status->value,
                ),
                'status' => Status::ERROR,
            ]);
            return;
        }

        // Check if max process attempts has exceeded
        if ($this->invoicePayment->crypto_payment_process_attempts >= self::MAX_PROCESS_ATTEMPTS) {
            $this->invoicePayment->update([
                'crypto_payment_last_checked' => time(),
                'crypto_payment_last_error' => sprintf(
                    '%s: Max process attempts %d reached',
                    $this->invoicePayment->crypto_payment_last_error,
                    self::MAX_PROCESS_ATTEMPTS,
                ),
                'status' => Status::ERROR,
            ]);
            return;
        }

        // Proceed if crypto payment method is enabled
        if ($this->hasCryptoPaymentMethod())
        {
            // Load crypto payment details
            $paymentDetails = $this->loadPaymentDetails();

            // Proceed if payment details were loaded
            if ($paymentDetails)
            {
                // Proceed if payment details is valid
                $paymentDetailsValidationResult = $this->isPaymentDetailsValid($paymentDetails);
                if ($paymentDetailsValidationResult['isValid'])
                {
                    // Update invoice
                    $this->invoicePayment->invoice->update([
                        'status' => Status::PAID,
                        'last_notified' => now()->toDateTimeString(),
                    ]);

                    // Update invoice payment
                    $this->invoicePayment->update([
                        'crypto_payment_last_checked' => time(),
                        'crypto_payment_process_attempts' => ($this->invoicePayment->crypto_payment_process_attempts + 1),
                        'crypto_payment_last_error' => null,
                        'status' => Status::PAID,
                    ]);

                    // Notify the user
                    Mail::to($this->invoicePayment->invoice->user->email, $this->invoicePayment->invoice->user->name)
                        ->send(new InvoicePaidNotificationMail($this->invoicePayment->invoice, $this->invoicePayment, $this->invoicePayment->invoice->user->name, true));

                    // Notify invoice recipients
                    foreach ($this->invoicePayment->invoice->recipients as $recipient) {
                        Mail::to($recipient->address, $recipient->name)
                            ->send(new InvoicePaidNotificationMail($this->invoicePayment->invoice, $this->invoicePayment, $recipient->name, false));
                    }

                    // Notify webhooks (if applicable)
                    if ($this->invoicePayment->invoice->user->webhooks->count()) {
                        foreach ($this->invoicePayment->invoice->user->webhooks as $webhook) {
                            dispatch(new InvoicePaidWebhookNotificationJob(
                                $this->invoicePayment->invoice,
                                $this->invoicePayment,
                                $webhook,
                            ));
                        }
                    }

                    // Log activity
                    InvoiceActivity::create([
                        'invoice_id' => $this->invoicePayment->invoice->id,
                        'activity' => sprintf(
                            'Crypto payment (%s) successfully processed, invoice is now paid',
                            $this->invoicePayment->payment_reference,
                        ),
                    ]);
                }
                else
                {
                    // Update invoice payment
                    $this->invoicePayment->update([
                        'crypto_payment_last_checked' => time(),
                        'crypto_payment_process_attempts' => ($this->invoicePayment->crypto_payment_process_attempts + 1),
                        'crypto_payment_last_error' => sprintf(
                            'Crypto payment is not valid: %s',
                            $paymentDetailsValidationResult['reason'],
                        ),
                        'status' => Status::ERROR,
                    ]);
                }
            }
            else
            {
                // Update invoice payment
                $this->invoicePayment->update([
                    'crypto_payment_last_checked' => time(),
                    'crypto_payment_process_attempts' => ($this->invoicePayment->crypto_payment_process_attempts + 1),
                    'crypto_payment_last_error' => 'Crypto payment details not found on chain',
                ]);
            }
        }
        else
        {
            // Update invoice payment
            $this->invoicePayment->update([
                'crypto_payment_last_checked' => time(),
                'crypto_payment_last_error' => 'Crypto payment method not enabled',
                'status' => Status::ERROR,
            ]);
        }
    }

    private function hasCryptoPaymentMethod(): bool
    {
        // Check settings
        return
            !empty($this->invoicePayment->invoice->user->crypto_config['api_key']) &&
            !empty($this->invoicePayment->invoice->user->crypto_config['cardano_network']);
    }

    private function loadPaymentDetails(): array|null
    {
        // Anticipate errors
        try {

            // Parse config
            $apiKey = decrypt($this->invoicePayment->invoice->user->crypto_config['api_key']);
            $cardanoNetwork = CardanoNetwork::from($this->invoicePayment->invoice->user->crypto_config['cardano_network']);

            // Initialize
            $blockfrostClient = new BlockfrostClient($cardanoNetwork, $apiKey);

            // Load transaction output info
            $txOutputInfo = $blockfrostClient->get(sprintf('txs/%s/utxos', $this->invoicePayment->payment_reference));

            // Proceed if tx output info is found
            if ($txOutputInfo)
            {
                // Load transaction metadata
                $txMetadata = $blockfrostClient->get(sprintf('txs/%s/metadata', $this->invoicePayment->payment_reference));

                // Return found data
                return [
                    'outputs' => $txOutputInfo['outputs'] ?? [],
                    'metadata' => $txMetadata ?? [],
                ];
            }

        } catch (Throwable) { }

        // Details not available (i.e. tx is not on chain yet)
        return null;
    }

    private function isPaymentDetailsValid(array $paymentDetails): array
    {
        // Validate if the CMIRef matches
        $cmiRefMatches = false;
        foreach ($paymentDetails['metadata'] ?? [] as $metadata) {
            if ((int) $metadata['label'] ?? 0 === 674) {
                @[, $cmiRef] = explode(':', $metadata['json_metadata'] ?? '');
                if ($cmiRef === $this->invoicePayment->invoice->invoice_reference) {
                    $cmiRefMatches = true;
                    break;
                }
            }
        }
        if (!$cmiRefMatches) {
            return [
                'isValid' => false,
                'reason' => sprintf(
                    'Invalid transaction metadata for CMIRef (expected %s)',
                    $this->invoicePayment->invoice->invoice_reference,
                ),
            ];
        }

        // Validate if the payment was for the intended recipient address
        $correctPaymentOutput = null;
        foreach ($paymentDetails['outputs'] ?? [] as $output) {
            if (isset($output['address']) && $output['address'] === $this->invoicePayment->crypto_payment_recipient_address) {
                $correctPaymentOutput = $output;
                break;
            }
        }
        if (!$correctPaymentOutput) {
            return [
                'isValid' => false,
                'reason' => sprintf(
                    'Invalid payment recipient (expected %s)',
                    $this->invoicePayment->crypto_payment_recipient_address,
                ),
            ];
        }

        // Validate if expected payment amount was paid
        $isCorrectPaymentAmount = false;
        $expectedPaymentAmount = (int) ($this->invoicePayment->crypto_asset_quantity * 1_000_000);
        foreach ($correctPaymentOutput['amount'] ?? [] as $amount) {
            if (isset($amount['unit']) && $amount['unit'] === 'lovelace' && (int) $amount['quantity'] === $expectedPaymentAmount) {
                $isCorrectPaymentAmount = true;
                break;
            }
        }
        if (!$isCorrectPaymentAmount) {
            return [
                'isValid' => false,
                'reason' => sprintf(
                    'Invalid payment amount (expected %d lovelaces)',
                    $expectedPaymentAmount,
                ),
            ];
        }

        // CMIRef matches, Intended recipient address and expected payment amount is correct
        return [
            'isValid' => true,
            'reason' => null,
        ];
    }
}
