<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Input Rental Mobil</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/logo-removebg-preview.png') }}">
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-black text-white font-sans min-h-screen p-6">

    <div class="max-w-6xl mx-auto">
        <h1 class="text-3xl font-bold text-white mb-6">Admin Panel - Kelola vj rental mobil</h1>

        <!-- Notifikasi Sukses -->
        @if(session('success'))
            <div class="bg-green-500 text-white p-4 rounded-lg mb-6 shadow">
                {{ session('success') }}
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            <!-- FORM INPUT (KIRI) -->
            <div class="bg-gray-800 p-6 rounded-xl shadow-md lg:col-span-1">
                <h2 class="text-xl font-bold text-white mb-4 border-b pb-2">Tambah Mobil Baru</h2>

                <form action="{{ route('admin.cars.store') }}" method="POST" enctype="multipart/form-data"
                    class="space-y-4">
                    @csrf

                    <div>
                        <label class="block text-sm font-semibold text-white mb-1">Nama Mobil</label>
                        <input type="text" name="name" placeholder="Contoh: Honda Brio"
                            class="w-full bg-gray-800 border p-2 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
                            required>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-white mb-1">Upload Gambar Mobil</label>
                        <input type="file" name="icon" accept="image/*"
                            class="w-full border p-1.5 rounded bg-gray-800 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                            required>
                        <p class="text-xs text-gray-400 mt-1">*Format: JPG, PNG, WEBP (Max 2MB)</p>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-white mb-1">Tipe Mobil</label>
                            <select name="type"
                                class="w-full border text-gray-400 p-2 rounded bg-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                required>
                                <option value="">-- Pilih Tipe --</option>
                                <option value="CITY CAR">CITY CAR</option>
                                <option value="SUV CAR">SUV CAR</option>
                                <option value="MPV">MPV</option>
                                <option value="SEDAN">SEDAN</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-white mb-1">Kapasitas Penumpang</label>
                            <select name="capacity"
                                class="w-full border text-gray-400 p-2 rounded bg-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                required>
                                <option value="">-- Kapasitas --</option>
                                <option value="2x Penumpang">2 Orang</option>
                                <option value="4x Penumpang">4 Orang</option>
                                <option value="5x Penumpang">5 Orang</option>
                                <option value="6-7x Penumpang">6-7 Orang</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-white mb-1">Transmisi</label>
                        <select name="transmission"
                            class="w-full border text-gray-400 p-2 rounded bg-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-500"
                            required>
                            <option value="">-- Pilih Transmisi --</option>
                            <option value="Manual">Manual</option>
                            <option value="Matic">Matic</option>
                            <option value="Manual/Matic">Manual & Matic</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-white mb-2">Metode Sewa</label>
                        <div class="flex items-center space-x-6 bg-gray-800 p-2.5 border rounded">
                            <label
                                class=" bg-gray-800 flex items-center space-x-2 cursor-pointer text-sm text-gray-400 font-medium">
                                <input type="radio" name="is_lepas_kunci" value="LEPAS KUNCI"
                                    class="w-4 h-4 text-blue-600 focus:ring-blue-500" checked>
                                <span>Bisa Lepas Kunci</span>
                            </label>
                            <label
                                class=" bg-gray-800 flex items-center space-x-2 cursor-pointer text-sm text-gray-400 font-medium">
                                <input type="radio" name="is_lepas_kunci" value="TANPA LEPAS KUNCI"
                                    class="w-4 h-4 text-blue-600  focus:ring-blue-500">
                                <span>Tidak Bisa Lepas Kunci</span>
                            </label>
                        </div>
                    </div>

                    <div class="bg-gray-800 grid grid-cols-2 gap-4">
                        <div>
                            <label class=" block text-sm font-semibold text-white mb-1">Harga / Hari </label>
                            <input type="number" name="price" placeholder="Contoh: 350000"
                                class=" bg-gray-800 w-full border p-2 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
                                required>
                        </div>

                        <div class="bg-gray-800">
                            <label class="  block text-sm font-semibold text-white mb-1">Harga + Driver /
                                Hari
                            </label>
                            <input type="number" name="driver_price" placeholder="Contoh: 550000"
                                class="bg-gray-800 w-full border p-2 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
                                required>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-white mb-1">Sewa Bulanan (Teks)</label>
                        <input type="text" name="monthly_price" placeholder="Contoh: 6 JUTA/Bulan"
                            class=" bg-gray-800 w-full border p-2 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
                            required>
                    </div>

                    <button type="submit"
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 px-4 rounded transition duration-200 shadow">
                        Simpan Data Mobil
                    </button>
                </form>
            </div>

            <!-- TABEL DAFTAR MOBIL (KANAN) -->
            <div class="bg-gray-800 p-6 rounded-xl shadow-md lg:col-span-2 overflow-x-auto">
                <h2 class="text-xl font-bold text-white mb-4 border-b pb-2">Daftar Armada di Database</h2>

                <table class="w-full text-left border-collapse text-sm">
                    <thead>
                        <tr class="bg-gray-800 text-white uppercase text-xs border-b">
                            <th class="p-3">Mobil</th>
                            <th class="p-3">Tipe</th>
                            <th class="p-3">Harga/Hari</th>
                            <th class="p-3">Bulanan</th>
                            <th class="p-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y text-white">
                        @forelse($cars as $car)
                            <tr>
                                <td class="p-3 font-semibold flex items-center space-x-3">
                                    @if($car->icon)
                                        <img src="{{ asset('storage/cars/' . $car->icon) }}" alt="{{ $car->name }}"
                                            class="w-16 h-10 object-contain rounded border bg-gray-50">
                                    @else
                                        <span class="text-xl">🚗</span>
                                    @endif
                                    <span>{{ $car->name }}</span>
                                </td>
                                <td class="p-3">{{ $car->type }}</td>
                                <td class="p-3 text-green-600 font-bold">IDR {{ number_format($car->price, 0, ',', '.') }}
                                </td>
                                <td class="p-3">{{ $car->monthly_price }}</td>
                                <td class="p-3 text-center">
                                    <form action="{{ route('admin.cars.destroy', $car->id) }}" method="POST"
                                        onsubmit="return confirm('Yakin mau menghapus mobil ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="bg-red-500 hover:bg-red-600 text-white text-xs px-3 py-1 rounded transition">
                                            Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="p-4 text-center text-gray-400">Belum ada data mobil. Silakan input di
                                    form sebelah kiri!</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>

</body>

</html>