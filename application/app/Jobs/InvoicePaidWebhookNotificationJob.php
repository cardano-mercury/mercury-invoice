<?php

namespace App\Jobs;

use App\Enums\PaymentMethod;
use Throwable;
use App\Enums\Status;
use App\Models\Invoice;
use App\Models\Webhook;
use App\Models\WebhookLog;
use Illuminate\Bus\Queueable;
use App\Models\InvoicePayment;
use App\Models\InvoiceActivity;
use App\Traits\LogExceptionTrait;
use Illuminate\Support\Facades\Http;
use App\Enums\WebhookEventTargetName;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Client\ConnectionException;

class InvoicePaidWebhookNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, LogExceptionTrait;

    private Invoice $invoice;
    private InvoicePayment $invoicePayment;
    private Webhook $webhook;
    private array $payload;

    /**
     * Create a new job instance.
     */
    public function __construct(
        Invoice $invoice,
        InvoicePayment $invoicePayment,
        Webhook $webhook,
    )
    {
        $this->invoice = $invoice;
        $this->invoicePayment = $invoicePayment;
        $this->webhook = $webhook;
    }

    /**
     * Determine number of times the job may be attempted.
     */
    public function tries(): int
    {
        return $this->webhook->max_attempts;
    }

    /**
     * Calculate the number of seconds to wait before retrying the job.
     */
    public function backoff(): int
    {
        return $this->webhook->retry_seconds;
    }

    /**
     * Execute the job.
     * @throws ConnectionException
     * @throws RequestException
     */
    public function handle(): void
    {
        // Generate payload
        $this->payload = $this->generatePayload();

        // Generate payload signature
        $payloadSignature = hash_hmac(
            $this->webhook->hmac_algorithm,
            json_encode($this->payload),
            decrypt($this->webhook->hmac_secret),
        );

        // Send the payload out
        Http::timeout($this->webhook->timeout_seconds)
            ->connectTimeout($this->webhook->timeout_seconds)
            ->withHeaders([
                'X-Webhook-HMAC-Signature' => $payloadSignature,
            ])
            ->post($this->webhook->url, $this->payload)
            ->throw();

        // Log webhook success
        $this->logWebhook(Status::SUCCESS);

        // Log activity
        InvoiceActivity::create([
            'invoice_id' => $this->invoice->id,
            'activity' => sprintf(
                'Webhook (%s) notified',
                $this->webhook->url,
            ),
        ]);
    }

    /**
     * Handle a job failure.
     */
    public function failed(Throwable|null $exception): void
    {
        // Log webhook error
        $this->logWebhook(Status::ERROR, $exception);
    }

    private function generatePayload(): array
    {
        return [
            'event' => WebhookEventTargetName::INVOICE_PAID->value,
            'invoice' => [
                'reference' => $this->invoice->invoice_reference,
                'issue_date' => $this->invoice->issue_date->toDateString(),
                'due_date' => $this->invoice->due_date->toDateString(),
                'status' => $this->invoice->status->value,
                'total' => $this->invoice->total,
                'currency' => $this->invoice->currency,
                'items' => $this->invoice->items->map(static function ($item) {
                    return [
                        'sku' => $item->sku,
                        'description' => $item->description,
                        'quantity' => (float) $item->quantity,
                        'unit_price' => (float) $item->unit_price,
                        'tax_rate' => (float) $item->tax_rate,
                    ];
                })->toArray(),
            ],
            'payment' => [
                'reference' => $this->invoicePayment->payment_reference,
                'date' => $this->invoicePayment->payment_date->toDateString(),
                'method' => $this->invoicePayment->payment_method,
                'currency' => $this->invoicePayment->payment_currency,
                'amount' => (float) ($this->invoicePayment->payment_method === PaymentMethod::CRYPTO
                    ? $this->invoicePayment->crypto_asset_quantity
                    : $this->invoicePayment->payment_amount
                ),
                'crypto_exchange_rate' => (float) $this->invoicePayment->crypto_asset_ada_price,
                'crypto_wallet_name' => $this->invoicePayment->crypto_wallet_name,
                'status' => $this->invoicePayment->status->value,
            ]
        ];
    }

    private function logWebhook(Status $status, Throwable|null $exception = null): void
    {
        $webhookLog = new WebhookLog;
        $webhookLog->fill([
            'webhook_id' => $this->webhook->id,
            'status' => $status,
            'event_target_id' => $this->invoicePayment->id,
            'event_name' => WebhookEventTargetName::INVOICE_PAID,
            'payload' => json_encode($this->payload),
            'attempts' => $this->attempts(),
            'error' => ($exception ? json_encode([
                'error' => $exception->getMessage(),
                'file' => $exception->getFile() . ':' . $exception->getLine(),
                'previous' => $this->parsePreviousExceptions($exception->getPrevious()),
            ]) : null),
        ]);
        $webhookLog->save();
    }
}
