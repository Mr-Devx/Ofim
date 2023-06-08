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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

// Route::group(['prefix' => '/v1'], function () {
//     Route::resource('cars', CarController::class)/**->middleware('auth:sanctum')*/;
// });


$middlewareGroup = ['auth:sanctum'];
$auth = 'v1/auth/';
$version = '/v1';

Route::prefix($auth)->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/verify-code',  [AuthController::class, 'verifyCode']);
    Route::post('/register', [RegisterController::class, 'store']);
});



Route::middleware($middlewareGroup)->group(function () use ($version) {
    Route::prefix($version)->group(function () {
        Route::get('/profile/{id}', [AuthController::class, 'profile']);
        Route::put('/update-profile/{id}', [AuthController::class, 'update']);
        Route::delete('/delete-profile/{id}', [AuthController::class, 'delete']);
        Route::put('/change-password/{id}', [AuthController::class, 'changePassword']);

    });
});
