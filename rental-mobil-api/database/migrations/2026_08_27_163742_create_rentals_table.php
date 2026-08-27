<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
       Schema::create('rentals', function (Blueprint $table) {
    $table->id();
    $table->foreignId('car_id')->constrained('cars')->onDelete('cascade');
    $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
    $table->foreignId('driver_id')->nullable()->constrained('drivers')->onDelete('set null');
    $table->string('rental_type'); // 'lepas_kunci' atau 'dengan_supir'
    $table->decimal('total_price', 12, 2);
    $table->enum('status', ['aktif', 'selesai'])->default('aktif'); // aktif = sedang disewa, selesai = sudah dikembalikan
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rentals');
    }
};
