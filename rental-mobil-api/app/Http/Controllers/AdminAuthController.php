<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminAuthController extends Controller
{
    public function showLoginForm()
    {
        return view('admin.cars.login'); 
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        // Cek kredensial hardcode sesuai permintaan
        if ($request->username === 'agri23' && $request->password === '12345678') {
            session(['admin_logged_in' => true, 'admin_username' => 'agri23']);
            return redirect()->route('admin.cars.index');
        }

        return back()->withErrors(['username' => 'Username atau password salah!'])->withInput();
    }

    public function logout()
    {
        session()->forget(['admin_logged_in', 'admin_username']);
        return redirect()->route('admin.login');
    }
}