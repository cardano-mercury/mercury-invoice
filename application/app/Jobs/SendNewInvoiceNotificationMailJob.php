<?php

namespace App\Jobs;

use App\Models\Invoice;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\SerializesModels;
use App\Mail\NewInvoiceNotificationMail;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendNewInvoiceNotificationMailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private Invoice $invoice;
    private bool $isReminder;

    /**
     * Create a new job instance.
     */
    public function __construct(Invoice $invoice, bool $isReminder = false)
    {
        $this->invoice = $invoice;
        $this->isReminder = $isReminder;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Load all the invoice recipients
        $this->invoice->load(['recipients']);

        // Send email to every recipient
        foreach ($this->invoice->recipients as $recipient) {
            Mail::to($recipient->address, $recipient->name)
                ->send(new NewInvoiceNotificationMail($this->invoice, $recipient->name, $this->isReminder));
        }

        // Remember when invoice notification was last sent
        $this->invoice->update([
            'last_notified' => now()->toDateTimeString(),
        ]);
    }
}
