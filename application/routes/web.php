<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\WebhookController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ServiceController;
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

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/send-test-email', [DashboardController::class, 'sendTestEmail'])->name('dashboard.send-test-email');

    // Customers
    Route::resource('customers', CustomerController::class);

    // Products
    Route::resource('products', ProductController::class);

    // Services
    Route::resource('services', ServiceController::class);

    // User Webhooks
    Route::resource('user/webhooks', WebhookController::class)->except(['create', 'show', 'edit']);
    Route::get('user/webhooks/{webhook}/test', [WebhookController::class, 'test'])->name('webhooks.test');

});

// TEST ROUTE :: START
Route::get('test', static function() {
    if (app()->environment('local')) {

        $hmacAlgo = 'sha256';
        $hmacSecret = \Illuminate\Support\Str::random(128);

        $myPayload = json_encode([
            'name' => 'bob',
            'event' => 'Invoice Paid',
            'invoice_uuid' => 'aaa-bb-ccc',
        ]);

        // This is what is sent to the webhook url along with the payload
        $givenPayloadHMACSignature = hash_hmac($hmacAlgo, $myPayload, $hmacSecret);

        // User must then verify the hmac hash are equal using shared secret
        $userGeneratedHMACSignature = hash_hmac('sha256', $myPayload, $hmacSecret);
        $hmacSignatureValid = hash_equals($userGeneratedHMACSignature, $givenPayloadHMACSignature);

        dd(compact(
            'hmacAlgo',
            'hmacSecret',
            'myPayload',
            'givenPayloadHMACSignature',
            'hmacSignatureValid',
        ));

    }
});
// TEST ROUTE :: END
