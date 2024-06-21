<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\WebhookController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PublicInvoiceController;

/**
 * Public Routes
 */

Route::get('/', [IndexController::class, 'index']);

Route::get('invoice/{encodedId}', [PublicInvoiceController::class, 'show'])->name('public.invoice.show');

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
    Route::get('/dashboard/send-test-email', [DashboardController::class, 'sendTestEmail'])->name('dashboard.send-test-email');

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

    // User Webhooks
    Route::resource('user/webhooks', WebhookController::class)->except(['create', 'show', 'edit']);
    Route::get('user/webhooks/{webhook}/logs', [WebhookController::class, 'logs'])->name('webhooks.logs');
    Route::get('user/webhooks/{webhook}/test', [WebhookController::class, 'test'])->name('webhooks.test');

});
