<?php

namespace App\Http\Middleware;

use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Product;
use App\Models\Report;
use App\Models\Service;
use Inertia\Middleware;
use Illuminate\Http\Request;

class HandleInertiaRequests extends Middleware {

    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     */
    public function version(Request $request): ?string {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     * @return array<string, mixed>
     */
    public function share(Request $request): array {
        return [
            ...parent::share($request),
            'flash' => [
                'success' => $request->session()->get('success'),
                'info'    => $request->session()->get('info'),
                'error'   => $request->session()->get('error'),
            ],
            'count' => fn () => $request->user()
                ? [
                    'customers' => Customer::query()->where('user_id', $request->user()->id)->count(),
                    'products' => Product::query()->where('user_id', $request->user()->id)->count(),
                    'services' => Service::query()->where('user_id', $request->user()->id)->count(),
                    'invoices' => Invoice::query()->where('user_id', $request->user()->id)->count(),
                    'reports' => Report::query()->where('user_id', $request->user()->id)->count(),
                ]
                : [
                    'customers' => 0,
                    'products' => 0,
                    'services' => 0,
                    'invoices' => 0,
                    'reports' => 0,
                ],
            'appVersion' => config('cardanomercury.app_version'),
        ];
    }
}
