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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('coverage_area_id')->constrained('coverage_areas')->cascadeOnDelete();
            $table->foreignId('korlap_id')->nullable()->constrained('korlaps')->nullOnDelete();
            $table->enum('type', ['residential', 'business']);
            $table->string('company_name')->nullable();
            $table->string('contact_person')->nullable();
            $table->text('address')->nullable();
            $table->double('lat', 10, 7);
            $table->double('lng', 10, 7);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
