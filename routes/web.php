<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Auth\VerificationController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/mailValidate', function () {
    return view('mailValidate');
});
Route::get('/alreadymailValidate', function () {
    return view('alreadymailValidate');
});

Route::get('/verify-email', [VerificationController::class, 'verifyEmail'])->name('verification.verify');

