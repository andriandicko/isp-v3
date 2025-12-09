<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('billings', function (Blueprint $table) {
            // Tambah kolom notes untuk menyimpan nama paket custom
            $table->text('notes')->nullable()->after('status');
            
            // Pastikan package_id boleh kosong (nullable)
            // Kita gunakan unsignedBigInteger agar aman saat change()
            $table->unsignedBigInteger('package_id')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('billings', function (Blueprint $table) {
            $table->dropColumn('notes');
            $table->unsignedBigInteger('package_id')->nullable(false)->change();
        });
    }
};
