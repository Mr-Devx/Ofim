<?php

use App\Http\Controllers\CarController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\ResetPasswordController;
use App\Http\Controllers\CurentTenantController;
use App\Http\Controllers\MessageController;
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
    Route::post('/verify-code',  [AuthController::class, 'verifyCode']);
    Route::post('/register', [RegisterController::class, 'store']);
    Route::post('/password-email', [ForgotPasswordController::class, 'sendResetLinkEmail']);
    Route::post('/verify-code-reset', [ResetPasswordController::class, 'verify']);
    Route::post('/password-reset', [ResetPasswordController::class, 'reset']);
});


Route::middleware($middlewareGroup)->group(function () use ($version) {
    Route::prefix($version)->group(function () {
        Route::get('/profile/{id}', [AuthController::class, 'profile']);
        Route::put('/update-profile/{id}', [AuthController::class, 'update']);
        Route::delete('/delete-profile/{id}', [AuthController::class, 'delete']);
        Route::put('/change-password/{id}', [AuthController::class, 'changePassword']);
        
        Route::post('/reserve-cars', [CurentTenantController::class, 'store']);
        Route::put('/update-reservation/{id}', [CurentTenantController::class, 'update']);
        Route::put('/note-reservation/{id}', [CurentTenantController::class, 'note']);
        Route::put('/extend-reservation/{id}', [CurentTenantController::class, 'extend']);
        Route::get('/cancel-reservation/{id}', [CurentTenantController::class, 'cancel']);
        Route::get('/valid-reservation/{id}', [CurentTenantController::class, 'valid']);
        Route::post('/comment-reservation', [MessageController::class, 'comment']);
        Route::put('/update-reservation/{id}', [MessageController::class, 'updatecomment']);
        Route::delete('/remove-reservation/{id}', [MessageController::class, 'deletecomment']);
        Route::post('/verifypayement-reservation', [CurentTenantController::class, 'verifypayement']);

        
        Route::get('/reservations', [CurentTenantController::class, 'index']);
        Route::get('/reservations/{id}', [CurentTenantController::class, 'show']);

        Route::resource('cars', CarController::class);
        Route::post('/cars/update', [CarController::class, 'update']);
        Route::post('/cars/publish', [CarController::class, 'publish']);
        Route::post('/cars/review', [CarController::class, 'review']);
        Route::post('/cars/media/add', [CarController::class, 'add_media']);
        Route::post('/cars/media/delete', [CarController::class, 'delete_media']);
    });
});

Route::post('password/reset', ['as' => 'password.reset','uses' => 'ResetPasswordController@reset']);
       



require __DIR__ . '/admin.php';
