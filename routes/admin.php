<?php

use App\Http\Controllers\CarController;
use Illuminate\Support\Facades\Route;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/


$middlewareGroup = ['auth:sanctum'];
$version = '/v1/admin/';


Route::middleware($middlewareGroup)->group(function () use ($version) {
    Route::prefix($version)->group(function () {
        Route::post('/cars/approve', [CarController::class, 'validation']);
        Route::post('/cars/revoke', [CarController::class, 'revocation']);
    });
});
