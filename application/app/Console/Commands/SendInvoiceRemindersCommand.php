<?php

namespace App\Console\Commands;

use Throwable;
use App\Enums\Status;
use App\Models\Invoice;
use App\Models\InvoiceActivity;
use Illuminate\Console\Command;
use App\Traits\LogExceptionTrait;
use App\Jobs\SendNewInvoiceNotificationMailJob;

class SendInvoiceRemindersCommand extends Command
{
    use LogExceptionTrait;

    const MAX_DUE_DAYS = 3;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-invoice-reminders-command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send invoice reminders';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        try {

            // Parse target due date
            $tomorrow = now()->addDay()->toDateString();
            $futureDate = now()->addDays(self::MAX_DUE_DAYS)->toDateString();

            // Select all invoices that are due within the configured due days range
            $invoices = Invoice::query()
                ->where('status', Status::PUBLISHED)
                ->whereBetween('due_date', [$tomorrow, $futureDate])
                ->get();

            // Proceed if there is anyone to send invoice reminds
            if ($invoices->count()) {

                /** @var Invoice $invoice */
                foreach ($invoices as $invoice) {

                    // Dispatch job to notify invoice recipients
                    dispatch(new SendNewInvoiceNotificationMailJob($invoice, true));

                    // Record invoice activity
                    InvoiceActivity::create([
                        'invoice_id' => $invoice->id,
                        'activity' => 'Automated reminder notifications were sent.',
                    ]);

                }

                // Task completed
                $this->info(sprintf(
                    'Automated reminder notifications were sent. %d invoices found.',
                    $invoices->count(),
                ));

            }

        } catch (Throwable $exception) {

            // Handle exception
            $this->logException('SendInvoiceRemindersCommand Error', $exception);

        }
    }
}
