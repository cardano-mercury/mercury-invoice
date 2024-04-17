<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;

/**
 * Public Routes
 */

Route::get('/', [IndexController::class, 'index']);

/**
 * Authenticated Routes
 */

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('customers', CustomerController::class);
});

// TEST ROUTE :: START
Route::get('test', static function() {
    if (app()->environment('local')) {

        $user = \App\Models\User::query()
            ->where('id', 1)
            ->with([
                'customers.categories',
                'customers.defaultEmail',
                'customers.defaultPhone',
                'customers.defaultAddress',
                'customers.emails',
                'customers.phones',
                'customers.addresses',
                'products.categories',
                'services.categories',
                'customerCategories',
                'productCategories',
                'servicesCategories',
            ])
            ->first();

        dd($user->toArray());

    }
});
// TEST ROUTE :: END
