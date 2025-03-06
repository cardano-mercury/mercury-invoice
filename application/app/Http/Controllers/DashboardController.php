<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Enums\Status;
use Inertia\Response;
use App\Models\Product;
use App\Models\Service;
use App\Models\Invoice;
use App\Models\Customer;
use App\Models\InvoiceItem;
use App\Models\InvoicePayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
            'payment_methods' => $this->paymentMethodDistribution($timeframe),
            'top_customers' => $this->topCustomersByRevenue($timeframe),
            'top_products' => $this->topProductsByRevenue($timeframe),
            'top_services' => $this->topServicesByRevenue($timeframe),
            'average_invoice' => $this->averageInvoiceValue($timeframe),
            'product_vs_service_revenue' => $this->productVsServiceRevenue($timeframe),
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
    
    private function paymentMethodDistribution(int $timeframe): array
    {
        return InvoicePayment::query()
            ->join('invoices', 'invoice_payments.invoice_id', '=', 'invoices.id')
            ->where('invoices.user_id', auth()->id())
            ->where('invoice_payments.created_at', '>=', now()->subDays($timeframe)->setTime(0, 0))
            ->where('invoice_payments.status', Status::PAID)
            ->select('payment_method', DB::raw('count(*) as count'), DB::raw('sum(payment_amount) as total_amount'))
            ->groupBy('payment_method')
            ->get()
            ->map(function ($item) {
                return [
                    'method' => $item->payment_method->value,
                    'count' => $item->count,
                    'amount' => $item->total_amount,
                ];
            })
            ->toArray();
    }
    
    private function topCustomersByRevenue(int $timeframe): array
    {
        $userId = auth()->id();
        
        // Get the user's preferred currency from the averageInvoiceValue method
        $currency = $this->getPreferredCurrency($timeframe);
        
        return Invoice::query()
            ->where('user_id', $userId)
            ->where('status', Status::PAID)
            ->where('created_at', '>=', now()->subDays($timeframe)->setTime(0, 0))
            ->where('currency', $currency)
            ->with('customer')
            ->select('customer_id', DB::raw('sum(total) as total_revenue'), DB::raw('count(*) as invoice_count'))
            ->groupBy('customer_id')
            ->orderByDesc('total_revenue')
            ->limit(5)
            ->get()
            ->map(function ($item) {
                return [
                    'customer_id' => $item->customer_id,
                    'customer_name' => $item->customer->name,
                    'revenue' => $item->total_revenue,
                    'invoice_count' => $item->invoice_count,
                ];
            })
            ->toArray();
    }
    
    private function topProductsByRevenue(int $timeframe): array
    {
        $userId = auth()->id();
        
        // Get the user's preferred currency
        $currency = $this->getPreferredCurrency($timeframe);
        
        return InvoiceItem::query()
            ->join('invoices', 'invoice_items.invoice_id', '=', 'invoices.id')
            ->join('products', 'invoice_items.product_id', '=', 'products.id')
            ->where('invoices.user_id', $userId)
            ->where('invoices.status', Status::PAID)
            ->where('invoices.created_at', '>=', now()->subDays($timeframe)->setTime(0, 0))
            ->where('invoices.currency', $currency)
            ->whereNotNull('invoice_items.product_id')
            ->select(
                'products.id',
                'products.name',
                DB::raw('SUM(invoice_items.quantity * invoice_items.unit_price) as revenue'),
                DB::raw('SUM(invoice_items.quantity) as total_quantity')
            )
            ->groupBy('products.id', 'products.name')
            ->orderByDesc('revenue')
            ->limit(5)
            ->get()
            ->toArray();
    }
    
    private function topServicesByRevenue(int $timeframe): array
    {
        $userId = auth()->id();
        
        // Get the user's preferred currency
        $currency = $this->getPreferredCurrency($timeframe);
        
        return InvoiceItem::query()
            ->join('invoices', 'invoice_items.invoice_id', '=', 'invoices.id')
            ->join('services', 'invoice_items.service_id', '=', 'services.id')
            ->where('invoices.user_id', $userId)
            ->where('invoices.status', Status::PAID)
            ->where('invoices.created_at', '>=', now()->subDays($timeframe)->setTime(0, 0))
            ->where('invoices.currency', $currency)
            ->whereNotNull('invoice_items.service_id')
            ->select(
                'services.id',
                'services.name',
                DB::raw('SUM(invoice_items.quantity * invoice_items.unit_price) as revenue'),
                DB::raw('SUM(invoice_items.quantity) as total_quantity')
            )
            ->groupBy('services.id', 'services.name')
            ->orderByDesc('revenue')
            ->limit(5)
            ->get()
            ->toArray();
    }
    
    private function getPreferredCurrency(int $timeframe): string
    {
        // Get most commonly used currency for this user
        return Invoice::query()
            ->where('user_id', auth()->id())
            ->where('created_at', '>=', now()->subDays($timeframe)->setTime(0, 0))
            ->select('currency')
            ->selectRaw('COUNT(*) as count')
            ->groupBy('currency')
            ->orderByDesc('count')
            ->first()?->currency ?? 'ADA';
    }
    
    private function averageInvoiceValue(int $timeframe): array
    {
        $userId = auth()->id();
        
        // Get most commonly used currency for this user
        $preferredCurrency = $this->getPreferredCurrency($timeframe);

        $result = Invoice::query()
            ->where('user_id', $userId)
            ->where('status', Status::PAID)
            ->where('created_at', '>=', now()->subDays($timeframe)->setTime(0, 0))
            ->where('currency', $preferredCurrency)
            ->select(
                DB::raw('AVG(total) as average'),
                DB::raw('MAX(total) as maximum'),
                DB::raw('MIN(total) as minimum'),
                DB::raw('COUNT(*) as count')
            )
            ->first();
            
        return [
            'average' => $result ? round($result->average, 2) : 0,
            'maximum' => $result ? round($result->maximum, 2) : 0,
            'minimum' => $result ? round($result->minimum, 2) : 0,
            'count' => $result ? $result->count : 0,
            'currency' => $preferredCurrency,
        ];
    }
    
    private function productVsServiceRevenue(int $timeframe): array
    {
        $userId = auth()->id();
        $currency = $this->getPreferredCurrency($timeframe);
        
        $products = InvoiceItem::query()
            ->join('invoices', 'invoice_items.invoice_id', '=', 'invoices.id')
            ->where('invoices.user_id', $userId)
            ->where('invoices.status', Status::PAID)
            ->where('invoices.created_at', '>=', now()->subDays($timeframe)->setTime(0, 0))
            ->where('invoices.currency', $currency)
            ->whereNotNull('invoice_items.product_id')
            ->select(
                DB::raw('DATE(invoices.issue_date) as date'),
                DB::raw('SUM(invoice_items.quantity * invoice_items.unit_price) as amount')
            )
            ->groupBy('date')
            ->get()
            ->keyBy('date')
            ->map(function ($item) {
                return [
                    'date' => $item->date,
                    'amount' => round($item->amount, 2),
                ];
            })
            ->toArray();

        $services = InvoiceItem::query()
            ->join('invoices', 'invoice_items.invoice_id', '=', 'invoices.id')
            ->where('invoices.user_id', $userId)
            ->where('invoices.status', Status::PAID)
            ->where('invoices.created_at', '>=', now()->subDays($timeframe)->setTime(0, 0))
            ->where('invoices.currency', $currency)
            ->whereNotNull('invoice_items.service_id')
            ->select(
                DB::raw('DATE(invoices.issue_date) as date'),
                DB::raw('SUM(invoice_items.quantity * invoice_items.unit_price) as amount')
            )
            ->groupBy('date')
            ->get()
            ->keyBy('date')
            ->map(function ($item) {
                return [
                    'date' => $item->date,
                    'amount' => round($item->amount, 2),
                ];
            })
            ->toArray();

        return [
            'products' => $products,
            'services' => $services,
            'currency' => $currency,
        ];
    }
}
