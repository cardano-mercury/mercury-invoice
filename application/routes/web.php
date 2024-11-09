<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\IndexController;
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

    // Products
    Route::get('products/export', [ProductController::class, 'export'])->name('products.export');
    Route::resource('products', ProductController::class);

    // Services
    Route::get('services/export', [ServiceController::class, 'export'])->name('services.export');
    Route::resource('services', ServiceController::class);

    // Invoices
    Route::get('invoices/export', [InvoiceController::class, 'export'])->name('invoices.export');
    Route::resource('invoices', InvoiceController::class)->except(['destroy']);
    Route::get('invoices/{invoice}/void', [InvoiceController::class, 'void'])->name('invoices.void');
    Route::get('invoices/{invoice}/restore', [InvoiceController::class, 'restore'])->name('invoices.restore');
    Route::get('invoices/{invoice}/sendReminderNotifications', [InvoiceController::class, 'sendReminderNotifications'])->name('invoices.sendReminderNotifications');
    Route::get('invoices/{invoice}/manuallyMarkAsPaid', [InvoiceController::class, 'manuallyMarkAsPaid'])->name('invoices.manuallyMarkAsPaid');

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

