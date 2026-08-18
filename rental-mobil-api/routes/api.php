<?php

use Illuminate\Http\Request;
use App\Models\Car;
use App\Http\Controllers\Api\CarController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
Route::get('/carss', function () {
    return response()->json(Car::latest()->get());
});
Route::get('/cars', [CarController::class, 'index']);
