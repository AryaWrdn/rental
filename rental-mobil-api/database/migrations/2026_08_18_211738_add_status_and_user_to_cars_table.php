<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cars', function (Blueprint $table) {
            $table->enum('status', ['tersedia', 'disewa'])->default('tersedia')->after('driver_price');
            $table->unsignedBigInteger('user_id')->nullable()->after('status'); // Menyimpan siapa yang sewa
            
            // Opsional foreign key ke tabel users
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('cars', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn(['status', 'user_id']);
        });
    }
};