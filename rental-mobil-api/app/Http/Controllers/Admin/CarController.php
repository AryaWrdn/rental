<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Car;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage; // Import Storage untuk hapus file

class CarController extends Controller
{
    public function index()
    {
        $cars = Car::latest()->get();
        return view('admin.cars.index', compact('cars'));
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
}