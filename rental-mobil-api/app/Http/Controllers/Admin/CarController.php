<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Car;
use App\Models\User;
use App\Models\Driver;
use App\Models\Rental; // Import model Rental
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon; // Import Carbon untuk filter laporan waktu

class CarController extends Controller
{
    public function index()
    {
        $cars = Car::withCount('rentals')->get(); // Mengambil armada beserta jumlah total disewa (terlaris)
        $availableCars = Car::where('status', 'tersedia')->get();
        $rentedCars = Car::where('status', 'disewa')->with(['user', 'driver'])->get();
        $users = User::all();
        $drivers = Driver::all();

        // Data untuk Dashboard Monitoring & Laporan
        $activeRentals = Rental::where('status', 'aktif')->with(['car', 'user', 'driver'])->get();
        $weeklyRentals = Rental::where('created_at', '>=', Carbon::now()->subDays(7))->get();
        $topCars = Car::withCount('rentals')->orderBy('rentals_count', 'desc')->take(5)->get();

        return view('admin.cars.index', compact(
            'cars', 
            'availableCars', 
            'rentedCars', 
            'users', 
            'drivers', 
            'activeRentals', 
            'weeklyRentals', 
            'topCars'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'icon' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
            'price' => 'required|numeric',
            'type' => 'required|string',
            'capacity' => 'required|string',
            'transmission' => 'required|string',
            'is_lepas_kunci' => 'required|string',
            'monthly_price' => 'required|string',
            'driver_price' => 'required|numeric',
        ]);

        $data = $request->except(['icon', 'is_lepas_kunci']);
        $data['transmission'] = $request->is_lepas_kunci . ' (' . $request->transmission . ')';

        if ($request->hasFile('icon')) {
            $file = $request->file('icon');
            $filename = time() . '_' . str_replace(' ', '_', strtolower($file->getClientOriginalName()));
            $file->storeAs('cars', $filename, 'public');
            $data['icon'] = $filename;
        }

        Car::create($data);

        return redirect()->back()->with('success', 'Data mobil berhasil ditambahkan dengan nama gambar asli!');
    }

    public function destroy($id)
    {
        $car = Car::findOrFail($id);

        if ($car->icon && Storage::disk('public')->exists('cars/' . $car->icon)) {
            Storage::disk('public')->delete('cars/' . $car->icon);
        }

        $car->delete();

        return redirect()->back()->with('success', 'Data mobil berhasil dihapus!');
    }

    public function bookCar(Request $request, $id)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'rental_type' => 'required|in:lepas_kunci,dengan_supir',
            'driver_id' => 'required_if:rental_type,dengan_supir'
        ]);

        $car = Car::findOrFail($id);

        // Hitung total harga (bisa disesuaikan jika menggunakan tarif per hari atau paket)
        $totalPrice = $request->rental_type == 'dengan_supir' ? $car->driver_price : $car->price;

        // 1. Simpan riwayat transaksi ke tabel rentals (Database Monitoring)
        Rental::create([
            'car_id' => $car->id,
            'user_id' => $request->user_id,
            'driver_id' => $request->rental_type == 'dengan_supir' ? $request->driver_id : null,
            'rental_type' => $request->rental_type,
            'total_price' => $totalPrice,
            'status' => 'aktif'
        ]);

        // 2. Update status mobil
        $car->status = 'disewa';
        $car->user_id = $request->user_id;
        $car->rental_type = $request->rental_type;
        $car->driver_id = $request->rental_type == 'dengan_supir' ? $request->driver_id : null;
        $car->save();

        // 3. Update status supir jika menggunakan supir
        if ($request->rental_type == 'dengan_supir' && $request->driver_id) {
            $driver = Driver::findOrFail($request->driver_id);
            $driver->status = 'bertugas';
            $driver->save();
        }

        return redirect()->back()->with('success', "Mobil {$car->name} berhasil disewa dan tercatat di sistem monitoring.");
    }

    public function returnCar($id) 
    {
        $car = Car::findOrFail($id);
        
        // Update riwayat rental yang aktif menjadi 'selesai'
        Rental::where('car_id', $car->id)->where('status', 'aktif')->update([
            'status' => 'selesai'
        ]);

        // Kembalikan status supir jika ada
        if ($car->driver_id) {
            $driver = Driver::find($car->driver_id);
            if ($driver) {
                $driver->status = 'tersedia';
                $driver->save();
            }
        }

        // Reset status mobil
        $car->status = 'tersedia';
        $car->user_id = null;
        $car->rental_type = null;
        $car->driver_id = null;
        $car->save();

        return redirect()->back()->with('success', 'Mobil telah dikembalikan dan transaksi selesai!');
    }
    public function showKtp($filename)
{
    $path = 'ktp/' . $filename;

    // Cek apakah file benar-benar ada di storage/app/public/ktp/
    if (!Storage::disk('public')->exists($path)) {
        abort(404, 'File KTP tidak ditemukan.');
    }

    $file = Storage::disk('public')->get($path);
    $type = Storage::disk('public')->mimeType($path);

    return response($file, 200)->header('Content-Type', $type);
}
}