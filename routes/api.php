<?php

use App\Http\Controllers\Admin\ProductOrderController;
use App\Http\Controllers\Api\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


// -----------------------------------------------------------------------------
// Admin API routes (session-guarded) – consumed via AJAX from Blade views
// -----------------------------------------------------------------------------

Route::middleware(['web', 'auth:admin'])->prefix('admin')->name('admin.')->group(function () {
    // DataTable: Orders that contain a given product
    Route::match(['get', 'post'], 'products/{product}/orders', [ProductOrderController::class, 'getOrdersForProduct'])
        ->name('products.orders.data');
});

// -----------------------------------------------------------------------------
// Generic API authentication routes (Passport password grant)
// -----------------------------------------------------------------------------

Route::post('login', [AuthController::class, 'login']);

Route::middleware('auth:api')->group(function () {
    Route::get('me', static function (Request $request) {
        return $request->user();
    });
    Route::post('logout', [AuthController::class, 'logout']);
});
