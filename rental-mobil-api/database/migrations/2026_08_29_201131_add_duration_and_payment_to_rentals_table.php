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
    Schema::table('rentals', function (Blueprint $table) {
        $table->string('duration_type')->default('daily'); // daily, weekly, monthly
        $table->integer('days_count')->default(1); // jumlah hari jika daily
        $table->string('payment_method')->nullable(); // BCA, BNI, dll
    });
}

public function down(): void
{
    Schema::table('rentals', function (Blueprint $table) {
        $table->dropColumn(['duration_type', 'days_count', 'payment_method']);
    });
}
};
