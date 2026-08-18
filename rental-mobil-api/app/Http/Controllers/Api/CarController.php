<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Car;

class CarController extends Controller
{
    public function index()
    {
        $cars = Car::all();

        // Mengembalikan data mobil dalam bentuk JSON
        return response()->json([
            'success' => true,
            'message' => 'Daftar data mobil rental',
            'data' => $cars
        ], 200);
    }
    
}
