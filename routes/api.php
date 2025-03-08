<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\RegisterController;
use App\Http\Controllers\API\ForgotPasswordController;
use App\Http\Controllers\SneakersController;

Route::controller(RegisterController::class)->group(function(): void{
    Route::post('register', 'register');
    Route::post('login', 'login');
    Route::post('logout', 'logout');
});

// Add forgot password endpoint
Route::post('forgot_password', [ForgotPasswordController::class, 'forgotPassword']);

Route::resource('sneakers', SneakersController::class);
