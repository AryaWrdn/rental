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
        <div class="flex items-center space-x-4">
            <span class="text-xs text-gray-400">Logged in as <strong class="text-white">Superadmin</strong></span>
            <a href="http://localhost:5173/" target="_blank" class="bg-gray-800 hover:bg-gray-700 text-xs px-3 py-2 rounded border border-gray-700 transition">Lihat Website ↗</a>
        </div>
    </header>

    <div class="max-w-7xl mx-auto px-6 py-8" x-data="{ activeTab: 'cars' }">

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
                <div class="w-12 h-12 bg-yellow-400/10 border border-yellow-400/30 rounded-xl flex items-center justify-center text-yellow-400 text-xl">
                    🚗
                </div>
            </div>

            <div class="bg-gray-900 border border-gray-800 p-6 rounded-2xl shadow flex items-center justify-between">
                <div>
                    <p class="text-xs text-gray-400 font-bold uppercase tracking-wider">Total User Client Terdaftar</p>
                    <h3 class="text-3xl font-black text-blue-400 mt-1">{{ isset($users) ? count($users) : 0 }} <span class="text-xs text-gray-400 font-normal">Akun</span></h3>
                </div>
                <div class="w-12 h-12 bg-blue-500/10 border border-blue-500/30 rounded-xl flex items-center justify-center text-blue-400 text-xl">
                    👥
                </div>
            </div>
        </div>

        <!-- Tab Navigasi Menu -->
        <div class="flex space-x-2 border-b border-gray-800 mb-6">
            <button 
                @click="activeTab = 'cars'" 
                :class="activeTab === 'cars' ? 'border-yellow-400 text-yellow-400 bg-yellow-400/10' : 'border-transparent text-gray-400 hover:text-white'"
                class="px-5 py-3 font-bold text-xs uppercase tracking-wider border-b-2 transition rounded-t-lg">
                Kelola Armada Mobil
            </button>
            <button 
                @click="activeTab = 'users'" 
                :class="activeTab === 'users' ? 'border-yellow-400 text-yellow-400 bg-yellow-400/10' : 'border-transparent text-gray-400 hover:text-white'"
                class="px-5 py-3 font-bold text-xs uppercase tracking-wider border-b-2 transition rounded-t-lg">
                Daftar Client / User React (<span x-text="{{ isset($users) ? count($users) : 0 }}"></span>)
            </button>
            <!-- Tambahkan tombol tab navigasi ini di samping tab mobil & user -->
<button 
    @click="activeTab = 'drivers'" 
    :class="activeTab === 'drivers' ? 'border-yellow-400 text-yellow-400 bg-yellow-400/10' : 'border-transparent text-gray-400 hover:text-white'"
    class="px-5 py-3 font-bold text-xs uppercase tracking-wider border-b-2 transition rounded-t-lg">
    Kelola Supir (<span x-text="{{ isset($drivers) ? count($drivers) : 0 }}"></span>)
</button>

        </div>
        <!-- KONTEN TAB: KELOLA & INPUT SUPIR -->
