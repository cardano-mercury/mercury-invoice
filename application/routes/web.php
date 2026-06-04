<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\WebhookController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Public\PublicWebhookHandler;
use App\Http\Controllers\Public\PublicInvoiceController;

/**
 * Public Routes
 */

Route::get('/', [IndexController::class, 'index'])->name('home');

Route::prefix('invoice')->group(function () {
    Route::get('{encodedId}', [PublicInvoiceController::class, 'view'])->name('public.invoice.view');
    Route::get('{encodedId}/pay-via-stripe', [PublicInvoiceController::class, 'payViaStripe'])->name('public.invoice.pay-via-stripe');
    Route::post('{encodedId}/pay-via-crypto', [PublicInvoiceController::class, 'payViaCrypto'])->name('public.invoice.pay-via-crypto');
});

Route::prefix('incoming-webhooks')->group(function () {
    Route::post('stripe/{encodedUserId}', [PublicWebhookHandler::class, 'handleStripeWebhook'])->name('incoming-webhooks.stripe');
});

/**
 * Authenticated Routes
 */

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/stats', [DashboardController::class, 'stats'])->name('dashboard.stats');

    // Customers
    Route::get('customers/export', [CustomerController::class, 'export'])->name('customers.export');
    Route::resource('customers', CustomerController::class);

    // Customer Emails
    Route::post('customers/{customer}/emails', [CustomerController::class, 'storeEmail'])->name('customers.emails.store');
    Route::put('customers/{customer}/emails/{email}', [CustomerController::class, 'updateEmail'])->name('customers.emails.update');
    Route::delete('customers/{customer}/emails/{email}', [CustomerController::class, 'destroyEmail'])->name('customers.emails.destroy');

    // Customer Phones
    Route::post('customers/{customer}/phones', [CustomerController::class, 'storePhone'])->name('customers.phones.store');
    Route::put('customers/{customer}/phones/{phone}', [CustomerController::class, 'updatePhone'])->name('customers.phones.update');
    Route::delete('customers/{customer}/phones/{phone}', [CustomerController::class, 'destroyPhone'])->name('customers.phones.destroy');

    // Customer Addresses
    Route::post('customers/{customer}/addresses', [CustomerController::class, 'storeAddress'])->name('customers.addresses.store');
    Route::put('customers/{customer}/addresses/{address}', [CustomerController::class, 'updateAddress'])->name('customers.addresses.update');
    Route::delete('customers/{customer}/addresses/{address}', [CustomerController::class, 'destroyAddress'])->name('customers.addresses.destroy');

    // Customer Categories
    Route::put('customers/{customer}/categories', [CustomerController::class, 'updateCategories'])->name('customers.categories.update');
    Route::post('customer-categories', [CustomerController::class, 'storeCategory'])->name('customer-categories.store');
    Route::put('customer-categories/{customerCategory}', [CustomerController::class, 'updateCategory'])->name('customer-categories.update');
    Route::delete('customer-categories/{customerCategory}', [CustomerController::class, 'destroyCategory'])->name('customer-categories.destroy');

    // Products
    Route::get('products/export', [ProductController::class, 'export'])->name('products.export');
    Route::resource('products', ProductController::class);

    // Product Categories
    Route::put('products/{product}/categories', [ProductController::class, 'updateCategories'])->name('products.categories.update');
    Route::post('product-categories', [ProductController::class, 'storeCategory'])->name('product-categories.store');
    Route::put('product-categories/{productCategory}', [ProductController::class, 'updateCategory'])->name('product-categories.update');
    Route::delete('product-categories/{productCategory}', [ProductController::class, 'destroyCategory'])->name('product-categories.destroy');

    // Services
    Route::get('services/export', [ServiceController::class, 'export'])->name('services.export');
    Route::resource('services', ServiceController::class);

    // Service Categories
    Route::put('services/{service}/categories', [ServiceController::class, 'updateCategories'])->name('services.categories.update');
    Route::post('service-categories', [ServiceController::class, 'storeCategory'])->name('service-categories.store');
    Route::put('service-categories/{serviceCategory}', [ServiceController::class, 'updateCategory'])->name('service-categories.update');
    Route::delete('service-categories/{serviceCategory}', [ServiceController::class, 'destroyCategory'])->name('service-categories.destroy');

    // Invoices
    Route::get('invoices/export', [InvoiceController::class, 'export'])->name('invoices.export');
    Route::resource('invoices', InvoiceController::class)->except(['destroy']);
    Route::get('invoices/{invoice}/void', [InvoiceController::class, 'void'])->name('invoices.void');
    Route::get('invoices/{invoice}/restore', [InvoiceController::class, 'restore'])->name('invoices.restore');
    Route::get('invoices/{invoice}/sendReminderNotifications', [InvoiceController::class, 'sendReminderNotifications'])->name('invoices.sendReminderNotifications');
    Route::get('invoices/{invoice}/manuallyMarkAsPaid', [InvoiceController::class, 'manuallyMarkAsPaid'])->name('invoices.manuallyMarkAsPaid');

    Route::prefix('reports')->group(static function () {
        Route::get('/', [ReportController::class, 'index'])->name('reports.index');
        Route::post('/', [ReportController::class, 'generate'])->name('reports.generate');
        Route::get('{report}/download', [ReportController::class, 'download'])->name('reports.download');
        Route::get('{report}/delete', [ReportController::class, 'delete'])->name('reports.delete');
    });

    // User Settings
    Route::prefix('user/settings')->group(function () {
        Route::get('/', [SettingController::class, 'index'])->name('user.settings');
        Route::post('save-business-info', [SettingController::class, 'saveBusinessInfo'])->name('user.settings.save-business-info');
        Route::post('save-stripe-config', [SettingController::class, 'saveStripeConfig'])->name('user.settings.save-stripe-config');
        Route::post('save-crypto-config', [SettingController::class, 'saveCryptoConfig'])->name('user.settings.save-crypto-config');
    });

    // User Webhooks
    Route::resource('user/webhooks', WebhookController::class)->except(['create', 'show', 'edit']);
    Route::get('user/webhooks/{webhook}/logs', [WebhookController::class, 'logs'])->name('webhooks.logs');
    Route::get('user/webhooks/{webhook}/test', [WebhookController::class, 'test'])->name('webhooks.test');

});
