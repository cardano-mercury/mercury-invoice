<?php

namespace App\Console\Commands;

use App\Enums\Status;
use App\Enums\PaymentMethod;
use App\Models\InvoicePayment;
use Illuminate\Console\Command;
use App\Jobs\ProcessCryptoPaymentJob;

class ProcessCryptoPayments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:process-crypto-payments';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process crypto payments';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        // Find all crypto invoice payments that needs processing
        $invoicePayments = InvoicePayment::query()
            ->where('payment_method', PaymentMethod::CRYPTO->value)
            ->where('status', Status::PAYMENT_PROCESSING->value)
            ->get();

        // Proceed if any invoice payments were found
        if ($invoicePayments->count()) {

            // Dispatch a job to process the invoice payment
            foreach ($invoicePayments as $invoicePayment) {
                dispatch(new ProcessCryptoPaymentJob($invoicePayment));
            }

            // Debug
            $this->info(sprintf(
                'Found %d invoice payments that needs processing',
                $invoicePayments->count(),
            ));

        }
    }
}
