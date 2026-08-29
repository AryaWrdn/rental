<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Car;
use App\Models\User;
use App\Models\Driver;
use App\Models\Rental; // Import model Rental
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon; // Import Carbon untuk filter laporan waktu

class CarController extends Controller
{
    public function index()
    {
        $cars = Car::withCount('rentals')->get(); // Mengambil armada beserta jumlah total disewa (terlaris)
        $availableCars = Car::where('status', 'tersedia')->get();
        $rentedCars = Car::where('status', 'disewa')->with(['user', 'driver'])->get();
        $users = User::all();
        $drivers = Driver::all();

        // Data untuk Dashboard Monitoring & Laporan
        $activeRentals = Rental::where('status', 'aktif')->with(['car', 'user', 'driver'])->get();
        $weeklyRentals = Rental::where('created_at', '>=', Carbon::now()->subDays(7))->get();
        $topCars = Car::withCount('rentals')->orderBy('rentals_count', 'desc')->take(5)->get();

        return view('admin.cars.index', compact(
            'cars', 
            'availableCars', 
            'rentedCars', 
            'users', 
            'drivers', 
            'activeRentals', 
            'weeklyRentals', 
            'topCars'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'icon' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
            'price' => 'required|numeric',
            'type' => 'required|string',
            'capacity' => 'required|string',
            'transmission' => 'required|string',
            'is_lepas_kunci' => 'required|string',
            'monthly_price' => 'required|string',
            'driver_price' => 'required|numeric',
        ]);

        $data = $request->except(['icon', 'is_lepas_kunci']);
        $data['transmission'] = $request->is_lepas_kunci . ' (' . $request->transmission . ')';

        if ($request->hasFile('icon')) {
            $file = $request->file('icon');
            $filename = time() . '_' . str_replace(' ', '_', strtolower($file->getClientOriginalName()));
            $file->storeAs('cars', $filename, 'public');
            $data['icon'] = $filename;
        }

        Car::create($data);

        return redirect()->back()->with('success', 'Data mobil berhasil ditambahkan dengan nama gambar asli!');
    }

    public function destroy($id)
    {
        $car = Car::findOrFail($id);

        if ($car->icon && Storage::disk('public')->exists('cars/' . $car->icon)) {
            Storage::disk('public')->delete('cars/' . $car->icon);
        }

        $car->delete();

        return redirect()->back()->with('success', 'Data mobil berhasil dihapus!');
    }

    public function bookCar(Request $request, $id)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'rental_type' => 'required|in:lepas_kunci,dengan_supir',
            'driver_id' => 'required_if:rental_type,dengan_supir'
        ]);

        $car = Car::findOrFail($id);

        // Hitung total harga (bisa disesuaikan jika menggunakan tarif per hari atau paket)
        $totalPrice = $request->rental_type == 'dengan_supir' ? $car->driver_price : $car->price;

        // 1. Simpan riwayat transaksi ke tabel rentals (Database Monitoring)
        Rental::create([
            'car_id' => $car->id,
            'user_id' => $request->user_id,
            'driver_id' => $request->rental_type == 'dengan_supir' ? $request->driver_id : null,
            'rental_type' => $request->rental_type,
            'total_price' => $totalPrice,
            'status' => 'aktif'
        ]);

        // 2. Update status mobil
        $car->status = 'disewa';
        $car->user_id = $request->user_id;
        $car->rental_type = $request->rental_type;
        $car->driver_id = $request->rental_type == 'dengan_supir' ? $request->driver_id : null;
        $car->save();

        // 3. Update status supir jika menggunakan supir
        if ($request->rental_type == 'dengan_supir' && $request->driver_id) {
            $driver = Driver::findOrFail($request->driver_id);
            $driver->status = 'bertugas';
            $driver->save();
        }

        return redirect()->back()->with('success', "Mobil {$car->name} berhasil disewa dan tercatat di sistem monitoring.");
    }

    public function returnCar($id) 
    {
        $car = Car::findOrFail($id);
        
        // Update riwayat rental yang aktif menjadi 'selesai'
        Rental::where('car_id', $car->id)->where('status', 'aktif')->update([
            'status' => 'selesai'
        ]);

        // Kembalikan status supir jika ada
        if ($car->driver_id) {
            $driver = Driver::find($car->driver_id);
            if ($driver) {
                $driver->status = 'tersedia';
                $driver->save();
            }
        }

        // Reset status mobil
        $car->status = 'tersedia';
        $car->user_id = null;
        $car->rental_type = null;
        $car->driver_id = null;
        $car->save();

        return redirect()->back()->with('success', 'Mobil telah dikembalikan dan transaksi selesai!');
    }
    public function showKtp($filename)
{
    $path = 'ktp/' . $filename;

    // Cek apakah file benar-benar ada di storage/app/public/ktp/
    if (!Storage::disk('public')->exists($path)) {
        abort(404, 'File KTP tidak ditemukan.');
    }

    $file = Storage::disk('public')->get($path);
    $type = Storage::disk('public')->mimeType($path);

    return response($file, 200)->header('Content-Type', $type);
}

