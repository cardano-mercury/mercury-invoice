<?php

namespace App\Console\Commands;

use Throwable;
use App\Enums\Status;
use App\Models\Report;
use App\Enums\ReportType;
use Illuminate\Console\Command;
use App\Traits\LogExceptionTrait;
use Illuminate\Support\Facades\Bus;
use App\Jobs\GenerateRevenueByProductReportJob;
use App\Jobs\GenerateRevenueByServiceReportJob;
use App\Jobs\GenerateRevenueByCustomerReportJob;
use App\Jobs\GenerateAgingReconciliationReportJob;
use App\Jobs\GenerateTotalRevenueByPeriodReportJob;

class GenerateReports extends Command
{
    use LogExceptionTrait;

    private const BATCH_SIZE = 100;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:generate-reports';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generates scheduled reports';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        try {

            $pendingReports = Report::query()
                ->where('status', Status::PENDING)
                ->orderBy('created_at', 'asc')
                ->limit(self::BATCH_SIZE)
                ->get();

            if ($pendingReports->count()) {

                $batchJobs = [];

                foreach ($pendingReports as $report) {
                    switch ($report->type) {
                        case ReportType::AGING_RECONCILIATION: $batchJobs[] = new GenerateAgingReconciliationReportJob($report); break;
                        case ReportType::REVENUE_BY_PRODUCT: $batchJobs[] = new GenerateRevenueByProductReportJob($report); break;
                        case ReportType::REVENUE_BY_SERVICE: $batchJobs[] = new GenerateRevenueByServiceReportJob($report); break;
                        case ReportType::REVENUE_BY_CUSTOMER: $batchJobs[] = new GenerateRevenueByCustomerReportJob($report); break;
                        case ReportType::TOTAL_REVENUE_BY_PERIOD: $batchJobs[] = new GenerateTotalRevenueByPeriodReportJob($report); break;
                    }
                }

                if (count($batchJobs)) {

                    Report::query()
                        ->whereIn('id', $pendingReports->pluck('id')->toArray())
                        ->update([
                            'status' => Status::GENERATING,
                        ]);

                    Bus::chain($batchJobs)->dispatch();

                    $this->info(sprintf(
                        'Processing %d reports',
                        count($batchJobs),
                    ));

                }

            }

        } catch (Throwable $exception) {

            $this->logException('Failed to start generate scheduled report cron', $exception);

        }
    }
}
