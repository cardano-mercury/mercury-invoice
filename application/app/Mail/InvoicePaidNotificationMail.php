<?php

namespace App\Mail;

use App\Enums\CardanoNetwork;
use App\Models\Invoice;
use App\Enums\PaymentMethod;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use App\Models\InvoicePayment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Contracts\Queue\ShouldQueue;

class InvoicePaidNotificationMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    private Invoice $invoice;
    private InvoicePayment $invoicePayment;
    private string $recipientName;
    private bool $isUserNotification;

    /**
     * Create a new message instance.
     */
    public function __construct(Invoice $invoice, InvoicePayment $invoicePayment, string $recipientName, bool $isUserNotification)
    {
        $this->invoice = $invoice;
        $this->invoicePayment = $invoicePayment;
        $this->recipientName = $recipientName;
        $this->isUserNotification = $isUserNotification;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->isUserNotification
                ? sprintf('Invoice %s payment received', $this->invoice->invoice_reference)
                : sprintf('Thank you for your invoice %s payment', $this->invoice->invoice_reference),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        $txExplorerUrl = null;
        if ($this->invoicePayment->payment_method === PaymentMethod::CRYPTO) {
            $txExplorerUrl = sprintf(
                'https://%scardanoscan.io/transaction/%s',
                (config('cardanomercury.target_cardano_network') === CardanoNetwork::MAINNET ? '' : 'preprod.'),
                $this->invoicePayment->payment_reference
            );
        }

        return new Content(
            markdown: 'emails.invoice-paid-notification',
            with: [
                'invoiceReference' => $this->invoice->invoice_reference,
                'isUserNotification' => $this->isUserNotification,
                'userName' => $this->invoice->user->name,
                'userEmail' => $this->invoice->user->email,
                'customerName' => $this->invoice->customer->name,
                'recipientName' => $this->recipientName,
                'invoiceCurrency' => $this->invoice->currency,
                'invoicePayment' => $this->invoicePayment,
                'invoiceViewerUrl' => route('public.invoice.view', $this->invoice->invoice_reference),
                'txExplorerUrl' => $txExplorerUrl,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
