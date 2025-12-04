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
            $table->string('no_odp')->nullable()->after('coverage_area_id');
            $table->string('foto_ktp')->nullable()->after('no_odp');
            $table->string('foto_rumah')->nullable()->after('foto_ktp');
            $table->string('foto_redaman')->nullable()->after('foto_rumah');
            $table->string('mac_ont')->nullable()->after('foto_redaman');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('billings', function (Blueprint $table) {
            //
            $table->dropColumn([
                'no_odp',
                'foto_ktp',
                'foto_rumah',
                'foto_redaman',
                'mac_ont'
            ]);
        });
    }
};
