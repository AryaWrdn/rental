<div class="grid grid-cols-1 lg:grid-cols-3 gap-8" x-data="{ 
    modalEditOpen: false,
    activeDriverId: null,
    activeName: '',
    activePhone: '',
    activeExperience: '',
    activeStatus: 'tersedia',
    openEditModal(driver) {
        this.activeDriverId = driver.id;
        this.activeName = driver.name;
        this.activePhone = driver.phone;
        this.activeExperience = driver.experience;
        this.activeStatus = driver.status;
        this.modalEditOpen = true;
    }
}">
    <!-- FORM TAMBAH SUPIR (KIRI) -->
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

    <!-- TABEL DAFTAR SUPIR (KANAN) -->
    <div class="bg-gray-900 border border-gray-800 p-6 rounded-2xl shadow-md lg:col-span-2 h-fit">
        <h2 class="text-lg font-bold text-white mb-4 border-b border-gray-800 pb-2">Daftar Supir di Database</h2>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-slate-900 text-gray-400 uppercase border-b border-gray-800">
                        <th class="p-3">Nama Supir</th>
                        <th class="p-3">No. HP / WA</th>
                        <th class="p-3">Pengalaman</th>
                        <th class="p-3">Status / Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-800 text-gray-200">
                    @forelse($drivers ?? [] as $driver)
                        <tr>
                            <td class="p-3 font-bold text-white">{{ $driver->name }}</td>
                            <td class="p-3 text-yellow-400">{{ $driver->phone }}</td>
                            <td class="p-3 text-gray-300">{{ $driver->experience }}</td>
                            <td class="p-3 flex items-center space-x-3">
                                <span class="px-2 py-1 rounded text-[10px] font-bold {{ $driver->status == 'tersedia' ? 'bg-green-500/20 text-green-400 border border-green-500/30' : 'bg-red-500/20 text-red-400 border border-red-500/30' }}">
                                    {{ strtoupper($driver->status) }}
                                </span>
                                <!-- Tombol Edit -->
                                <button @click="openEditModal({{ json_encode($driver) }})" class="bg-blue-600/20 text-blue-400 hover:bg-blue-600 hover:text-white border border-blue-500/30 px-2.5 py-1 rounded text-[10px] font-bold transition cursor-pointer">
                                    Edit
                                </button>
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

    <!-- MODAL EDIT SUPIR -->
    <div x-show="modalEditOpen" class="fixed inset-0 z-50 flex items-center justify-center bg-black/80 backdrop-blur-sm p-4" style="display: none;">
        <div class="bg-gray-900 border border-gray-800 w-full max-w-md rounded-2xl p-6 text-white shadow-2xl">
            <h3 class="text-base font-bold text-yellow-400 mb-4 border-b border-gray-800 pb-2">Edit Data Supir</h3>

            <!-- Form Update -->
            <form :action="'/admin/drivers/' + activeDriverId" method="POST" class="space-y-4 text-xs">
                @csrf
                @method('PUT')
                
                <div>
                    <label class="block font-semibold text-gray-300 mb-1">Nama Lengkap Supir</label>
                    <input type="text" name="name" x-model="activeName" class="w-full bg-slate-900 border border-gray-700 p-2.5 rounded-lg text-white" required>
                </div>
                <div>
                    <label class="block font-semibold text-gray-300 mb-1">No. WhatsApp / HP</label>
                    <input type="text" name="phone" x-model="activePhone" class="w-full bg-slate-900 border border-gray-700 p-2.5 rounded-lg text-white" required>
                </div>
                <div>
                    <label class="block font-semibold text-gray-300 mb-1">Pengalaman Kerja</label>
                    <input type="text" name="experience" x-model="activeExperience" class="w-full bg-slate-900 border border-gray-700 p-2.5 rounded-lg text-white" required>
                </div>
                <div>
                    <label class="block font-semibold text-gray-300 mb-1">Status</label>
                    <select name="status" x-model="activeStatus" class="w-full border border-gray-700 text-gray-300 p-2.5 rounded-lg bg-slate-900" required>
                        <option value="tersedia">Tersedia</option>
                        <option value="bertugas">Sedang Bertugas</option>
                    </select>
                </div>

                <!-- Tombol Save & Batal -->
                <div class="flex space-x-3 pt-2">
                    <button type="submit" class="flex-1 bg-yellow-400 hover:bg-white text-black font-extrabold py-2.5 rounded-lg transition cursor-pointer">SIMPAN PERUBAHAN</button>
                    <button type="button" @click="modalEditOpen = false" class="bg-gray-800 text-gray-300 px-4 py-2.5 rounded-lg hover:bg-gray-700 transition">Batal</button>
                </div>
            </form>

            <!-- Form Hapus Driver (Terpisah di bagian bawah modal) -->
            <div class="mt-4 pt-4 border-t border-gray-800 flex justify-between items-center">
                <span class="text-[11px] text-red-400">Hapus data dari sistem?</span>
                <form :action="'/admin/drivers/' + activeDriverId" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus supir ini?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-500/20 hover:bg-red-500 text-red-400 hover:text-white border border-red-500/40 px-3 py-1.5 rounded-lg font-bold transition cursor-pointer">
                        Hapus Driver
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>