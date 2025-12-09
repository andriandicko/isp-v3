<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('billings', function (Blueprint $table) {
            // Ubah kolom menjadi nullable
            $table->unsignedBigInteger('coverage_area_id')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('billings', function (Blueprint $table) {
            // Kembalikan ke NOT NULL (Hati-hati jika ada data NULL)
            $table->unsignedBigInteger('coverage_area_id')->nullable(false)->change();
        });
    }
};