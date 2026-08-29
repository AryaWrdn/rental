<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users', // Diubah ke username
            'password' => 'required|string|min:6',
        ]);

        $user = User::create([
            'name'     => $request->name,
            'username' => $request->username, // Diubah ke username
            'password' => Hash::make($request->password),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Registrasi berhasil',
            'user'    => $user
        ], 201);
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string', // Diubah ke username
            'password' => 'required',
        ]);

        $user = User::where('username', $request->username)->first(); // Cari berdasarkan username

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Username atau password salah' // Pesan disesuaikan
            ], 401);
        }

        return response()->json([
            'success' => true,
            'message' => 'Login berhasil',
            'user'    => $user
        ], 200);
    }
    
}