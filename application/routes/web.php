<?php

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
});

// TEST ROUTE :: START
Route::get('test', static function() {
    if (app()->environment('local')) {

//        $customer = \App\Models\Customer::query()
//            ->where('id', 1)
//            ->with([
//                'categories',
//                'defaultEmail',
//                'defaultPhone',
//                'defaultAddress',
//                'emails',
//                'phones',
//                'addresses',
//            ])
//            ->first();
//        dd($customer->toArray());

//        $category = \App\Models\Category::query()
//            ->where('user_id', 1)
//            ->where('id', 1)
//            ->with('customers')
//            ->get();
//        dd($category->toArray());

//        $categories = \App\Models\Category::all();
//        $allCategoryIds = $categories->pluck('id')->toArray();
//        $randomCustomerCategoryIds = (array) array_rand($allCategoryIds, random_int(1, 3));
//        dd($randomCustomerCategoryIds);

    }
});
// TEST ROUTE :: END
