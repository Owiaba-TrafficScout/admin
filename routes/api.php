<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\CarController;
use App\Http\Controllers\API\CarTypeController;
use App\Http\Controllers\API\GeneralController;
use App\Http\Controllers\API\ProjectController;
use App\Http\Controllers\API\StateController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;

Route::group(['middleware' => ['auth:sanctum']], function () {
    // Request a new verification link
    Route::post('/email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
        ->middleware('throttle:6,1')
        ->name('api.verification.send');

    // Handle the verification link
    Route::get('/verify-email/{id}/{hash}', VerifyEmailController::class)
        ->middleware(['signed', 'throttle:6,1'])
        ->name('api.verification.verify');
});

Route::post('/register', [AuthController::class, 'register'])->name('api.register');
Route::post('/login', [AuthController::class, 'login'])->name('api.login');

// Forgot Password (API)
Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])
    ->name('api.password.email');

// Reset Password (API)
Route::post('/reset-password', [NewPasswordController::class, 'store'])
    ->name('api.password.update');


Route::group(['middleware' => ['auth:sanctum', 'verified']], function () {
    Route::get('/user', [AuthController::class, 'user']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/change-password', [AuthController::class, 'changePassword'])->name('api.change-password');

    Route::group(['prefix' => 'cars'], function () {
        Route::post('/', [CarController::class, 'store'])->name('api.cars.store');
        Route::get('/', [CarController::class, 'index'])->name('api.cars.index');
        Route::get('/{car}', [CarController::class, 'show'])->name('api.cars.show');
        Route::patch('/{car}', [CarController::class, 'update'])->name('api.cars.update');
        Route::delete('/{car}', [CarController::class, 'destroy'])->name('api.cars.destroy');
    });
    Route::group(['prefix' => 'car-types'], function () {
        Route::get('/', [CarTypeController::class, 'index'])->name('api.car-types.index');
        Route::post('/', [CarTypeController::class, 'store'])->name('api.car-types.store');
        Route::get('/{carType}', [CarTypeController::class, 'show'])->name('api.car-types.show');
        Route::patch('/{carType}', [CarTypeController::class, 'update'])->name('api.car-types.update');
        Route::delete('/{carType}', [CarTypeController::class, 'destroy'])->name('api.car-types.destroy');
    });

    Route::group(['prefix' => 'projects'], function () {
        Route::get('/', [ProjectController::class, 'index'])->name('api.projects.index');
        Route::post('/join', [ProjectController::class, 'joinProject'])->name('api.project.join');
    });
    Route::group(['prefix' => 'states'], function () {
        Route::patch('/update', [StateController::class, 'update'])->name('api.states.update');
    });
    Route::group(['prefix' => 'general'], function () {
        Route::post('/upload-data', [GeneralController::class, 'uploadData'])->name('api.general.upload-data');
    });
});
