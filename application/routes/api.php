<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\APICustomersController;
use App\Http\Controllers\API\APICustomerEmailsController;
use App\Http\Controllers\API\APICustomerPhonesController;

Route::prefix('v1')->middleware('auth:sanctum')->group(static function () {

    // Customers
    Route::resource('customers', APICustomersController::class)->except(['create', 'edit', 'destroy']);
    Route::resource('customers.emails', APICustomerEmailsController::class)->except(['create', 'edit', 'destroy'])->scoped();
    Route::resource('customers.phones', APICustomerPhonesController::class)->except(['create', 'edit', 'destroy'])->scoped();

});
