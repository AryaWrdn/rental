<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Driver;
use Illuminate\Http\Request;

class DriverController extends Controller
{
    // Menyimpan data supir baru
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:255',
            'experience' => 'required|string|max:255',
            'status' => 'required|in:tersedia,bertugas',
        ]);

        Driver::create($request->all());

        return redirect()->back()->with('success', 'Supir baru berhasil ditambahkan.');
    }

    // Memperbarui data supir
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:255',
            'experience' => 'required|string|max:255',
            'status' => 'required|in:tersedia,bertugas',
        ]);

        $driver = Driver::findOrFail($id);
        $driver->update($request->all());

        return redirect()->back()->with('success', 'Data supir berhasil diperbarui.');
    }

    // Menghapus data supir
    public function destroy($id)
    {
        $driver = Driver::findOrFail($id);
        $driver->delete();

        return redirect()->back()->with('success', 'Supir berhasil dihapus.');
    }
}