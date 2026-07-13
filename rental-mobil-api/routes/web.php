<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\CarController;

Route::redirect('/', '/admin/cars');
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/cars', [CarController::class, 'index'])->name('cars.index');
    Route::post('/cars', [CarController::class, 'store'])->name('cars.store');
    Route::delete('/cars/{id}', [CarController::class, 'destroy'])->name('cars.destroy');
});
