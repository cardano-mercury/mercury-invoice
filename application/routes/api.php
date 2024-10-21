<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\APIServicesController;
use App\Http\Controllers\API\APIProductsController;
use App\Http\Controllers\API\APICustomersController;
use App\Http\Controllers\API\APICustomerEmailsController;
use App\Http\Controllers\API\APICustomerPhonesController;
use App\Http\Controllers\API\APIServiceCategoriesController;
use App\Http\Controllers\API\APIProductCategoriesController;
use App\Http\Controllers\API\APICustomerAddressesController;
use App\Http\Controllers\API\APICustomerCategoriesController;

Route::prefix('v1')->middleware('auth:sanctum')->group(static function () {

    // Customers
    Route::resource('customers', APICustomersController::class)->except(['create', 'edit', 'destroy']);
    Route::resource('customers.emails', APICustomerEmailsController::class)->except(['create', 'edit', 'destroy'])->scoped();
    Route::resource('customers.phones', APICustomerPhonesController::class)->except(['create', 'edit', 'destroy'])->scoped();
    Route::resource('customers.addresses', APICustomerAddressesController::class)->except(['create', 'edit', 'destroy'])->scoped();
    Route::resource('customer-categories', APICustomerCategoriesController::class)->except(['create', 'edit', 'destroy']);
    Route::put('customer-categories-sync', [APICustomerCategoriesController::class, 'sync']);

    // Products
    Route::resource('products', APIProductsController::class)->except(['create', 'edit', 'destroy']);
    Route::resource('product-categories', APIProductCategoriesController::class)->except(['create', 'edit', 'destroy']);
    Route::put('product-categories-sync', [APIProductCategoriesController::class, 'sync']);

    // Services
    Route::resource('services', APIServicesController::class)->except(['create', 'edit', 'destroy']);
    Route::resource('service-categories', APIServiceCategoriesController::class)->except(['create', 'edit', 'destroy']);
    Route::put('service-categories-sync', [APIServiceCategoriesController::class, 'sync']);

});