<div x-show="activeTab === 'drivers'" class="grid grid-cols-1 lg:grid-cols-3 gap-8" style="display: none;">
    
    <!-- Form Input Supir Baru (Kiri) -->
    <div class="bg-gray-900 border border-gray-800 p-6 rounded-2xl shadow-md lg:col-span-1 h-fit">
        <h2 class="text-lg font-bold text-white mb-4 border-b border-gray-800 pb-2">Tambah Supir Baru</h2>

        <form action="{{ route('admin.drivers.store') }}" method="POST" class="space-y-4 text-xs">
            @csrf
            <div>
                <label class="block font-semibold text-gray-300 mb-1">Nama Lengkap Supir</label>
                <input type="text" name="name" placeholder="Contoh: Budi Santoso" class="w-full bg-slate-900 border border-gray-700 p-2.5 rounded-lg text-white" required>
            </div>
            <div>
                <label class="block font-semibold text-gray-300 mb-1">No. WhatsApp / HP</label>
                <input type="text" name="phone" placeholder="Contoh: 081234567890" class="w-full bg-slate-900 border border-gray-700 p-2.5 rounded-lg text-white" required>
            </div>
            <div>
                <label class="block font-semibold text-gray-300 mb-1">Pengalaman Kerja</label>
                <input type="text" name="experience" placeholder="Contoh: 3 Tahun Pengalaman" class="w-full bg-slate-900 border border-gray-700 p-2.5 rounded-lg text-white" required>
            </div>
            <div>
                <label class="block font-semibold text-gray-300 mb-1">Status</label>
                <select name="status" class="w-full border border-gray-700 text-gray-300 p-2.5 rounded-lg bg-slate-900" required>
                    <option value="tersedia">Tersedia</option>
                    <option value="bertugas">Sedang Bertugas</option>
                </select>
            </div>
            <button type="submit" class="w-full bg-yellow-400 hover:bg-white text-black font-extrabold py-3 rounded-lg transition cursor-pointer uppercase">Simpan Supir</button>
        </form>
    </div>

    <!-- Tabel Daftar Supir (Kanan) -->
    <div class="bg-gray-900 border border-gray-800 p-6 rounded-2xl shadow-md lg:col-span-2 h-fit">
        <h2 class="text-lg font-bold text-white mb-4 border-b border-gray-800 pb-2">Daftar Supir di Database</h2>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-slate-900 text-gray-400 uppercase border-b border-gray-800">
                        <th class="p-3">Nama Supir</th>
                        <th class="p-3">No. HP / WA</th>
                        <th class="p-3">Pengalaman</th>
                        <th class="p-3">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-800 text-gray-200">
                    @forelse($drivers ?? [] as $driver)
                        <tr>
                            <td class="p-3 font-bold text-white">{{ $driver->name }}</td>
                            <td class="p-3 text-yellow-400">{{ $driver->phone }}</td>
                            <td class="p-3 text-gray-300">{{ $driver->experience }}</td>
                            <td class="p-3">
                                <span class="px-2 py-1 rounded text-[10px] font-bold {{ $driver->status == 'tersedia' ? 'bg-green-500/20 text-green-400 border border-green-500/30' : 'bg-red-500/20 text-red-400 border border-red-500/30' }}">
                                    {{ strtoupper($driver->status) }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="p-8 text-center text-gray-500">Belum ada data supir yang diinput.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- KONTEN TAB 1: KELOLA ARMADA MOBIL -->
<div x-show="activeTab === 'cars'" class="grid grid-cols-1 lg:grid-cols-3 gap-8" x-data="{ 
    tableFilter: 'semua', 
    modalBookOpen: false, 
    modalReturnOpen: false, 
    activeCarId: null, 
    activeCarName: '',
    rentalType: 'lepas_kunci', // Tambahan state untuk radio button (lepas_kunci / dengan_supir)
    openBookModal(id, name) {
        this.activeCarId = id;
        this.activeCarName = name;
        this.rentalType = 'lepas_kunci'; // Reset ke lepas kunci setiap modal dibuka
        this.modalBookOpen = true;
    },
    openReturnModal(id, name) {
        this.activeCarId = id;
        this.activeCarName = name;
        this.modalReturnOpen = true;
    }
}">

    <!-- FORM INPUT (KIRI) -->
    <div class="bg-gray-900 border border-gray-800 p-6 rounded-2xl shadow-md lg:col-span-1 h-fit">
        <h2 class="text-lg font-bold text-white mb-4 border-b border-gray-800 pb-2">Tambah Mobil Baru</h2>

        <form action="{{ route('admin.cars.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4 text-xs">
            @csrf
            <div>
                <label class="block font-semibold text-gray-300 mb-1">Nama Mobil</label>
                <input type="text" name="name" placeholder="Contoh: Honda Brio" class="w-full bg-slate-900 border border-gray-700 p-2.5 rounded-lg text-white" required>
            </div>
            <div>
                <label class="block font-semibold text-gray-300 mb-1">Upload Gambar</label>
                <input type="file" name="icon" accept="image/*" class="w-full border border-gray-700 p-2 rounded-lg bg-slate-900 text-gray-300" required>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block font-semibold text-gray-300 mb-1">Tipe</label>
                    <select name="type" class="w-full border border-gray-700 text-gray-300 p-2.5 rounded-lg bg-slate-900" required>
                        <option value="">-- Pilih --</option>
                        <option value="CITY CAR">CITY CAR</option>
                        <option value="SUV CAR">SUV CAR</option>
                        <option value="MPV">MPV</option>
                        <option value="SEDAN">SEDAN</option>
                    </select>
                </div>
                <div>
                    <label class="block font-semibold text-gray-300 mb-1">Kapasitas</label>
                    <select name="capacity" class="w-full border border-gray-700 text-gray-300 p-2.5 rounded-lg bg-slate-900" required>
                        <option value="">-- Pilih --</option>
                        <option value="2x Penumpang">2 Orang</option>
                        <option value="4x Penumpang">4 Orang</option>
                        <option value="5x Penumpang">5 Orang</option>
                        <option value="6-7x Penumpang">6-7 Orang</option>
                    </select>
                </div>
            </div>
            <div>
                <label class="block font-semibold text-gray-300 mb-1">Transmisi</label>
                <select name="transmission" class="w-full border border-gray-700 text-gray-300 p-2.5 rounded-lg bg-slate-900" required>
                    <option value="Manual">Manual</option>
                    <option value="Matic">Matic</option>
                </select>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block font-semibold text-gray-300 mb-1">Harga/Hari</label>
                    <input type="number" name="price" placeholder="350000" class="w-full bg-slate-900 border border-gray-700 p-2.5 rounded-lg text-white" required>
                </div>
                <div>
                    <label class="block font-semibold text-gray-300 mb-1">Harga+Driver</label>
                    <input type="number" name="driver_price" placeholder="550000" class="w-full bg-slate-900 border border-gray-700 p-2.5 rounded-lg text-white" required>
                </div>
            </div>
            <div>
                <label class="block font-semibold text-gray-300 mb-1">Sewa Bulanan</label>
                <input type="text" name="monthly_price" placeholder="6 JUTA/Bulan" class="w-full bg-slate-900 border border-gray-700 p-2.5 rounded-lg text-white" required>
            </div>
            <button type="submit" class="w-full bg-yellow-400 hover:bg-white text-black font-extrabold py-3 rounded-lg transition cursor-pointer uppercase">Simpan Mobil</button>
        </form>
    </div>
    

    <!-- TABEL UTAMA DENGAN TOMBOL FILTER (KANAN) -->
    <div class="bg-gray-900 border border-gray-800 p-6 rounded-2xl shadow-md lg:col-span-2 h-fit">
        
        <!-- Header Tabel & Tombol Filter (Seperti di coretan gambar) -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 pb-4 border-b border-gray-800 gap-4">
            <h2 class="text-lg font-bold text-white">Daftar Armada di Database</h2>
            
            <!-- Tombol Filter Kategori -->
            <div class="flex items-center space-x-2 bg-slate-900 p-1 rounded-xl border border-gray-800">
                <button 
                    @click="tableFilter = 'semua'" 
                    :class="tableFilter === 'semua' ? 'bg-yellow-400 text-black font-extrabold' : 'text-gray-400 hover:text-white'"
                    class="px-3 py-1.5 rounded-lg text-[10px] uppercase transition cursor-pointer">
                    Semua
                </button>
                <button 
                    @click="tableFilter = 'tersedia'" 
                    :class="tableFilter === 'tersedia' ? 'bg-green-400 text-black font-extrabold' : 'text-gray-400 hover:text-white'"
                    class="px-3 py-1.5 rounded-lg text-[10px] uppercase transition cursor-pointer">
                    Tersedia
                </button>
                <button 
                    @click="tableFilter = 'disewa'" 
                    :class="tableFilter === 'disewa' ? 'bg-red-400 text-black font-extrabold' : 'text-gray-400 hover:text-white'"
                    class="px-3 py-1.5 rounded-lg text-[10px] uppercase transition cursor-pointer">
                    Disewa
                </button>
            </div>
        </div>

        <!-- Tabel Data -->
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-slate-900 text-gray-400 uppercase border-b border-gray-800">
                        <th class="p-3">Mobil</th>
                        <th class="p-3">Tipe</th>
                        <th class="p-3">Status / Penyewa</th>
                        <th class="p-3 text-center">Aksi / Kelola</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-800 text-gray-200">
                    @foreach($cars as $car)
                        <tr x-show="tableFilter === 'semua' || (tableFilter === 'tersedia' && '{{ $car->status }}' === 'tersedia') || (tableFilter === 'disewa' && '{{ $car->status }}' === 'disewa')">
                            
                            <!-- Kolom Mobil -->
                            <td class="p-3 font-semibold flex items-center space-x-3">
                                @if($car->icon)
                                    <img src="{{ asset('storage/cars/' . $car->icon) }}" class="w-12 h-8 object-contain rounded border border-gray-700 bg-slate-900">
                                @else
                                    <span>🚗</span>
                                @endif
                                <div>
                                    <p class="font-bold text-white">{{ $car->name }}</p>
                                    <p class="text-[10px] text-yellow-400">IDR {{ number_format($car->price, 0, ',', '.') }}/hari</p>
                                </div>
                            </td>

                            <!-- Kolom Tipe -->
                            <td class="p-3 text-gray-300">{{ $car->type }}</td>

                            <!-- Kolom Status -->
                            <!-- Kolom Status -->
