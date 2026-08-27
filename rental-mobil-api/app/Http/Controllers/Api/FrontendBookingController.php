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

    // Proses pemesanan dari website React user
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'car_id' => 'required|exists:cars,id',
            'rental_type' => 'required|in:lepas_kunci,dengan_supir',
            'ktp_photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'driver_id' => 'nullable|exists:drivers,id',
        ]);

        $ktpPath = null;
        if ($request->hasFile('ktp_photo')) {
            $file = $request->file('ktp_photo');
            $filename = time() . '_' . preg_replace('/\s+/', '_', $file->getClientOriginalName());
            
            // PERBAIKAN: Simpan tepat ke disk public pada folder ktp (storage/app/public/ktp)
            $file->storeAs('ktp', $filename, 'public');
            $ktpPath = $filename; // Simpan nama filenya saja ke database
        }

        $car = Car::findOrFail($request->car_id);

        // Update status mobil menjadi disewa
        $car->update([
            'status' => 'disewa',
            'user_id' => $request->user_id,
            'rental_type' => $request->rental_type,
            'driver_id' => $request->rental_type === 'dengan_supir' ? $request->driver_id : null,
        ]);

        // Jika pakai supir, ubah status supir jadi bertugas
        if ($request->rental_type === 'dengan_supir' && $request->filled('driver_id')) {
            Driver::where('id', $request->driver_id)->update(['status' => 'bertugas']);
        }

        // Simpan ke tabel transaksi/rental
        Rental::create([
            'car_id' => $car->id,
            'user_id' => $request->user_id,
            'driver_id' => $request->rental_type === 'dengan_supir' ? $request->driver_id : null,
            'rental_type' => $request->rental_type,
            'ktp_photo' => $ktpPath, // Masuk ke database!
            'total_price' => $request->rental_type === 'dengan_supir' ? ($car->price + $car->driver_price) : $car->price,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Booking berhasil disimpan!'
        ]);
    }
}