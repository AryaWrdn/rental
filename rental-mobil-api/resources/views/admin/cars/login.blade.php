<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - VJ Rental Mobil</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/logo-removebg-preview.png') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-[#07111e] text-white font-sans min-h-screen flex items-center justify-center p-4">

    <div class="bg-gray-900 border border-gray-800 p-8 rounded-2xl shadow-2xl w-full max-w-md space-y-6">
        
        <!-- Logo & Header -->
        <div class="text-center space-y-2">
            <div class="inline-flex items-center justify-center bg-yellow-400 text-black font-black px-4 py-1.5 rounded-xl text-lg shadow-md">
                VJ
            </div>
            <h1 class="text-xl font-bold tracking-wider text-yellow-400">ADMIN DASHBOARD</h1>
            <p class="text-xs text-gray-400">Silakan login untuk mengelola sistem VJ Rental Mobil</p>
        </div>

        <!-- Notifikasi Error Login -->
        @if($errors->any())
            <div class="bg-red-500/20 border border-red-500/50 text-red-300 p-3 rounded-xl text-xs flex items-center space-x-2">
                <span>⚠️</span>
                <span>{{ $errors->first() }}</span>
            </div>
        @endif

        <!-- Form Login dengan Alpine.js untuk Toggle Password -->
        <form action="{{ route('admin.login.submit') }}" method="POST" class="space-y-4">
            @csrf
            
            <div>
                <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-1.5">Username</label>
                <input type="text" name="username" value="{{ old('username') }}" placeholder="Masukkan username admin" required 
                    class="w-full bg-slate-900 border border-gray-800 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-yellow-400 transition placeholder:text-gray-600">
            </div>

            <!-- Input Password dengan Toggle Ikon Mata -->
            <div x-data="{ showPassword: false }">
                <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-1.5">Password</label>
                <div class="relative">
                    <input :type="showPassword ? 'text' : 'password'" name="password" placeholder="Masukkan password" required 
                        class="w-full bg-slate-900 border border-gray-800 rounded-xl px-4 py-3 pr-12 text-sm text-white focus:outline-none focus:border-yellow-400 transition placeholder:text-gray-600">
                    
                    <button type="button" @click="showPassword = !showPassword" 
                        class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-gray-400 hover:text-white transition cursor-pointer">
                        <!-- Ikon Mata Terbuka (Saat password terlihat) -->
                        <svg x-show="showPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                        <!-- Ikon Mata Tertutup/Dicoret (Saat password disembunyikan) -->
                        <svg x-show="!showPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a10.035 10.035 0 013.161-4.82m3.67-2.022A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.542 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21M3 3l18 18"></path>
                        </svg>
                    </button>
                </div>
            </div>

            <button type="submit" class="w-full bg-yellow-400 hover:bg-yellow-500 text-black font-extrabold py-3 rounded-xl transition text-sm cursor-pointer shadow-lg shadow-yellow-400/10">
                Masuk Dashboard
            </button>
        </form>

        <div class="text-center pt-2 border-t border-gray-800/80">
            <p class="text-[11px] text-gray-500">VJ Rental Mobil &copy; {{ date('Y') }} — All rights reserved.</p>
        </div>

    </div>

</body>
</html>