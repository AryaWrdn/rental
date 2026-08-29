<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\CarController;
use App\Models\Car;
use App\Models\User;
use App\Http\Controllers\Admin\DriverController;
use App\Http\Controllers\AdminAuthController;

// --- ROUTE AUTHENTICATION ADMIN (PUBLIK) ---
Route::get('/admin/login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
Route::post('/admin/login', [AdminAuthController::class, 'login'])->name('admin.login.submit');
Route::post('/admin/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

// Redirect awal halaman utama website
Route::redirect('/', '/admin/cars');

// --- ROUTE ADMIN (DIPROTEKSI MIDDLEWARE 'admin.auth') ---
Route::middleware(['admin.auth'])->prefix('admin')->name('admin.')->group(function () {
    
    // Halaman Utama / Dashboard / Kelola Mobil
    Route::get('/cars', [CarController::class, 'index'])->name('cars.index');
    Route::post('/cars', [CarController::class, 'store'])->name('cars.store');
    Route::delete('/cars/{id}', [CarController::class, 'destroy'])->name('cars.destroy');

    // Fitur Pendukung Admin Lainnya
    Route::get('/rentals/export-excel', [CarController::class, 'exportExcel'])->name('rentals.export');
    Route::get('/ktp-view/{filename}', [CarController::class, 'showKtp'])->name('ktp.view');
    
    // Rute Driver (Tambah, Update, Hapus)
    Route::post('/drivers/store', [DriverController::class, 'store'])->name('drivers.store');
    Route::put('/drivers/{id}', [DriverController::class, 'update'])->name('drivers.update');
    Route::delete('/drivers/{id}', [DriverController::class, 'destroy'])->name('drivers.destroy');

    Route::post('/cars/{id}/book', [CarController::class, 'bookCar'])->name('cars.book');
    Route::post('/cars/{id}/return', [CarController::class, 'returnCar'])->name('cars.return');

    Route::get('/dashboard', function () {
        $cars = Car::all();
        $users = User::all();

        return view('admin.cars.index', compact('cars', 'users'));
    })->name('dashboard');

});