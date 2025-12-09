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
        Schema::table('customers', function (Blueprint $table) {
            // Ubah kolom menjadi nullable agar bisa menyimpan nilai NULL
            // Kita gunakan unsignedBigInteger agar tipe datanya tetap sama dengan sebelumnya (foreignId)
            $table->unsignedBigInteger('coverage_area_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            // Kembalikan ke keadaan semula (NOT NULL)
            // Hati-hati: ini akan error jika sudah ada data yang NULL di database
            $table->unsignedBigInteger('coverage_area_id')->nullable(false)->change();
        });
    }
};