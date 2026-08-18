<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\CarController;
use App\Models\Car;
use App\Models\User;
use App\Http\Controllers\Admin\DriverController;

Route::post('/admin/drivers/store', [DriverController::class, 'store'])->name('admin.drivers.store');
Route::post('/admin/cars/{id}/book', [App\Http\Controllers\Admin\CarController::class, 'bookCar'])->name('admin.cars.book');
Route::post('/admin/cars/{id}/return', [App\Http\Controllers\Admin\CarController::class, 'returnCar'])->name('admin.cars.return');
Route::get('/admin/dashboard', function () {
    $cars = Car::all();
    $users = User::all(); // <-- Pastikan $users didefinisikan di sini

    return view('admin.cars.index', compact('cars', 'users')); // <-- Kirim ke view
})->name('admin.dashboard');
Route::redirect('/', '/admin/cars');
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/cars', [CarController::class, 'index'])->name('cars.index');
    Route::post('/cars', [CarController::class, 'store'])->name('cars.store');
    Route::delete('/cars/{id}', [CarController::class, 'destroy'])->name('cars.destroy');
});
