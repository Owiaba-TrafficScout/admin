<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\CarController;
use App\Http\Controllers\API\CarTypeController;
use App\Http\Controllers\API\StateController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register'])->name('api.register');
Route::post('/login', [AuthController::class, 'login']);
Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('/user', [AuthController::class, 'user']);
    Route::post('/logout', [AuthController::class, 'logout']);

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
        Route::get('/', [\App\Http\Controllers\API\ProjectController::class, 'index'])->name('api.projects.index');
    });
    Route::group(['prefix' => 'states'], function () {
        Route::patch('/update', [StateController::class, 'update'])->name('api.states.update');
    });
});
