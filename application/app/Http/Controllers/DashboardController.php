<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Enums\Status;
use Inertia\Response;
use App\Models\Product;
use App\Models\Service;
use App\Models\Invoice;
use App\Models\Customer;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    private const DEFAULT_TIME_FRAME = 7;
    private const VALID_TIME_FRAMES = [1, 7, 30, 365];

    public function index(Request $request): Response
    {
        $stats = $this->stats($request);

        return Inertia::render('Dashboard', compact('stats'));
    }

    public function stats(Request $request): array
    {
        $timeframe = (int) $request->query('timeframe', self::DEFAULT_TIME_FRAME);

        if (!in_array($timeframe, self::VALID_TIME_FRAMES)) {
            return [];
        }

        return [
            'counts' => [
                'customers' => $this->customersCount($timeframe),
                'products' => $this->productsCount($timeframe),
                'services' => $this->servicesCount($timeframe),
                'invoices' => $this->invoicesCount($timeframe),
                'paid_invoices' => $this->paidInvoicesCount($timeframe),
                'unpaid_invoices' => $this->unPaidInvoicesCount($timeframe),
            ],
            'invoices' => $this->recentInvoices($timeframe),
        ];
    }

    private function customersCount(int $timeframe): int
    {
        return Customer::query()
            ->where('user_id', auth()->id())
            ->where('created_at', '>=', now()->subDays($timeframe)->setTime(0, 0))
            ->count();
    }

    private function productsCount(int $timeframe): int
    {
        return Product::query()
            ->where('user_id', auth()->id())
            ->where('created_at', '>=', now()->subDays($timeframe)->setTime(0, 0))
            ->count();
    }

    private function servicesCount(int $timeframe): int
    {
        return Service::query()
            ->where('user_id', auth()->id())
            ->where('created_at', '>=', now()->subDays($timeframe)->setTime(0, 0))
            ->count();
    }

    private function invoicesCount(int $timeframe): int
    {
        return Invoice::query()
            ->where('user_id', auth()->id())
            ->where('created_at', '>=', now()->subDays($timeframe)->setTime(0, 0))
            ->count();
    }

    private function recentInvoices(int $timeframe): array
    {
        return Invoice::query()
            ->where('user_id', auth()->id())
            ->where('created_at', '>=', now()->subDays($timeframe)->setTime(0, 0))
            ->with(['customer'])
            ->orderBy('issue_date', 'asc')
            ->get()
            ->map(static function (Invoice $invoice) {
                return [
                    'status' => $invoice->status,
                    'customer' => [
                        'id' => $invoice->customer->id,
                        'name' => $invoice->customer->name,
                    ],
                    'issue_date' => $invoice->issue_date->toDateString(),
                    'due_date' => $invoice->due_date->toDateString(),
                    'total' => $invoice->total,
                    'currency' => $invoice->currency,
                    'is_overdue' => $invoice->is_overdue,
                ];
            })
            ->toArray();
    }

    private function paidInvoicesCount(int $timeframe): int
    {
        return Invoice::query()
            ->where('user_id', auth()->id())
            ->where('created_at', '>=', now()->subDays($timeframe)->setTime(0, 0))
            ->where('status', Status::PAID)
            ->count();
    }

    private function unPaidInvoicesCount(int $timeframe): int
    {
        return Invoice::query()
            ->where('user_id', auth()->id())
            ->where('created_at', '>=', now()->subDays($timeframe)->setTime(0, 0))
            ->where('status', Status::PUBLISHED)
            ->count();
    }
}
