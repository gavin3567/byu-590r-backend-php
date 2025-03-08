<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\RegisterController;
use App\Http\Controllers\API\ForgotPasswordController;
use App\Http\Controllers\PokemonController;

Route::controller(RegisterController::class)->group(function(): void{
    Route::post('register', 'register');
    Route::post('login', 'login');
    Route::post('logout', 'logout');
});

// Password reset endpoints
Route::post('forgot_password', [ForgotPasswordController::class, 'forgotPassword']);
Route::post('reset_password', [ForgotPasswordController::class, 'resetPassword']);

Route::resource('pokemon', PokemonController::class);
