<?php

use App\Http\Controllers\CarController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\ResetPasswordController;
use App\Http\Controllers\CityController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\AlertController;


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

        Route::get('countries', [CountryController::class, 'index']);
        Route::get('cities', [CityController::class, 'index']);
        Route::get('cities/{id}', [CityController::class, 'citiesCountry']);


        

        Route::post('/alerts', [AlertController::class, 'store']);
        Route::get('/alerts', [AlertController::class, 'index']);
        Route::get('/alerts/{id}', [AlertController::class, 'show']);
        Route::put('/alerts/{id}', [AlertController::class, 'update']);
        Route::delete('/alerts/{id}', [AlertController::class, 'destroy']);
    });
});

Route::post('password/reset', ['as' => 'password.reset','uses' => 'ResetPasswordController@reset']);
       



require __DIR__ . '/admin.php';
