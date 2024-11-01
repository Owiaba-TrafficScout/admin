<?php

use App\Http\Controllers\CarTypeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InvitationController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TenantController;
use App\Http\Controllers\TripController;
use App\Http\Controllers\UserController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/payment/{success}', function ($success) {
    return Inertia::render('Welcome', ['success' => $success]);
})->name('payment.success');

Route::get('/paystack/callback', [PaymentController::class, 'handleGatewayCallback'])->name('paystack');

Route::get('/', function () {
    return Inertia::render('Auth/Login');
});

Route::get('/invite/accept/{token}', [InvitationController::class, 'accept'])->name('invite.accept');
Route::post('invitation/register', [InvitationController::class, 'register'])->name('invite.register');



Route::middleware(['auth', 'verified'])->group(function () {
    //select tenant
    Route::get('/select-tenant', [TenantController::class, 'selectTenant'])->name('tenant.select');
    Route::post('/select-tenant', [TenantController::class, 'storeSelectedTenant'])->name('tenant.selected.store');
});

Route::middleware(['auth', 'verified', 'admin'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

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
    Route::post('/projects/{project}/{user}/remove', [ProjectController::class, 'removeUser'])->name('projects.users.remove');
    Route::get('/projects/create', [ProjectController::class, 'create'])->name('projects.create');
    Route::post('/projects/store', [ProjectController::class, 'store'])->name('projects.store');
    Route::get('/projects/{project}/users/create', [ProjectController::class, 'addUsers'])->name('project.users.create');
    Route::post('/projects/{project}/users/store', [ProjectController::class, 'storeUsers'])->name('project.users.store');
    Route::post('/projects/{project}/{projectUser}/users/roles/update', [ProjectController::class, 'updateUserRole'])->name('projects.users.update');

    //Cartype routes
    Route::get('/car-types', [CarTypeController::class, 'index'])->name('car-types.index');
    Route::post('/car-types', [CarTypeController::class, 'store'])->name('car-types.store');
    Route::patch('/car-types/{car_type}', [CarTypeController::class, 'update'])->name('car-types.update');
    Route::delete('/car-types/{car_type}', [CarTypeController::class, 'destroy'])->name('car-types.destroy');
    Route::post('/project/{project}/cartype/add', [CarTypeController::class, 'addCarType'])->name('project.cartype.add');
    Route::get('/project/{project}/cartype/add', [CarTypeController::class, 'addCarTypePage'])->name('project.cartype.add.page');

    //Users routes
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::patch('/users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');

    //payments routes
    Route::get('/payments', [PaymentController::class, 'index'])->name('payments.index');
    Route::patch('/payments/{payment}', [PaymentController::class, 'update'])->name('payments.update');
    Route::delete('/payments/{payment}', [PaymentController::class, 'destroy'])->name('payments.destroy');

    //Paystack payemnt routes

    //Export to excel
    //---Trips-------//
    Route::get('/export-trips', [TripController::class, 'exportTripsToExcel'])->name('export.trips');
    Route::get('/export-trip/{tripId}', [TripController::class, 'exportTripToExcel'])->name('export.trip');

    // select project
    Route::post('/select-project', [ProjectController::class, 'storeSelectedProject'])->name('project.selected.store');


    //---Invitations---//
    Route::post('/projects/{project}/invitations/send', [InvitationController::class, 'sendInvite'])->name('projects.invite');
});

require __DIR__ . '/auth.php';