<td class="p-3">
    @if($car->status == 'tersedia')
        <span class="px-2 py-1 rounded text-[10px] font-bold bg-green-500/20 text-green-400 border border-green-500/30">TERSEDIA</span>
    @else
        <div class="space-y-1">
            <span class="px-2 py-1 rounded text-[10px] font-bold bg-red-500/20 text-red-400 border border-red-500/30">DISEWA</span>
            <p class="text-[10px] text-white">Oleh: {{ $car->user->name ?? 'User' }}</p>
            
            <!-- Tampilkan info sewa -->
            @if($car->rental_type == 'dengan_supir')
                <p class="text-[10px] text-yellow-400">
                    Sewa: 🚗 + Supir ({{ $car->driver->name ?? 'N/A' }})
                </p>
            @else
                <p class="text-[10px] text-gray-400">Sewa: 🚗 Lepas Kunci</p>
            @endif
        </div>
    @endif
</td>

                            <!-- Kolom Aksi Dinamis -->
                            <td class="p-3 text-center space-x-2">
                                @if($car->status == 'tersedia')
                                    <button @click="openBookModal({{ $car->id }}, '{{ $car->name }}')" class="bg-yellow-400 text-black hover:bg-white px-2.5 py-1.5 rounded font-extrabold text-[10px] transition cursor-pointer shadow">
                                        BOOKING
                                    </button>
                                @else
                                    <button @click="openReturnModal({{ $car->id }}, '{{ $car->name }}')" class="bg-blue-600 hover:bg-blue-500 text-white px-2.5 py-1.5 rounded font-bold text-[10px] transition cursor-pointer shadow">
                                        KEMBALI
                                    </button>
                                @endif

                                <form action="{{ route('admin.cars.destroy', $car->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Hapus mobil ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="bg-red-500/20 text-red-400 hover:bg-red-500 hover:text-white px-2 py-1.5 rounded text-[10px] font-bold transition">Hapus</button>
                                </form>
                            </td>

                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

