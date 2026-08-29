<div class="bg-gray-900 border border-gray-800 p-6 rounded-2xl shadow-md">
    <div class="flex justify-between items-center mb-6 pb-4 border-b border-gray-800">
        <div>
            <h2 class="text-lg font-bold text-white">Daftar Akun Client / Pengguna Website</h2>
            <p class="text-xs text-gray-400 mt-1">Berikut adalah daftar user yang telah melakukan registrasi akun melalui form pop-up di React client.</p>
        </div>
        <span class="bg-blue-500/10 text-blue-400 border border-blue-500/30 text-xs px-3 py-1.5 rounded-lg font-bold">
            Total Akun: {{ isset($users) ? count($users) : 0 }}
        </span>
    </div>

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
                @forelse($users ?? [] as $usr)
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