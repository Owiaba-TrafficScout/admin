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



Route::middleware(['auth', 'verified', 'admin'])->group(function () {
    //select tenant
    Route::get('/select-tenant', [TenantController::class, 'selectTenant'])->name('tenant.select');
    Route::post('/select-tenant', [TenantController::class, 'storeSelectedTenant'])->name('tenant.selected.store');
});

Route::middleware(['auth', 'verified', 'admin'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    //Profile routes
    Route::group(['prefix' => '/profile'], function () {
        Route::get('/', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/', [ProfileController::class, 'destroy'])->name('profile.destroy');
    });

    // Trips routes
    Route::group(['prefix' => '/trips'], function () {
        Route::get('/', [TripController::class, 'index'])->name('trips.index');
        Route::patch('/{trip}', [TripController::class, 'update'])->name('trips.update');
        Route::delete('/{trip}', [TripController::class, 'destroy'])->name('trips.destroy');
    });

    //Projects routes
    Route::group(['prefix' => '/projects'], function () {

        Route::get('/', [ProjectController::class, 'index'])->name('projects.index');
        Route::patch('/{project}', [ProjectController::class, 'update'])->name('projects.update');
        Route::delete('/{project}', [ProjectController::class, 'destroy'])->name('projects.destroy');
        Route::post('/{project}/{user}/remove', [ProjectController::class, 'removeUser'])->name('projects.users.remove');
        Route::get('/create', [ProjectController::class, 'create'])->name('projects.create');
        Route::post('/store', [ProjectController::class, 'store'])->name('projects.store');
        Route::get('/{project}/users/create', [ProjectController::class, 'addUsers'])->name('project.users.create');
        Route::post('/{project}/users/store', [ProjectController::class, 'storeUsers'])->name('project.users.store');
        Route::post('/{project}/{projectUser}/users/roles/update', [ProjectController::class, 'updateUserRole'])->name('projects.users.update');
    });

    //Cartype routes
    Route::group(['prefix' => '/car-types'], function () {
        Route::get('/', [CarTypeController::class, 'index'])->name('car-types.index');
        Route::post('/', [CarTypeController::class, 'store'])->name('car-types.store');
        Route::patch('/{car_type}', [CarTypeController::class, 'update'])->name('car-types.update');
        Route::delete('/{car_type}', [CarTypeController::class, 'destroy'])->name('car-types.destroy');
    });
    Route::post('/project/{project}/cartype/add', [CarTypeController::class, 'addCarType'])->name('project.cartype.add');
    Route::get('/project/{project}/cartype/add', [CarTypeController::class, 'addCarTypePage'])->name('project.cartype.add.page');

    //Users routes
    Route::group(['prefix' => '/users'], function () {
        Route::get('/', [UserController::class, 'index'])->name('users.index');
        Route::patch('/{user}', [UserController::class, 'update'])->name('users.update');
        Route::delete('/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    });
    //payments routes
    Route::group(['prefix' => '/payments'], function () {
        Route::get('/', [PaymentController::class, 'index'])->name('payments.index');
        Route::patch('/{payment}', [PaymentController::class, 'update'])->name('payments.update');
        Route::delete('/{payment}', [PaymentController::class, 'destroy'])->name('payments.destroy');
    });

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
