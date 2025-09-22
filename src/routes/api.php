<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ConfigController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// ✅ Config-Routen nur für Admins
Route::prefix('v2/config')
    ->middleware('auth:sanctum', 'role:admin')
    ->group(function () {
    Route::post('/mc',    [ConfigController::class, 'getConfig'])->defaults('type', 'mc');
    Route::post('/ebike', [ConfigController::class, 'getConfig'])->defaults('type', 'ebike');
    Route::post('/app',   [ConfigController::class, 'getConfig'])->defaults('type', 'app');

    Route::put('/mc',    [ConfigController::class, 'setConfig'])->defaults('type', 'mc');
    Route::put('/ebike', [ConfigController::class, 'setConfig'])->defaults('type', 'ebike');
    Route::put('/app',   [ConfigController::class, 'setConfig'])->defaults('type', 'app');
});
