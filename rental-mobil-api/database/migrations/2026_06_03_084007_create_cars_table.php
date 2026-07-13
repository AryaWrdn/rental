<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('cars', function (Blueprint $table) {
            $table->id();
            $table->string('name');            // Nama mobil (e.g., Honda Brio)
            $table->string('icon')->nullable(); // Emoji atau nama file gambar (e.g., 🚗)
            $table->integer('price');          // Harga sewa per hari (e.g., 350000)
            $table->string('type');            // Tipe mobil (e.g., CITY CAR, SUV, MPV)
            $table->string('capacity');        // Kapasitas (e.g., 4x Penumpang)
            $table->string('transmission');    // Transmisi (e.g., Manual/Matic)
            $table->string('monthly_price');   // Harga bulanan (e.g., 6 JUTA/Bulan)
            $table->integer('driver_price');   // Harga mobil + driver (e.g., 550000)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cars');
    }
};