<!-- MODAL 1: INPUT USER & OPSI SUPIR UNTUK BOOKING -->
    <div x-show="modalBookOpen" class="fixed inset-0 z-50 flex items-center justify-center bg-black/80 backdrop-blur-sm p-4" style="display: none;">
        <div class="bg-gray-900 border border-gray-800 w-full max-w-md rounded-2xl p-6 text-white shadow-2xl">
            <h3 class="text-base font-bold text-yellow-400 mb-2 uppercase">Form Booking Unit: <span x-text="activeCarName"></span></h3>
            <p class="text-xs text-gray-400 mb-4">Pilih client dan tentukan jenis layanan sewa:</p>

            <form :action="'/admin/cars/' + activeCarId + '/book'" method="POST" class="space-y-4 text-xs">
                @csrf
                
                <!-- Pilih User Client -->
                <div>
                    <label class="block font-semibold text-gray-300 mb-1">Pilih Client</label>
                    <select name="user_id" class="w-full bg-slate-900 border border-gray-700 p-2.5 rounded-lg text-white" required>
                        <option value="">-- Pilih User Client --</option>
                        @foreach($users as $usr)
                            <option value="{{ $usr->id }}">{{ $usr->name }} ({{ $usr->email }})</option>
                        @endforeach
                    </select>
                </div>

                <!-- Radio Button Opsi Layanan: Lepas Kunci / Tidak Lepas Kunci -->
                <div>
                    <label class="block font-semibold text-gray-300 mb-2">Jenis Layanan</label>
                    <div class="grid grid-cols-2 gap-3">
                        <label @click="rentalType = 'lepas_kunci'" :class="rentalType === 'lepas_kunci' ? 'border-yellow-400 bg-yellow-400/10 text-yellow-400' : 'border-gray-700 text-gray-400 bg-slate-900'" class="flex items-center space-x-2 border p-2.5 rounded-lg cursor-pointer transition">
                            <input type="radio" name="rental_type" value="lepas_kunci" x-model="rentalType" class="text-yellow-400 focus:ring-0">
                            <span class="font-bold">Lepas Kunci</span>
                        </label>

                        <label @click="rentalType = 'dengan_supir'" :class="rentalType === 'dengan_supir' ? 'border-yellow-400 bg-yellow-400/10 text-yellow-400' : 'border-gray-700 text-gray-400 bg-slate-900'" class="flex items-center space-x-2 border p-2.5 rounded-lg cursor-pointer transition">
                            <input type="radio" name="rental_type" value="dengan_supir" x-model="rentalType" class="text-yellow-400 focus:ring-0">
                            <span class="font-bold">Dengan Supir</span>
                        </label>
                    </div>
                </div>

                <!-- Dropdown Pilih Supir (Hanya tampil jika "Dengan Supir" dipilih) -->
                <div x-show="rentalType === 'dengan_supir'" x-transition style="display: none;">
                    <label class="block font-semibold text-gray-300 mb-1">Pilih Supir dari Database</label>
                    <select name="driver_id" class="w-full bg-slate-900 border border-gray-700 p-2.5 rounded-lg text-white">
                        <option value="">-- Pilih Supir --</option>
                        @if(isset($drivers))
                            @foreach($drivers as $driver)
                                <option value="{{ $driver->id }}">{{ $driver->name }} ({{ $driver->experience }} - {{ ucfirst($driver->status) }})</option>
                            @endforeach
                        @endif
                    </select>
                </div>

                <!-- Tombol Aksi -->
                <div class="flex space-x-3 pt-2">
                    <button type="submit" class="flex-1 bg-yellow-400 text-black font-extrabold py-2.5 rounded-lg hover:bg-white transition cursor-pointer">PROSES SEWA</button>
                    <button type="button" @click="modalBookOpen = false" class="bg-gray-800 text-gray-300 px-4 py-2.5 rounded-lg hover:bg-gray-700 transition">Batal</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL 2: KONFIRMASI PENGEMBALIAN MOBIL -->
    <div x-show="modalReturnOpen" class="fixed inset-0 z-50 flex items-center justify-center bg-black/80 backdrop-blur-sm p-4" style="display: none;">
        <div class="bg-gray-900 border border-gray-800 w-full max-w-md rounded-2xl p-6 text-white shadow-2xl text-center">
            <div class="w-12 h-12 bg-blue-500/20 text-blue-400 border border-blue-500/30 rounded-full flex items-center justify-center mx-auto mb-3 text-xl">❓</div>
            <h3 class="text-base font-bold text-white mb-2">Konfirmasi Pengembalian Unit</h3>
            <p class="text-xs text-gray-300 mb-6">Apakah user telah benar-benar mengembalikan mobil <strong class="text-yellow-400" x-text="activeCarName"></strong>?</p>

            <form :action="'/admin/cars/' + activeCarId + '/return'" method="POST" class="flex space-x-3 justify-center">
                @csrf
                <button type="submit" class="bg-green-500 hover:bg-green-600 text-black font-extrabold px-6 py-2.5 rounded-lg transition text-xs cursor-pointer">YA, KEMBALIKAN</button>
                <button type="button" @click="modalReturnOpen = false" class="bg-red-500/20 text-red-400 border border-red-500/40 hover:bg-red-500 hover:text-white px-6 py-2.5 rounded-lg transition text-xs font-bold">TIDAK</button>
            </form>
        </div>
    </div>

