<div class="grid grid-cols-1 lg:grid-cols-3 gap-8" x-data="{ 
    tableFilter: 'semua', 
    modalBookOpen: false, 
    modalReturnOpen: false, 
    activeCarId: null, 
    activeCarName: '',
    rentalType: 'lepas_kunci',
    openBookModal(id, name) {
        this.activeCarId = id;
        this.activeCarName = name;
        this.rentalType = 'lepas_kunci';
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
        @if ($errors->any())
            <div class="bg-red-500/20 border border-red-500 text-red-300 p-3 rounded-lg mb-4 text-[10px]">
                <ul class="list-disc pl-4">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
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
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-2.5 flex items-center text-gray-400 text-xs font-bold">Rp</span>
                        <input type="text" id="display_price" placeholder="350.000" class="w-full bg-slate-900 border border-gray-700 p-2.5 pl-8 rounded-lg text-white" oninput="formatRupiah(this, 'price_hidden')" required>
                        <input type="hidden" name="price" id="price_hidden">
                    </div>
                </div>
                <div>
                    <label class="block font-semibold text-gray-300 mb-1">Harga+Driver</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-2.5 flex items-center text-gray-400 text-xs font-bold">Rp</span>
                        <input type="text" id="display_driver_price" placeholder="550.000" class="w-full bg-slate-900 border border-gray-700 p-2.5 pl-8 rounded-lg text-white" oninput="formatRupiah(this, 'driver_price_hidden')" required>
                        <input type="hidden" name="driver_price" id="driver_price_hidden">
                    </div>
                </div>
            </div>
            <div>
                <label class="block font-semibold text-gray-300 mb-1">Sewa Bulanan</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-2.5 flex items-center text-gray-400 text-xs font-bold">Rp</span>
                    <input type="text" id="display_monthly_price" placeholder="6.000.000" class="w-full bg-slate-900 border border-gray-700 p-2.5 pl-8 rounded-lg text-white" oninput="formatRupiah(this, 'monthly_price_hidden')" required>
                    <input type="hidden" name="monthly_price" id="monthly_price_hidden">
                </div>
            </div>
            <div>
                <label class="block font-semibold text-gray-300 mb-1">Opsi Lepas Kunci</label>
                <select name="is_lepas_kunci" class="w-full border border-gray-700 text-gray-300 p-2.5 rounded-lg bg-slate-900" required>
                    <option value="">-- Pilih Opsi --</option>
                    <option value="Lepas Kunci">Lepas Kunci</option>
                    <option value="Bisa dengan Supir">Bisa dengan Supir</option>
                </select>
            </div>
            <button type="submit" class="w-full bg-yellow-400 hover:bg-white text-black font-extrabold py-3 rounded-lg transition cursor-pointer uppercase">Simpan Mobil</button>
        </form>
    </div>
    
    <!-- TABEL UTAMA DENGAN TOMBOL FILTER (KANAN) -->
    <div class="bg-gray-900 border border-gray-800 p-6 rounded-2xl shadow-md lg:col-span-2 h-fit">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 pb-4 border-b border-gray-800 gap-4">
            <h2 class="text-lg font-bold text-white">Daftar Armada di Database</h2>
            
            <div class="flex items-center space-x-2 bg-slate-900 p-1 rounded-xl border border-gray-800">
                <button @click="tableFilter = 'semua'" :class="tableFilter === 'semua' ? 'bg-yellow-400 text-black font-extrabold' : 'text-gray-400 hover:text-white'" class="px-3 py-1.5 rounded-lg text-[10px] uppercase transition cursor-pointer">Semua</button>
                <button @click="tableFilter = 'tersedia'" :class="tableFilter === 'tersedia' ? 'bg-green-400 text-black font-extrabold' : 'text-gray-400 hover:text-white'" class="px-3 py-1.5 rounded-lg text-[10px] uppercase transition cursor-pointer">Tersedia</button>
                <button @click="tableFilter = 'disewa'" :class="tableFilter === 'disewa' ? 'bg-red-400 text-black font-extrabold' : 'text-gray-400 hover:text-white'" class="px-3 py-1.5 rounded-lg text-[10px] uppercase transition cursor-pointer">Disewa</button>
            </div>
        </div>

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
                            <td class="p-3 text-gray-300">{{ $car->type }}</td>
                            <td class="p-3">
                                @if($car->status == 'tersedia')
                                    <span class="px-2 py-1 rounded text-[10px] font-bold bg-green-500/20 text-green-400 border border-green-500/30">TERSEDIA</span>
                                @else
                                    <div class="space-y-1">
                                        <span class="px-2 py-1 rounded text-[10px] font-bold bg-red-500/20 text-red-400 border border-red-500/30">DISEWA</span>
                                        <p class="text-[10px] text-white">Oleh: {{ $car->user->name ?? 'User' }}</p>
                                        @if($car->rental_type == 'dengan_supir')
    <p class="text-[10px] text-yellow-400">Sewa: 🚗 + Supir ({{ $car->driver->name ?? $car->rentals->where('status', 'aktif')->first()->driver->name ?? 'N/A' }})</p>
@else
                                            <p class="text-[10px] text-gray-400">Sewa: 🚗 Lepas Kunci</p>
                                        @endif
                                    </div>
                                @endif
                            </td>
                            <td class="p-3 text-center space-x-2">
                                @if($car->status == 'tersedia')
                                    <button @click="openBookModal({{ $car->id }}, '{{ $car->name }}')" class="bg-yellow-400 text-black hover:bg-white px-2.5 py-1.5 rounded font-extrabold text-[10px] transition cursor-pointer shadow">BOOKING</button>
                                @else
                                    <button @click="openReturnModal({{ $car->id }}, '{{ $car->name }}')" class="bg-blue-600 hover:bg-blue-500 text-white px-2.5 py-1.5 rounded font-bold text-[10px] transition cursor-pointer shadow">KEMBALI</button>
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

    <!-- MODAL 1: BOOKING -->
    <div x-show="modalBookOpen" class="fixed inset-0 z-50 flex items-center justify-center bg-black/80 backdrop-blur-sm p-4" style="display: none;">
        <div class="bg-gray-900 border border-gray-800 w-full max-w-md rounded-2xl p-6 text-white shadow-2xl">
            <h3 class="text-base font-bold text-yellow-400 mb-2 uppercase">Form Booking Unit: <span x-text="activeCarName"></span></h3>
            <p class="text-xs text-gray-400 mb-4">Pilih client dan tentukan jenis layanan sewa:</p>

            <form :action="'/admin/cars/' + activeCarId + '/book'" method="POST" class="space-y-4 text-xs">
                @csrf
                <div>
                    <label class="block font-semibold text-gray-300 mb-1">Pilih Client</label>
                    <select name="user_id" class="w-full bg-slate-900 border border-gray-700 p-2.5 rounded-lg text-white" required>
                        <option value="">-- Pilih User Client --</option>
                        @foreach($users as $usr)
                            <option value="{{ $usr->id }}">{{ $usr->name }} ({{ $usr->email }})</option>
                        @endforeach
                    </select>
                </div>

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

                <div x-show="rentalType === 'dengan_supir'" x-transition style="display: none;">
                    <label class="block font-semibold text-gray-300 mb-1">Pilih Supir yang Tersedia</label>
                    <select name="driver_id" class="w-full bg-slate-900 border border-gray-700 p-2.5 rounded-lg text-white">
                        <option value="">-- Pilih Supir --</option>
                        @if(isset($drivers))
                            @foreach($drivers->where('status', 'tersedia') as $driver)
                                <option value="{{ $driver->id }}">{{ $driver->name }} ({{ $driver->experience }})</option>
                            @endforeach
                        @endif
                    </select>
                </div>

                <div class="flex space-x-3 pt-2">
                    <button type="submit" class="flex-1 bg-yellow-400 text-black font-extrabold py-2.5 rounded-lg hover:bg-white transition cursor-pointer">PROSES SEWA</button>
                    <button type="button" @click="modalBookOpen = false" class="bg-gray-800 text-gray-300 px-4 py-2.5 rounded-lg hover:bg-gray-700 transition">Batal</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL 2: PENGEMBALIAN -->
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