<?php

use App\Http\Controllers\Api\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


// -----------------------------------------------------------------------------
// Admin API routes (session-guarded) – consumed via AJAX from Blade views
// -----------------------------------------------------------------------------


// -----------------------------------------------------------------------------
// Generic API authentication routes (Sanctum personal access tokens)
// -----------------------------------------------------------------------------

Route::post('login', [AuthController::class, 'login']);

Route::middleware('auth:api')->group(function () {
    Route::get('me', static function (Request $request) {
        return $request->user();
    });
    Route::post('logout', [AuthController::class, 'logout']);
});
