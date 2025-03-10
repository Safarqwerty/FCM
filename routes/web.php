<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\ClassRoomController;
use App\Http\Controllers\ClusteringController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Public routes
Route::get('/', function () {
    return view('welcome');
});

// Authentication routes
require __DIR__.'/auth.php';

// Authenticated user routes
Route::middleware(['auth', 'verified'])->group(function () {
    // Admin routes
    Route::middleware('role:admin')
        ->prefix('admin')
        ->name('admin.')
        ->group(function () {
            // Admin dashboard
            Route::get('/dashboard', [StudentController::class, 'dashboard'])->name('dashboard');

            // Student management
            Route::resource('students', StudentController::class);
            Route::get('/forms', [StudentController::class, 'create'])->name('forms');

            // Classroom management
            Route::controller(ClassRoomController::class)->group(function () {
                Route::get('/class', 'index')->name('class');
                Route::post('/class', 'store')->name('class.store');
                Route::get('/class/{classRoom}/edit', 'edit')->name('class.edit');
                Route::put('/class/{classRoom}', 'update')->name('class.update');
                Route::delete('/class/{classRoom}', 'destroy')->name('class.destroy');
            });

            // Clustering functionality
            Route::controller(ClusteringController::class)->group(function () {
                Route::get('/tables', 'index')->name('tables');
                Route::post('/clustering/calculate', 'calculate')->name('clustering.calculate');
            });

            // Profile management
            Route::controller(ProfileController::class)->group(function () {
                Route::get('/profile', 'edit')->name('profile.edit');
                Route::patch('/profile', 'update')->name('profile.update');
                Route::delete('/profile', 'destroy')->name('profile.destroy');
            });

            // User, role, and permission management
            Route::resource('users', UserController::class);
            Route::resource('roles', RoleController::class);
            Route::resource('permissions', PermissionController::class);
        });
});
