<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\BillingController; // Import Controller

class UpdateBillingsStatus extends Command
{
    /**
     * Nama command yang akan dipanggil di Cron Job / Terminal
     */
    protected $signature = 'billing:check-overdue';

    /**
     * Deskripsi command
     */
    protected $description = 'Cek tagihan jatuh tempo dan otomatis isolir pelanggan';

    /**
     * Eksekusi perintah
     */
    public function handle()
    {
        $this->info('Memulai pengecekan tagihan...');

        // Panggil fungsi checkOverdue() dari BillingController
        // Ini memastikan logika yang dijalankan sama persis dengan tombol "Cek Isolir" di web
        $controller = new BillingController();
        
        // Kita perlu mock Request kosong karena controller butuh object Request (opsional tergantung implementasi, tapi method checkOverdue kita tidak butuh input)
        // Namun karena checkOverdue() kita return "back()->with()", itu akan error di console karena tidak ada session/view.
        
        // --- SOLUSI: Kita copy logic intinya saja ke sini agar aman di Console ---
        // Atau memodifikasi Controller agar return value-nya fleksibel.
        // Tapi cara paling aman dan cepat untuk console adalah copy logic intinya:
        
        $today = \Carbon\Carbon::now();
        $countExpired = 0;
        $countIsolir = 0;

        // 1. CEK MASA AKTIF HABIS (Paid -> Overdue)
        $expiredBillings = \App\Models\Billing::where('status', 'paid')
            ->where('end_date', '<', $today)
            ->get();

        foreach ($expiredBillings as $bill) {
            $bill->update([
                'status' => 'overdue',
                'due_date' => \Carbon\Carbon::parse($bill->end_date)->addDays(5)
            ]);
            $countExpired++;
        }

        // 2. CEK JATUH TEMPO (Pending/Overdue -> Isolir)
        $overdueBills = \App\Models\Billing::with('customer')
            ->whereIn('status', ['pending', 'overdue'])
            ->where('due_date', '<', $today)
            ->get();

        foreach ($overdueBills as $bill) {
            if ($bill->status != 'overdue') {
                $bill->update(['status' => 'overdue']);
            }

            if ($bill->customer && $bill->customer->status == 'active') {
                $bill->customer->update(['status' => 'isolir']);
                $countIsolir++;
            }
        }

        $this->info("Selesai. $countExpired tagihan expired, $countIsolir pelanggan diisolir.");
        
        return Command::SUCCESS;
    }
}