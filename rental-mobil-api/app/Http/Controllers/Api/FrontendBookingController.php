<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Driver;
use App\Models\Car;
use App\Models\Rental;

class FrontendBookingController extends Controller
{
    // Ambil semua daftar supir untuk ditampilkan di website React
    public function getDrivers()
    {
        $drivers = Driver::all(); // Mengambil semua data tanpa filter 'tersedia'
        return response()->json([
            'success' => true,
            'data' => $drivers
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'car_id' => 'required|exists:cars,id',
            'rental_type' => 'required|in:lepas_kunci,dengan_supir',
            'duration_type' => 'required|string',
            'days_count' => 'required|integer|min:1',
            'payment_method' => 'required|string',
            'ktp_photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'payment_proof' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // Validasi foto bukti pembayaran
            'driver_id' => 'nullable|exists:drivers,id',
        ]);

        // Upload Foto KTP
        $ktpPath = null;
        if ($request->hasFile('ktp_photo')) {
            $file = $request->file('ktp_photo');
            $filename = time() . '_' . preg_replace('/\s+/', '_', $file->getClientOriginalName());
            $file->storeAs('ktp', $filename, 'public');
            $ktpPath = $filename;
        }

        // Upload Foto Bukti Pembayaran
        $proofPath = null;
        if ($request->hasFile('payment_proof')) {
            $proofFile = $request->file('payment_proof');
            $proofFilename = time() . '_proof_' . preg_replace('/\s+/', '_', $proofFile->getClientOriginalName());
            $proofFile->storeAs('payment_proofs', $proofFilename, 'public');
            $proofPath = $proofFilename;
        }

        $car = Car::findOrFail($request->car_id);

        // Hitung total harga sesuai durasi dan tipe layanan di backend agar akurat
        $pricePerDay = $car->price;
        $driverPricePerDay = $car->driver_price ?? 0;

        $totalDays = 1;
        if ($request->duration_type === 'daily') {
            $totalDays = max(1, (int) $request->days_count);
        } elseif ($request->duration_type === 'weekly') {
            $totalDays = 7;
        } elseif ($request->duration_type === 'monthly') {
            $totalDays = 30;
        }

        $carTotal = $pricePerDay * $totalDays;
        $driverTotal = $request->rental_type === 'dengan_supir' ? ($driverPricePerDay * $totalDays) : 0;
        $grandTotal = $carTotal + $driverTotal;

        // Update status mobil
        $car->update([
            'status' => 'disewa',
            'user_id' => $request->user_id,
            'rental_type' => $request->rental_type,
            'driver_id' => $request->rental_type === 'dengan_supir' ? $request->driver_id : null,
        ]);

        if ($request->rental_type === 'dengan_supir' && $request->filled('driver_id')) {
            Driver::where('id', $request->driver_id)->update(['status' => 'bertugas']);
        }

        // Simpan ke tabel rentals dengan data lengkap termasuk bukti pembayaran
        Rental::create([
            'car_id' => $car->id,
            'user_id' => $request->user_id,
            'driver_id' => $request->rental_type === 'dengan_supir' ? $request->driver_id : null,
            'rental_type' => $request->rental_type,
            'duration_type' => $request->duration_type,
            'days_count' => $totalDays,
            'payment_method' => $request->payment_method,
            'ktp' => $ktpPath,
            'payment_proof' => $proofPath, // Menyimpan nama file bukti pembayaran ke database
            'total_price' => $grandTotal,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Booking dan bukti pembayaran berhasil disimpan!'
        ]);
    }
}