<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - VJ Rental Mobil</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/logo-removebg-preview.png') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<body class="bg-[#07111e] text-white font-sans min-h-screen">

<!-- Top Navbar Admin -->
    <header class="bg-gray-900 border-b border-gray-800 px-8 py-4 flex justify-between items-center sticky top-0 z-50">
        <div class="flex items-center space-x-3">
            <div class="bg-yellow-400 text-black font-black px-3 py-1 rounded text-sm">VJ</div>
            <h1 class="text-lg font-bold tracking-wider text-yellow-400">ADMIN DASHBOARD</h1>
        </div>
        
        <!-- BAGIAN KANAN: DROPDOWN PROFIL ADMIN -->
        <div class="flex items-center space-x-4">
            <div class="relative" x-data="{ openDropdown: false }">
                <!-- Tombol Trigger Profil -->
                <button @click="openDropdown = !openDropdown" @click.away="openDropdown = false" 
                    class="flex items-center space-x-2 bg-slate-800/80 hover:bg-slate-800 border border-gray-700/60 px-3 py-1.5 rounded-xl transition cursor-pointer focus:outline-none">
                    <div class="w-7 h-7 bg-yellow-400 text-black font-bold rounded-full flex items-center justify-center text-xs">A</div>
                    <div class="text-left">
                        <p class="text-xs font-bold text-white leading-none">agri23</p>
                        <p class="text-[10px] text-yellow-400 leading-tight">Superadmin</p>
                    </div>
                    <svg class="w-4 h-4 text-gray-400 ml-1 transition-transform" :class="openDropdown ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>

                <!-- Menu Dropdown -->
                <div x-show="openDropdown" 
                    x-transition:enter="transition ease-out duration-100" 
                    x-transition:enter-start="transform opacity-0 scale-95" 
                    x-transition:enter-end="transform opacity-100 scale-100" 
                    x-transition:leave="transition ease-in duration-75" 
                    x-transition:leave-start="transform opacity-100 scale-100" 
                    x-transition:leave-end="transform opacity-0 scale-95"
                    style="display: none;" 
                    class="absolute right-0 mt-2 w-48 bg-gray-900 border border-gray-800 rounded-xl shadow-2xl py-1 z-50">
                    
                    <div class="px-4 py-2 border-b border-gray-800">
                        <p class="text-[11px] text-gray-400">Login sebagai</p>
                        <p class="text-xs font-bold text-yellow-400 truncate">agri23</p>
                    </div>

                    <form action="{{ route('admin.logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full text-left px-4 py-2.5 text-xs text-red-400 hover:bg-red-500/10 font-bold transition flex items-center space-x-2 cursor-pointer">
                            <span>⏏</span>
                            <span>Logout System</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </header>

    <div class="max-w-7xl mx-auto px-6 py-8" 
         x-data="{ 
             activeTab: localStorage.getItem('admin_active_tab') || 'cars',
             setTab(tab) {
                 this.activeTab = tab;
                 localStorage.setItem('admin_active_tab', tab);
             }
         }">

        <!-- Notifikasi Sukses -->
        @if(session('success'))
            <div class="bg-green-500/20 border border-green-500 text-green-300 p-4 rounded-xl mb-6 shadow text-sm">
                {{ session('success') }}
            </div>
        @endif

        <!-- Statistik Ringkasan (Cards) -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <div class="bg-gray-900 border border-gray-800 p-6 rounded-2xl shadow flex items-center justify-between">
                <div>
                    <p class="text-xs text-gray-400 font-bold uppercase tracking-wider">Total Armada Mobil</p>
                    <h3 class="text-3xl font-black text-yellow-400 mt-1">{{ count($cars) }} <span class="text-xs text-gray-400 font-normal">Unit</span></h3>
                </div>
                <div class="w-12 h-12 bg-yellow-400/10 border border-yellow-400/30 rounded-xl flex items-center justify-center text-yellow-400 text-xl">🚗</div>
            </div>

            <div class="bg-gray-900 border border-gray-800 p-6 rounded-2xl shadow flex items-center justify-between">
                <div>
                    <p class="text-xs text-gray-400 font-bold uppercase tracking-wider">Total User Client Terdaftar</p>
                    <h3 class="text-3xl font-black text-blue-400 mt-1">{{ isset($users) ? count($users) : 0 }} <span class="text-xs text-gray-400 font-normal">Akun</span></h3>
                </div>
                <div class="w-12 h-12 bg-blue-500/10 border border-blue-500/30 rounded-xl flex items-center justify-center text-blue-400 text-xl">👥</div>
            </div>
        </div>

        <!-- Tab Navigasi Menu -->
        <div class="flex space-x-2 border-b border-gray-800 mb-6 overflow-x-auto">
            <button @click="setTab('cars')" :class="activeTab === 'cars' ? 'border-yellow-400 text-yellow-400 bg-yellow-400/10' : 'border-transparent text-gray-400 hover:text-white'" class="px-5 py-3 font-bold text-xs uppercase tracking-wider border-b-2 transition rounded-t-lg whitespace-nowrap cursor-pointer">
                Kelola Armada Mobil
            </button>
            <button @click="setTab('users')" :class="activeTab === 'users' ? 'border-yellow-400 text-yellow-400 bg-yellow-400/10' : 'border-transparent text-gray-400 hover:text-white'" class="px-5 py-3 font-bold text-xs uppercase tracking-wider border-b-2 transition rounded-t-lg whitespace-nowrap cursor-pointer">
                Daftar Client / User React (<span x-text="{{ isset($users) ? count($users) : 0 }}"></span>)
            </button>
            <button @click="setTab('drivers')" :class="activeTab === 'drivers' ? 'border-yellow-400 text-yellow-400 bg-yellow-400/10' : 'border-transparent text-gray-400 hover:text-white'" class="px-5 py-3 font-bold text-xs uppercase tracking-wider border-b-2 transition rounded-t-lg whitespace-nowrap cursor-pointer">
                Kelola Supir (<span x-text="{{ isset($drivers) ? count($drivers) : 0 }}"></span>)
            </button>
            <button @click="setTab('reports')" :class="activeTab === 'reports' ? 'border-yellow-400 text-yellow-400 bg-yellow-400/10' : 'border-transparent text-gray-400 hover:text-white'" class="px-5 py-3 font-bold text-xs uppercase tracking-wider border-b-2 transition rounded-t-lg whitespace-nowrap cursor-pointer">
                Dashboard Monitoring & Laporan (<span x-text="{{ isset($activeRentals) ? $activeRentals->count() : 0 }}"></span>)
            </button>
        </div>

        <!-- KONTEN TAB: KELOLA ARMADA MOBIL -->
        <div x-show="activeTab === 'cars'" style="display: none;">
            @include('admin.cars.cars')
        </div>

        <!-- KONTEN TAB: DAFTAR CLIENT / USER -->
        <div x-show="activeTab === 'users'" style="display: none;">
            @include('admin.cars.users')
        </div>

        <!-- KONTEN TAB: KELOLA & INPUT SUPIR -->
        <div x-show="activeTab === 'drivers'" style="display: none;">
            @include('admin.cars.drivers')
        </div>

        <!-- KONTEN TAB: DASHBOARD MONITORING & LAPORAN -->
        <div x-show="activeTab === 'reports'" style="display: none;">
            @include('admin.cars.reports')
        </div>

    </div>

</body>
<!-- Script JavaScript untuk memformat titik ribuan secara otomatis -->
<script>
function formatRupiah(input, hiddenId) {
    // Ambil hanya angka saja
    let angka = input.value.replace(/[^,\d]/g, '').toString();
    
    // Format dengan pemisah titik
    let split = angka.split(',');
    let sisa = split[0].length % 3;
    let rupiah = split[0].substr(0, sisa);
    let ribuan = split[0].substr(sisa).match(/\d{3}/gi);

    if (ribuan) {
        let separator = sisa ? '.' : '';
        rupiah += separator + ribuan.join('.');
    }

    rupiah = split[1] !== undefined ? rupiah + ',' + split[1] : rupiah;
    
    // Tampilkan format beritik ke input teks yang dilihat user
    input.value = rupiah;
    
    // Masukkan angka murni (tanpa titik) ke input hidden agar aman saat disimpan ke database Laravel
    document.getElementById(hiddenId).value = angka;
}
</script>
</html>