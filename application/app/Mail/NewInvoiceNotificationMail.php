<?php

namespace App\Mail;

use App\Models\Invoice;
use App\Enums\DateFormat;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Contracts\Queue\ShouldQueue;

class NewInvoiceNotificationMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    private Invoice $invoice;
    private string $recipientName;
    private bool $isReminder;

    /**
     * Create a new message instance.
     */
    public function __construct(Invoice $invoice, string $recipientName, bool $isReminder)
    {
        $this->invoice = $invoice;
        $this->recipientName = $recipientName;
        $this->isReminder = $isReminder;

        $this->invoice->load([
            'user',
            'customer',
        ]);
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            replyTo: [
                new Address($this->invoice->user->email, $this->invoice->user->name)
            ],
            subject: sprintf(
                '%sNew invoice %s from %s',
                ($this->isReminder ? 'REMINDER: ' : ''),
                $this->invoice->invoice_reference,
                $this->invoice->user->name,
            ),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        $subTotal = 0;
        $totalTax = 0;
        foreach ($this->invoice->items as $item) {
            $itemSubTotal = (float) $item->quantity * (float) $item->unit_price;
            $itemTax = 0;
            if ((int) $item->tax_rate > 0) {
                $itemTax = $itemSubTotal * ((float) $item->tax_rate / 100);
            }
            $subTotal += $itemSubTotal;
            $totalTax += $itemTax;
        }
        $totalDue = ($subTotal + $totalTax);

        return new Content(
            markdown: 'emails.new-invoice-notification',
            with: [
                'isReminder' => $this->isReminder,
                'invoiceReference' => $this->invoice->invoice_reference,
                'recipientName' => $this->recipientName,
                'issuerUsername' => $this->invoice->user->name,
                'issueDate' => $this->invoice->issue_date->format(DateFormat::DATE_FORMAT->value),
                'dueDate' => $this->invoice->due_date->format(DateFormat::DATE_FORMAT->value),
                'dueDateDiff' => $this->invoice->due_date->diffForHumans(),
                'invoiceViewerUrl' => route('public.invoice.show', $this->invoice->invoice_reference),
                'subTotal' => sprintf('%.2f', $subTotal),
                'totalTax' => sprintf('%.2f', $totalTax),
                'totalDue' => sprintf('%.2f', $totalDue),
                'issuerEmail' => $this->invoice->user->email,
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