</div>

        <!-- KONTEN TAB 2: DAFTAR USER CLIENT (REACT) -->
        <div x-show="activeTab === 'users'" class="bg-gray-900 border border-gray-800 p-6 rounded-2xl shadow-md">
            <h2 class="text-lg font-bold text-white mb-2">Daftar Akun Client / Pengguna Website</h2>
            <p class="text-xs text-gray-400 mb-6">Berikut adalah daftar user yang telah melakukan registrasi akun melalui form pop-up di React client.</p>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="bg-slate-900 text-gray-400 uppercase border-b border-gray-800">
                            <th class="p-3">#ID</th>
                            <th class="p-3">Nama Lengkap</th>
                            <th class="p-3">Email Address</th>
                            <th class="p-3">Tanggal Bergabung</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-800 text-gray-200">
                        @forelse($users as $index => $usr)
                            <tr>
                                <td class="p-3 font-mono text-gray-500">#{{ $usr->id }}</td>
                                <td class="p-3 font-bold flex items-center space-x-2">
                                    <div class="w-6 h-6 rounded-full bg-blue-500/20 text-blue-400 flex items-center justify-center font-bold text-[10px] uppercase">
                                        {{ substr($usr->name, 0, 2) }}
                                    </div>
                                    <span>{{ $usr->name }}</span>
                                </td>
                                <td class="p-3 text-yellow-400">{{ $usr->email }}</td>
                                <td class="p-3 text-gray-400">{{ $usr->created_at ? $usr->created_at->format('d M Y, H:i') : '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="p-8 text-center text-gray-500">Belum ada user client yang mendaftar.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>

</body>

</html>