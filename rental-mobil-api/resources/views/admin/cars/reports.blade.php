<div class="space-y-6">
    <!-- Kartu Statistik Utama -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-gray-900 border border-gray-800 p-6 rounded-2xl shadow-md">
            <p class="text-xs text-gray-400 font-bold uppercase">Mobil Sedang Dipakai</p>
            <h3 class="text-3xl font-black text-yellow-400 mt-1">{{ isset($activeRentals) ? $activeRentals->count() : 0 }} <span class="text-xs text-gray-400 font-normal">Unit</span></h3>
        </div>

        <!-- KARTU TRANSAKSI + TOMBOL EXPORT EXCEL DI SAMPINGNYA -->
        <div class="bg-gray-900 border border-gray-800 p-6 rounded-2xl shadow-md flex justify-between items-center">
            <div>
                <p class="text-xs text-gray-400 font-bold uppercase">Transaksi (1 Minggu Terakhir)</p>
                <h3 class="text-3xl font-black text-blue-400 mt-1">{{ isset($weeklyRentals) ? $weeklyRentals->count() : 0 }} <span class="text-xs text-gray-400 font-normal">Sewa</span></h3>
            </div>
            <div>
                <a href="{{ route('admin.rentals.export') }}" class="bg-green-600 hover:bg-green-500 text-white font-bold text-xs px-3 py-2.5 rounded-xl transition shadow flex items-center space-x-1.5 cursor-pointer">
                    <span>📊</span>
                    <span>Export Excel</span>
                </a>
            </div>
        </div>

        <div class="bg-gray-900 border border-gray-800 p-6 rounded-2xl shadow-md">
            <p class="text-xs text-gray-400 font-bold uppercase">Estimasi Pendapatan (Mingguan)</p>
            <h3 class="text-2xl font-black text-green-400 mt-1 font-mono">IDR {{ isset($weeklyRentals) ? number_format($weeklyRentals->sum('total_price'), 0, ',', '.') : 0 }}</h3>
        </div>
    </div>

    <!-- Armada Terlaris -->
    <div class="bg-gray-900 border border-gray-800 p-6 rounded-2xl shadow-md">
        <h3 class="text-sm font-bold text-white mb-4 pb-2 border-b border-gray-800">🔥 Armada Terlaris (Paling Sering Dibooking)</h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
            @forelse($topCars ?? [] as $car)
                <div class="bg-slate-900 border border-gray-800 p-4 rounded-xl flex justify-between items-center">
                    <div>
                        <p class="font-bold text-white text-xs">{{ $car->name }}</p>
                        <p class="text-[10px] text-gray-400">Tipe: {{ $car->type }}</p>
                    </div>
                    <span class="bg-yellow-400/10 text-yellow-400 border border-yellow-400/30 text-xs px-2.5 py-1 rounded-lg font-extrabold">
                        {{ $car->rentals_count }}x Sewa
                    </span>
                </div>
            @empty
                <p class="text-xs text-gray-500 col-span-3 text-center py-4">Belum ada data riwayat penyewaan mobil.</p>
            @endforelse
        </div>
    </div>

    <!-- Tabel Monitoring Transaksi Aktif -->
    <div class="bg-gray-900 border border-gray-800 p-6 rounded-2xl shadow-md" x-data="{ openDetail: false, selectedRental: {} }">
        <h3 class="text-sm font-bold text-white mb-4 pb-2 border-b border-gray-800">📋 Daftar Client & Unit yang Sedang Dibooking (Aktif)</h3>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-slate-900 text-gray-400 uppercase border-b border-gray-800">
                        <th class="p-3">Waktu Sewa</th>
                        <th class="p-3">Mobil</th>
                        <th class="p-3">Penyewa (Client)</th>
                        <th class="p-3">Username</th>
                        <th class="p-3">Jenis Layanan</th>
                        <th class="p-3">Supir Bertugas</th>
                        <th class="p-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-800 text-gray-200">
                    @forelse($activeRentals ?? [] as $rental)
                        <tr>
                            <td class="p-3 text-gray-400">{{ $rental->created_at->format('d M Y, H:i') }}</td>
                            <td class="p-3 font-bold text-white flex items-center space-x-2">
                                <span>🚗</span>
                                <span>{{ $rental->car->name ?? 'Mobil Dihapus' }}</span>
                            </td>
                            <td class="p-3 text-yellow-400 font-semibold">{{ $rental->user->name ?? 'Tidak Diketahui' }}</td>
                            <td class="p-3 text-gray-400 font-mono text-yellow-300">{{ $rental->user->username ?? $rental->user->name ?? '-' }}</td>
                            <td class="p-3">
                                <span class="px-2 py-1 rounded text-[10px] font-bold {{ $rental->rental_type == 'dengan_supir' ? 'bg-yellow-500/20 text-yellow-300 border border-yellow-500/30' : 'bg-purple-500/20 text-purple-300 border border-purple-500/30' }}">
                                    {{ ucfirst(str_replace('_', ' ', $rental->rental_type)) }}
                                </span>
                            </td>
                            <td class="p-3 text-gray-300">
                                @if($rental->rental_type == 'dengan_supir')
                                    {{ $rental->driver->name ?? 'Supir Ditugaskan' }} ({{ $rental->driver->phone ?? '-' }})
                                @else
                                    <span class="text-gray-500 italic">Lepas Kunci</span>
                                @endif
                            </td>
                            <td class="p-3 text-center">
                                <button 
                                    @click="openDetail = true; selectedRental = {{ json_encode([
                                        'waktu' => $rental->created_at->format('d M Y, H:i'),
                                        'mobil' => $rental->car->name ?? '-',
                                        'tipeMobil' => $rental->car->type ?? '-',
                                        'namaClient' => $rental->user->name ?? '-',
                                        'usernameClient' => $rental->user->username ?? $rental->user->name ?? '-',
                                        'layanan' => ucfirst(str_replace('_', ' ', $rental->rental_type)),
                                        'durasiSewa' => $rental->duration_type == 'daily' ? $rental->days_count . ' Hari' : ($rental->duration_type == 'weekly' ? '1 Minggu (7 Hari)' : '1 Bulan (30 Hari)'),
                                        'metodePembayaran' => $rental->payment_method ?? '-',
                                        'supir' => $rental->driver->name ?? 'Tidak Menggunakan Supir',
                                        'telpSupir' => $rental->driver->phone ?? '-',
                                        'hargaSewa' => 'IDR ' . number_format($rental->car_price ?? ($rental->total_price - ($rental->rental_type == 'dengan_supir' ? 100000 * ($rental->days_count ?? 1) : 0)), 0, ',', '.'),
                                        'biayaSupir' => $rental->rental_type == 'dengan_supir' ? 'IDR ' . number_format(100000 * ($rental->days_count ?? 1), 0, ',', '.') : 'Rp 0 (Lepas Kunci)',
                                        'totalHarga' => 'IDR ' . number_format($rental->total_price, 0, ',', '.'),
                                        'ktp' => $rental->ktp ? asset('storage/ktp/' . $rental->ktp) : '',
                                        'paymentProof' => $rental->payment_proof ? asset('storage/payment_proofs/' . $rental->payment_proof) : ''
                                    ]) }}"
                                    class="bg-blue-600 hover:bg-blue-500 text-white font-bold px-3 py-1.5 rounded-lg transition cursor-pointer text-[10px]">
                                    🔍 Detail
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="p-8 text-center text-gray-500">Tidak ada unit mobil yang sedang disewa saat ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- MODAL DETAIL RENTAL -->
        <div x-show="openDetail" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center bg-black/80 backdrop-blur-sm p-4">
            <div @click.away="openDetail = false" class="bg-gray-900 border border-gray-800 w-full max-w-lg rounded-2xl p-6 text-white shadow-2xl space-y-4 max-h-[90vh] overflow-y-auto">
                <div class="flex justify-between items-center border-b border-gray-800 pb-3">
                    <h3 class="text-sm font-bold text-yellow-400 uppercase">📄 Detail Informasi Penyewaan</h3>
                    <button @click="openDetail = false" class="text-gray-400 hover:text-white font-bold text-sm cursor-pointer">✕</button>
                </div>

                <div class="space-y-3 text-xs">
                    <div class="grid grid-cols-2 gap-3 bg-slate-900 p-3 rounded-xl border border-gray-800">
                        <div>
                            <p class="text-gray-400">Waktu Sewa:</p>
                            <p class="font-bold text-white" x-text="selectedRental.waktu"></p>
                        </div>
                        <div>
                            <p class="text-gray-400">Total Harga:</p>
                            <p class="font-bold text-green-400" x-text="selectedRental.totalHarga"></p>
                        </div>
                    </div>

                    <!-- Kotak Informasi Durasi & Pembayaran Rinci -->
                    <div class="bg-slate-900 p-3 rounded-xl border border-gray-800 space-y-2">
                        <p class="text-gray-400 font-bold uppercase text-[10px] text-yellow-400 mb-1">Detail Durasi & Pembayaran</p>
                        <div class="flex justify-between text-gray-300 border-b border-gray-800 pb-1.5">
                            <span class="text-gray-400">Durasi Sewa:</span> 
                            <span class="font-bold text-white" x-text="selectedRental.durasiSewa"></span>
                        </div>
                        <div class="flex justify-between text-gray-300 border-b border-gray-800 pb-1.5">
                            <span class="text-gray-400">Metode Pembayaran:</span> 
                            <span class="font-bold text-yellow-400" x-text="selectedRental.metodePembayaran"></span>
                        </div>
                        <div class="flex justify-between text-gray-300 pb-1">
                            <span class="text-gray-400">Harga Sewa Mobil:</span> 
                            <span class="font-mono text-white" x-text="selectedRental.hargaSewa"></span>
                        </div>
                        <div class="flex justify-between text-gray-300 pb-1">
                            <span class="text-gray-400">Biaya Supir:</span> 
                            <span class="font-mono text-white" x-text="selectedRental.biayaSupir"></span>
                        </div>
                        <div class="flex justify-between text-gray-300 pt-2 border-t border-gray-800 font-bold">
                            <span class="text-gray-200">Total Pembayaran:</span> 
                            <span class="font-mono text-green-400 text-sm" x-text="selectedRental.totalHarga"></span>
                        </div>
                    </div>

                    <div class="bg-slate-900 p-3 rounded-xl border border-gray-800 space-y-1">
                        <p class="text-gray-400 font-bold uppercase text-[10px] text-yellow-400 mb-1">Informasi Client / Penyewa</p>
                        <p><span class="text-gray-400">Nama:</span> <span class="font-bold text-white" x-text="selectedRental.namaClient"></span></p>
                        <p><span class="text-gray-400">Username:</span> <span class="text-white font-mono text-yellow-300" x-text="selectedRental.usernameClient"></span></p>
                    </div>

                    <div class="bg-slate-900 p-3 rounded-xl border border-gray-800 space-y-1">
                        <p class="text-gray-400 font-bold uppercase text-[10px] text-yellow-400 mb-1">Informasi Kendaraan & Layanan</p>
                        <p><span class="text-gray-400">Mobil:</span> <span class="font-bold text-white" x-text="selectedRental.mobil + ' (' + selectedRental.tipeMobil + ')'"></span></p>
                        <p><span class="text-gray-400">Tipe Layanan:</span> <span class="font-bold text-blue-400" x-text="selectedRental.layanan"></span></p>
                        <p><span class="text-gray-400">Supir Bertugas:</span> <span class="text-white" x-text="selectedRental.supir + (selectedRental.telpSupir !== '-' ? ' (' + selectedRental.telpSupir + ')' : '')"></span></p>
                    </div>

                    <!-- Tampilan Foto KTP -->
                    <div class="bg-slate-900 p-3 rounded-xl border border-gray-800">
                        <p class="text-gray-400 font-bold uppercase text-[10px] text-yellow-400 mb-2">Foto KTP Penyewa:</p>
                        <template x-if="selectedRental.ktp">
                            <div class="border border-gray-700 rounded-lg overflow-hidden bg-black/40 flex justify-center p-2">
                                <img :src="selectedRental.ktp" alt="Foto KTP" class="max-h-48 object-contain rounded-md" />
                            </div>
                        </template>
                        <template x-if="!selectedRental.ktp">
                            <p class="text-gray-500 italic text-center py-4 bg-gray-950 rounded-lg">Tidak ada lampiran foto KTP.</p>
                        </template>
                    </div>

                    <!-- Tampilan Foto Bukti Pembayaran -->
                    <div class="bg-slate-900 p-3 rounded-xl border border-gray-800">
                        <p class="text-gray-400 font-bold uppercase text-[10px] text-yellow-400 mb-2">Foto Bukti Pembayaran:</p>
                        <template x-if="selectedRental.paymentProof">
                            <div class="border border-gray-700 rounded-lg overflow-hidden bg-black/40 flex justify-center p-2">
                                <img :src="selectedRental.paymentProof" alt="Bukti Pembayaran" class="max-h-48 object-contain rounded-md" />
                            </div>
                        </template>
                        <template x-if="!selectedRental.paymentProof">
                            <p class="text-gray-500 italic text-center py-4 bg-gray-950 rounded-lg">Tidak ada lampiran bukti pembayaran.</p>
                        </template>
                    </div>
                </div>

                <div class="pt-2 border-t border-gray-800 flex justify-end">
                    <button @click="openDetail = false" class="bg-gray-800 hover:bg-gray-700 text-gray-300 font-bold px-4 py-2 rounded-lg text-xs transition cursor-pointer">Tutup</button>
                </div>
            </div>
        </div>
    </div>
</div>