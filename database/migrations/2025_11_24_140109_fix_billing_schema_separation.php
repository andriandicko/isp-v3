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
        //
        // 1. Pindahkan data teknis ke tabel CUSTOMERS
        Schema::table('customers', function (Blueprint $table) {
            // Status customer (Penting untuk filter siapa yang ditagih)
            $table->enum('status', ['active', 'isolir', 'inactive'])->default('active')->after('type');

            // Data Teknis (Pindahan dari Billing)
            $table->string('no_odp')->nullable();
            $table->string('mac_ont')->nullable();
            $table->string('foto_ktp')->nullable();
            $table->string('foto_rumah')->nullable();
            $table->string('foto_redaman')->nullable();
        });

        // 2. Bersihkan tabel BILLINGS
        Schema::table('billings', function (Blueprint $table) {
            // Hapus kolom teknis yang bikin duplikat
            $table->dropColumn(['no_odp', 'mac_ont', 'foto_ktp', 'foto_rumah', 'foto_redaman']);

            // Tambahkan kolom pencatatan pembayaran
            $table->string('payment_method')->nullable()->after('amount'); // cash, transfer
            $table->timestamp('paid_at')->nullable()->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        // Logic rollback (kembalikan seperti semula)
        Schema::table('customers', function (Blueprint $table) {
            $table->dropColumn(['status', 'no_odp', 'mac_ont', 'foto_ktp', 'foto_rumah', 'foto_redaman']);
        });

        Schema::table('billings', function (Blueprint $table) {
            $table->string('no_odp')->nullable();
            $table->string('mac_ont')->nullable();
            $table->string('foto_ktp')->nullable();
            $table->string('foto_rumah')->nullable();
            $table->string('foto_redaman')->nullable();
            $table->dropColumn(['payment_method', 'paid_at']);
        });
    }
};
