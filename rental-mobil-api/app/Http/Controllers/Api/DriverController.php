<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Driver;
use Illuminate\Http\Request;

class DriverController extends Controller
{
    public function index()
    {
        $drivers = Driver::all(); // Atau Driver::where('status', 'tersedia')->get();

        return response()->json([
            'success' => true,
            'data' => $drivers
        ]);
    }
}