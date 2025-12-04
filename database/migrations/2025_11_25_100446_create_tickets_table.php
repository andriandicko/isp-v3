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
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();

            // 1. Identitas Tiket
            $table->string('ticket_code')->unique(); // Contoh: TIK-202511-001

            // 2. Relasi (Siapa yang lapor & Siapa yang ngerjain)
            // customer_id wajib ada (karena tiket punya pelanggan)
            $table->foreignId('customer_id')->constrained('customers')->cascadeOnDelete();

            // user_id (Teknisi) boleh kosong dulu (nullable) saat tiket baru dibuat
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();

            // 3. Detail Masalah
            $table->string('subject'); // Judul keluhan (misal: Internet Mati Total)
            $table->text('description'); // Kronologi

            // 4. Status & Prioritas (Penting buat ISP)
            $table->enum('priority', ['low', 'medium', 'high', 'critical'])->default('medium');
            $table->enum('status', ['open', 'in_progress', 'resolved', 'closed'])->default('open');

            // 5. Bukti Foto (Optional)
            $table->string('photo')->nullable(); // Foto modem merah/kabel putus

            // 6. Waktu Penyelesaian
            $table->timestamp('resolved_at')->nullable(); // Kapan internet nyala lagi

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
