<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\RegisterController;
use App\Http\Controllers\API\ForgotPasswordController;
use App\Http\Controllers\PokemonCardController;
use App\Http\Controllers\API\UserController;

Route::controller(RegisterController::class)->group(function(): void{
    Route::post('register', 'register');
    Route::post('login', 'login');
    Route::post('logout', 'logout');
});

// Password reset endpoints
Route::post('forgot_password', [ForgotPasswordController::class, 'forgotPassword']);
Route::post('reset_password', [ForgotPasswordController::class, 'resetPassword']);

// User management routes (protected by auth:sanctum)
Route::middleware('auth:sanctum')->group(function () {
    Route::controller(UserController::class)->group(function(){
        Route::get('user', 'getUser');
        Route::post('user/upload_avatar', 'uploadAvatar');
        Route::delete('user/remove_avatar','removeAvatar');
        Route::post('user/send_verification_email','sendVerificationEmail');
        Route::post('user/change_email', 'changeEmail');
    });
});

Route::resource('pokemon-cards', PokemonCardController::class);