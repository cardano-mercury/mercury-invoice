<?php

use App\Http\Controllers\API\APICustomersController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->middleware('auth:sanctum')->group(static function () {

    // Customers
    Route::resource('customers', APICustomersController::class)->except(['create', 'edit', 'destroy']);

    // NOTE: https://laravel.com/docs/11.x/controllers#restful-nested-resources

});
