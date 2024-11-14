<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Inertia\Inertia;
use App\Enums\Status;
use Inertia\Response;
use App\Models\Report;
use App\Models\Product;
use App\Models\Service;
use App\Models\Customer;
use App\Enums\ReportType;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Contracts\Container\BindingResolutionException;

class ReportController extends Controller
{
    private const MAX_LIVE_REPORTS = 5;

    public function index(): Response
    {
        $reports = Report::query()
            ->where('user_id', auth()->id())
            ->with([
                'product' => static function ($query) {
                    $query->select('id', 'name');
                },
                'service' => static function ($query) {
                    $query->select('id', 'name');
                },
                'customer' => static function ($query) {
                    $query->select('id', 'name');
                },
            ])
            ->orderBy('id', 'desc')
            ->get();
        $reportTypes = ReportType::values();
        $customers = Customer::query()
            ->where('user_id', auth()->id())
            ->select('id', 'name')
            ->get();
        $products = Product::query()
            ->where('user_id', auth()->id())
            ->select('id', 'name')
            ->get();
        $services = Service::query()
            ->where('user_id', auth()->id())
            ->select('id', 'name')
            ->get();

        return Inertia::render(
            'Report/Index',
            compact(
                'reports',
                'reportTypes',
                'customers',
                'products',
                'services',
            ));
    }

    public function generate(Request $request): RedirectResponse
    {
        $rules = [
            'reportType' => ['required', 'in:' . implode(',', ReportType::values()), static function ($attribute, $value, $fail) {
                $currentLiveReportCount = Report::query()
                    ->where('user_id', auth()->id())
                    ->whereIn('status', [Status::PENDING->value, Status::GENERATING->value])
                    ->count();
                if ($currentLiveReportCount >= self::MAX_LIVE_REPORTS) {
                    $fail('You have reached the maximum number of reports (' . self::MAX_LIVE_REPORTS . ') that can be generated concurrently. Please wait and try again later.');
                }
            }],
            'reportName' => ['required', 'string', 'min:3', 'max:64'],
            'fromDate' => ['required', 'date', static function ($attribute, $value, $fail) use($request) {
                if (Carbon::parse($value)->gt(Carbon::parse($request->get('toDate')))) {
                    $fail('The from date cannot be greater than to date.');
                }
            }],
            'toDate' => ['required', 'date', static function ($attribute, $value, $fail) use($request) {
                if (Carbon::parse($value)->lt(Carbon::parse($request->get('fromDate')))) {
                    $fail('The to date cannot be less than from date.');
                }
            }],
        ];

        if ($request->get('reportType') === ReportType::REVENUE_BY_PRODUCT->value) {
            $rules['productId'] = ['required', 'integer', Rule::exists('products', 'id')->where(static function ($query) use($request) {
                return $query
                    ->where('user_id', auth()->id())
                    ->where('id', $request->get('productId'));
            })];
        }

        if ($request->get('reportType') === ReportType::REVENUE_BY_SERVICE->value) {
            $rules['serviceId'] = ['required', 'integer', Rule::exists('services', 'id')->where(static function ($query) use($request) {
                return $query
                    ->where('user_id', auth()->id())
                    ->where('id', $request->get('serviceId'));
            })];
        }

        if ($request->get('reportType') === ReportType::REVENUE_BY_CUSTOMER->value) {
            $rules['customerId'] = ['required', 'integer', Rule::exists('customers', 'id')->where(static function ($query) use($request) {
                return $query
                    ->where('user_id', auth()->id())
                    ->where('id', $request->get('customerId'));
            })];
        }

        $validated = $request->validate($rules);

        $report = new Report;
        $report->fill([
            'user_id' => auth()->id(),
            'product_id' => $validated['productId'] ?? null,
            'service_id' => $validated['serviceId'] ?? null,
            'customer_id' => $validated['customerId'] ?? null,
            'name' => $validated['reportName'],
            'type' => $validated['reportType'],
            'from_date' => $validated['fromDate'],
            'to_date' => $validated['toDate'],
            'status' => Status::PENDING,
        ]);
        $report->save();

        return to_route('reports.index');
    }

    /**
     * @throws BindingResolutionException
     */
    public function download(Report $report)
    {
        if ($report->status === Status::SUCCESS->value) {
            return response()->make($this->getStorageInstance()->get($report->file_name), 200, [
                'Content-type' => 'application/json',
                'Content-Disposition' => sprintf(
                    'attachment; filename="%s"',
                    basename($report->file_name),
                ),
            ]);
        }

        abort(404);
    }

    public function delete(Report $report): RedirectResponse
    {
        if ($report->status === Status::SUCCESS->value) {
            $this->getStorageInstance()->delete($report->file_name);
            $report->delete();
            return to_route('reports.index');
        }

        abort(404);
    }

    private function getStorageInstance(): Filesystem
    {
        return Storage::disk(app()->environment('local') ? 'public' : 's3');
    }
}
