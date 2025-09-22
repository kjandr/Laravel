<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\ConfigController;

//
// Öffentliche Routen
//
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth:sanctum');
Route::get('/admin/users', [AuthController::class, 'listUsers'])
        ->middleware('auth:sanctum', 'role:admin');


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
