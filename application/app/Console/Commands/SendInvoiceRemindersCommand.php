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
    const BATCH_SIZE = 100;

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

            // Calculate dates
            $tomorrow = now()->addDay()->toDateString();
            $futureDate = now()->addDays(self::MAX_DUE_DAYS)->toDateString();

            // Initialise counters and activity collection
            $processedCount = 0;
            $activities = collect();

            // Use a lazy collection for minimal memory footprint
            Invoice::query()
                ->where('status', Status::PUBLISHED)
                ->whereBetween('due_date', [$tomorrow, $futureDate])
                ->whereDoesntHave('recipients', function ($query) {
                    $query->where('address', 'like', '%@example%');
                })
                ->with('recipients')
                ->lazy()
                ->each(function ($invoice) use (&$processedCount, &$activities) {

                    // Dispatch notification job
                    dispatch(new SendNewInvoiceNotificationMailJob($invoice, true));

                    // Collect activity data
                    $activities->push([
                        'invoice_id' => $invoice->id,
                        'activity' => 'Automated reminder notifications were sent.',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);

                    // Keep track of processed count
                    $processedCount++;

                    // Batch insert when we reach the batch size
                    if ($activities->count() >= self::BATCH_SIZE) {
                        InvoiceActivity::insert($activities->toArray());
                        $activities = collect();
                    }

                });

            // Insert any remaining activities
            if ($activities->isNotEmpty()) {
                InvoiceActivity::insert($activities->toArray());
            }

            // Debug log
            if ($processedCount > 0) {
                $this->info(sprintf(
                    'Automated reminder notifications were sent. %d invoices processed.',
                    $processedCount
                ));
            } else {
                $this->info('No invoices found for reminder notifications.');
            }


        } catch (Throwable $exception) {

            // Handle exception
            $this->logException('SendInvoiceRemindersCommand Error', $exception);

        }
    }
}
