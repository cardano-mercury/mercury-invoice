<?php

use App\Http\Controllers\CustomerController;
use Inertia\Inertia;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');

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
