<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('cars', function (Blueprint $table) {
            $table->string('rental_type')->nullable()->after('user_id');
            $table->unsignedBigInteger('driver_id')->nullable()->after('rental_type');
        });
    }

    public function down(): void {
        Schema::table('cars', function (Blueprint $table) {
            $table->dropColumn(['rental_type', 'driver_id']);
        });
    }
};