<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Driver;
use Illuminate\Http\Request;

class DriverController extends Controller {
    public function store(Request $request) {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'experience' => 'required|string',
            'status' => 'required|string',
        ]);

        Driver::create($request->all());

        return redirect()->back()->with('success', 'Data supir berhasil ditambahkan ke database!');
    }
}
