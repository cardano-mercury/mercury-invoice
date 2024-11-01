<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\APIInvoicesController;
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
    Route::resource('customers', APICustomersController::class, ['as' => 'api'])->except(['create', 'edit', 'destroy']);
    Route::resource('customers.emails', APICustomerEmailsController::class, ['as' => 'api'])->except(['create', 'edit', 'destroy'])->scoped();
    Route::resource('customers.phones', APICustomerPhonesController::class, ['as' => 'api'])->except(['create', 'edit', 'destroy'])->scoped();
    Route::resource('customers.addresses', APICustomerAddressesController::class, ['as' => 'api'])->except(['create', 'edit', 'destroy'])->scoped();
    Route::resource('customer-categories', APICustomerCategoriesController::class, ['as' => 'api'])->except(['create', 'edit', 'destroy']);
    Route::put('customer-categories-sync', [APICustomerCategoriesController::class, 'sync']);

    // Products
    Route::resource('products', APIProductsController::class, ['as' => 'api'])->except(['create', 'edit', 'destroy']);
    Route::resource('product-categories', APIProductCategoriesController::class, ['as' => 'api'])->except(['create', 'edit', 'destroy']);
    Route::put('product-categories-sync', [APIProductCategoriesController::class, 'sync']);

    // Services
    Route::resource('services', APIServicesController::class, ['as' => 'api'])->except(['create', 'edit', 'destroy']);
    Route::resource('service-categories', APIServiceCategoriesController::class, ['as' => 'api'])->except(['create', 'edit', 'destroy']);
    Route::put('service-categories-sync', [APIServiceCategoriesController::class, 'sync']);

    // Invoices
    Route::resource('invoices', APIInvoicesController::class, ['as' => 'api'])->except(['create', 'edit', 'destroy']);
    Route::put('invoice-void/{invoice}', [APIInvoicesController::class, 'void']);
    Route::put('invoice-restore/{invoice}', [APIInvoicesController::class, 'restore']);
    Route::put('invoice-send-reminder-notifications/{invoice}', [APIInvoicesController::class, 'sendReminderNotifications']);
    Route::put('invoice-manually-mark-as-paid/{invoice}', [APIInvoicesController::class, 'manuallyMarkAsPaid']);
});
