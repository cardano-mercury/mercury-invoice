<?php

namespace App\Jobs;

use Throwable;
use App\Enums\Status;
use App\Models\Webhook;
use App\Models\WebhookLog;
use App\Traits\LogExceptionTrait;
use Illuminate\Support\Facades\Http;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Client\ConnectionException;

class WebhookNotificationJob implements ShouldQueue
{
    use Queueable, LogExceptionTrait;

    private Webhook $webhook;
    private array $payload;

    /**
     * Create a new job instance.
     *
     * @param Webhook $webhook
     * @param array $payload
     */
    public function __construct(
        Webhook $webhook,
        array $payload,
    )
    {
        $this->webhook = $webhook;
        $this->payload = $payload;
    }

    /**
     * Determine number of times the job may be attempted.
     *
     * @return int
     */
    public function tries(): int
    {
        return $this->webhook->max_attempts;
    }

    /**
     * Calculate the number of seconds to wait before retrying the job.
     *
     * @return int
     */
    public function backoff(): int
    {
        return $this->webhook->retry_seconds;
    }

    /**
     * Execute the job.
     *
     * @throws RequestException
     * @throws ConnectionException
     * @return void
     */
    public function handle(): void
    {
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
    }

    /**
     * Handle a job failure.
     *
     * @param Throwable|null $exception
     * @return void
     */
    public function failed(Throwable|null $exception): void
    {
        // Log webhook error
        $this->logWebhook(Status::ERROR, $exception);
    }

    /**
     * Helper function logging webhook status changes.
     *
     * @param Status $status
     * @param Throwable|null $exception
     * @return void
     */
    private function logWebhook(Status $status, Throwable|null $exception = null): void
    {
        $webhookLog = new WebhookLog;
        $webhookLog->fill([
            'webhook_id' => $this->webhook->id,
            'status' => $status,
            'event_target_id' => $this->payload['event_target_id'],
            'event_name' => $this->payload['event_name'],
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
