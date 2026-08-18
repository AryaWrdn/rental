<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Car;
use App\Models\User;
use App\Models\Driver;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage; // Import Storage untuk hapus file

class CarController extends Controller
{
   public function index()
{
    $cars = Car::all(); // Keseluruhan Armada
    $availableCars = Car::where('status', 'tersedia')->get(); // Armada Tersedia
    $rentedCars = Car::where('status', 'disewa')->with('user')->get(); // Armada Sedang Disewa
    $users = User::all(); // Data Client untuk pilihan modal booking
    $drivers = Driver::all();

    return view('admin.cars.index', compact('cars', 'availableCars', 'rentedCars', 'users','drivers'));
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

            // Pake nama asli bawaan gambar kamu (e.g., 1780631073_bf.jpg) agar tidak berubah jadi nama mobil
            $filename = time() . '_' . str_replace(' ', '_', strtolower($file->getClientOriginalName()));

            // PASTIKAN disimpan ke folder 'cars' di dalam public storage
            $file->storeAs('cars', $filename, 'public');

            $data['icon'] = $filename;
        }

        Car::create($data);

        return redirect()->back()->with('success', 'Data mobil berhasil ditambahkan dengan nama gambar asli!');
    }
    public function destroy($id)
    {
        $car = Car::findOrFail($id);

        // Hapus file dari folder cars yang benar
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
    $car->status = 'disewa';
    $car->user_id = $request->user_id;
    $car->rental_type = $request->rental_type; // Pastikan kolom ini ada di tabel cars
    $car->driver_id = $request->driver_id;     // Pastikan kolom ini ada di tabel cars
    $car->save();

    // UPDATE STATUS SUPIR
    if ($request->rental_type == 'dengan_supir' && $request->driver_id) {
        $driver = \App\Models\Driver::findOrFail($request->driver_id);
        $driver->status = 'bertugas';
        $driver->save();
    }

    return redirect()->back()->with('success', "Mobil {$car->name} berhasil disewa oleh client.");
}

// Proses Mobil Dikembalikan (Kembali ke Tersedia)
public function returnCar($id) {
    $car = Car::findOrFail($id);
    
    // Kembalikan status supir jika ada
    if ($car->driver_id) {
        $driver = \App\Models\Driver::find($car->driver_id);
        if ($driver) {
            $driver->status = 'tersedia';
            $driver->save();
        }
    }

    $car->status = 'tersedia';
    $car->user_id = null;
    $car->rental_type = null;
    $car->driver_id = null;
    $car->save();

    return redirect()->back()->with('success', 'Mobil telah dikembalikan!');
}
}