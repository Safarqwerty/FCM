<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\ClassRoomController;
use App\Http\Controllers\ClusteringController;

// Halaman awal
Route::get('/', function () {
    return view('welcome');
});

// Halaman dashboard
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Rute profile untuk pengguna
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Grup rute untuk admin
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    // Rute halaman utama admin
    Route::get('/', [StudentController::class, 'dashboard'])->name('dashboard');

    Route::resource('students', StudentController::class);

    Route::get('/forms', [StudentController::class, 'create'])->name('forms');
    // Route::get('/forms', [StudentController::class, 'store'])->name('admin.students.store');

    Route::get('/class', [ClassRoomController::class, 'index'])->name('class');
    Route::post('/class', [ClassRoomController::class, 'store'])->name('class.store');
    Route::get('/class/{classRoom}/edit', [ClassRoomController::class, 'edit'])->name('class.edit');
    Route::put('/class/{classRoom}', [ClassRoomController::class, 'update'])->name('class.update');
    Route::delete('/class/{classRoom}', [ClassRoomController::class, 'destroy'])->name('class.destroy');

    Route::get('/tables', [ClusteringController::class, 'calculate'])->name('tables');

    Route::get('/ui-elements', function () {
        return view('admin.ui-elements');
    })->name('ui-elements');

    // Resource controller untuk user, role, dan permission
    Route::resource('users', UserController::class);
    Route::resource('roles', RoleController::class);
    Route::resource('permissions', PermissionController::class);
});

// Grup rute untuk permission tertentu
Route::middleware(['auth', 'permission:publish articles'])->group(function () {
    // Tambahkan rute dengan permission jika diperlukan
});

// Rute otentikasi
require __DIR__.'/auth.php';
