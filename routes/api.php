<?php

use App\Http\Controllers\CarController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\RegisterController;
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
$auth = 'v1/auth/';
$version = '/v1';

Route::prefix($auth)->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/register', [RegisterController::class, 'store']);
});


Route::middleware($middlewareGroup)->group(function () use ($version) {
    Route::prefix($version)->group(function () {
        Route::resource('cars', CarController::class);
        Route::post('/cars/update', [CarController::class, 'update']);
        Route::post('/cars/publish', [CarController::class, 'publish']);
        Route::post('/cars/review', [CarController::class, 'review']);
        Route::post('/cars/media/add', [CarController::class, 'add_media']);
        Route::post('/cars/media/delete', [CarController::class, 'delete_media']);
    });
});



require __DIR__ . '/admin.php';