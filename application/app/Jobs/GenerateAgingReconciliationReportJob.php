<?php

namespace App\Jobs;

use Throwable;
use App\Enums\Status;
use App\Models\Report;
use App\Models\Invoice;
use Illuminate\Support\Str;
use App\Traits\UploadCSVTrait;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Collection;

class GenerateAgingReconciliationReportJob implements ShouldQueue
{
    use Queueable, UploadCSVTrait;

    private Report $report;

    /**
     * Create a new job instance.
     */
    public function __construct(Report $report)
    {
        $this->report = $report;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {

            $invoices = $this->loadInvoices();

            $fileName = sprintf(
                'reports/report-%d-%s-%s.csv',
                $this->report->id,
                Str::slug($this->report->type->value),
                Str::uuid()->toString(),
            );

            $csvRecords = [
                [
                    'ID',
                    'Invoice Reference',
                    'Invoice Status',
                    'Customer Reference',
                    'Customer',
                    'Issue Date',
                    'Due Date',
                    'Currency',
                    'Total',
                    'Is Overdue?'
                ],
            ];

            foreach ($invoices as $invoice) {
                $csvRecords[] = [
                    $invoice->id,
                    $invoice->invoice_reference,
                    $invoice->status->value,
                    $invoice->customer_reference,
                    $invoice->customer->name,
                    $invoice->issue_date->toDateString(),
                    $invoice->due_date->toDateString(),
                    $invoice->currency,
                    (float) $invoice->total,
                    $invoice->is_overdue ? 'Yes' : 'No',
                ];
            }

            $this->uploadCSV($fileName, $csvRecords);

            $this->report->update([
                'status' => Status::SUCCESS,
                'file_name' => $fileName,
                'generated_at' => now(),
            ]);

        } catch (Throwable $exception) {

            $this->report->update([
                'status' => Status::ERROR,
                'last_error' => sprintf(
                    '%s (%s:%d)',
                    $exception->getMessage(),
                    basename($exception->getFile()),
                    $exception->getLine(),
                ),
            ]);

        }
    }

    private function loadInvoices(): Collection
    {
        return Invoice::query()
            ->where('user_id', $this->report->user_id)
            ->where('status', Status::PUBLISHED)
            ->where('created_at', '>=', sprintf('%s 00:00:00', $this->report->from_date))
            ->where('created_at', '<=', sprintf('%s 23:59:59', $this->report->to_date))
            ->with([
                'customer' => static function ($query) {
                    $query->select('id', 'name');
                },
            ])
            ->orderBy('id', 'asc')
            ->get();
    }
}