public function exportExcel()
{
    $rentals = Rental::with(['user', 'car', 'driver'])->latest()->get();

    $filename = 'Laporan_Transaksi_Rental_' . date('Y-m-d') . '.xls';

    header("Content-Type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=\"$filename\"");

    echo '<table border="1">';
    
    // Judul Laporan di bagian atas (colspan disesuaikan jadi 8 kolom)
    echo '<tr>';
    echo '<td colspan="8" style="font-size: 14pt; font-weight: bold; text-align: center; background-color: #ffff99; height: 40px;">LAPORAN TRANSAKSI PENYEWAAN MOBIL VJ RENTAL</td>';
    echo '</tr>';
    echo '<tr><td colspan="8"></td></tr>';

    // Header Tabel
    echo '<thead>';
    echo '<tr style="background-color: #4F81BD; color: #ffffff; font-weight: bold; text-align: center; height: 25px;">';
    echo '<th style="width: 50px;">No</th>';
    echo '<th style="width: 150px;">Waktu Transaksi</th>';
    echo '<th style="width: 180px;">Nama Penyewa</th>';
    echo '<th style="width: 180px;">Nama Mobil</th>';
    echo '<th style="width: 130px;">Jenis Layanan</th>';
    echo '<th style="width: 160px;">Supir Bertugas</th>';
    echo '<th style="width: 130px;">Durasi Disewa</th>';
    echo '<th style="width: 150px;">Total Harga</th>';
    echo '</tr>';
    echo '</thead>';
    echo '<tbody>';

    $no = 1;
    $totalPendapatan = 0;

    foreach ($rentals as $rental) {
        // Format Teks Durasi
        $durasi = '-';
        if ($rental->duration_type == 'daily') {
            $durasi = $rental->days_count . ' Hari';
        } elseif ($rental->duration_type == 'weekly') {
            $durasi = '1 Minggu (7 Hari)';
        } elseif ($rental->duration_type == 'monthly') {
            $durasi = '1 Bulan (30 Hari)';
        }

        // Format Jenis Layanan & Supir
        $jenisLayanan = ($rental->rental_type == 'dengan_supir') ? 'Dengan Supir' : 'Lepas Kunci';
        $namaSupir = ($rental->rental_type == 'dengan_supir' && $rental->driver) ? $rental->driver->name : '-';

        $totalPendapatan += $rental->total_price;

        echo '<tr>';
        echo '<td style="text-align: center;">' . $no++ . '</td>';
        echo '<td style="text-align: center;">' . $rental->created_at->format('d-m-Y H:i') . '</td>';
        echo '<td style="text-align: left;">' . ($rental->user->name ?? 'Tidak Diketahui') . '</td>';
        echo '<td style="text-align: left;">' . ($rental->car->name ?? 'Mobil Dihapus') . '</td>';
        echo '<td style="text-align: center;">' . $jenisLayanan . '</td>';
        echo '<td style="text-align: left;">' . $namaSupir . '</td>';
        echo '<td style="text-align: center;">' . $durasi . '</td>';
        echo '<td style="text-align: right;">Rp ' . number_format($rental->total_price, 0, ',', '.') . '</td>';
        echo '</tr>';
    }

    // Baris Total Pendapatan di bawah (colspan disesuaikan ke 7 agar sejajar dengan kolom total harga di kolom ke-8)
    echo '<tr style="font-weight: bold; background-color: #DCE6F1; height: 25px;">';
    echo '<td colspan="7" style="text-align: right; padding-right: 10px;">TOTAL PENDAPATAN:</td>';
    echo '<td style="text-align: right;">Rp ' . number_format($totalPendapatan, 0, ',', '.') . '</td>';
    echo '</tr>';

    echo '</tbody>';
    echo '</table>';
    exit();
}
}