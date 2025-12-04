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
        Schema::table('billings', function (Blueprint $table) {
            //
            // Tambahkan kolom start_date dan end_date jika belum ada
            if (!Schema::hasColumn('billings', 'start_date')) {
                $table->date('start_date')->nullable()->after('billing_date');
            }

            if (!Schema::hasColumn('billings', 'end_date')) {
                $table->date('end_date')->nullable()->after('start_date');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('billings', function (Blueprint $table) {
            //
            $table->dropColumn(['start_date', 'end_date']);
        });
    }
};
