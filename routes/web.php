<?php

use App\Http\Controllers\CarTypeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LicenseController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TripController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
    ]);
});

//get all Licences
Route::get('/licenses', [LicenseController::class, 'index'])->name('licenses.index');


// Route::get('/dashboard', function () {
//     return Inertia::render('Dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');
Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Trips routes
    Route::get('/trips', [TripController::class, 'index'])->name('trips.index');
    Route::patch('/trips/{trip}', [TripController::class, 'update'])->name('trips.update');
    Route::delete('/trips/{trip}', [TripController::class, 'destroy'])->name('trips.destroy');

    //Projects routes
    Route::get('/projects', [ProjectController::class, 'index'])->name('projects.index');
    Route::patch('/projects/{project}', [ProjectController::class, 'update'])->name('projects.update');
    Route::delete('/projects/{project}', [ProjectController::class, 'destroy'])->name('projects.destroy');

    //Cartype routes
    Route::get('/car-types', [CarTypeController::class, 'index'])->name('car-types.index');
    Route::patch('/car-types/{car_type}', [CarTypeController::class, 'update'])->name('car-types.update');
    Route::delete('/car-types/{car_type}', [CarTypeController::class, 'destroy'])->name('car-types.destroy');

    //payments routes
    Route::get('/payments', [PaymentController::class, 'index'])->name('payments.index');
    Route::patch('/payments/{payment}', [PaymentController::class, 'update'])->name('payments.update');
    Route::delete('/payments/{payment}', [PaymentController::class, 'destroy'])->name('payments.destroy');

    //Paystack payemnt routes
});
Route::get('/pay', [PaymentController::class, 'pay'])->name('pay');
Route::get('/paystack/callback', [PaymentController::class, 'handleGatewayCallback'])->name('paystack');

require __DIR__ . '/auth.php';
