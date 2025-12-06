<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Attendance;
use Carbon\Carbon;

class AutoAbsent extends Command
{
    // Nama command untuk dipanggil di terminal
    protected $signature = 'attendance:auto-absent';

    // Deskripsi command
    protected $description = 'Otomatis set status Absent untuk karyawan yang tidak check-in kemarin';

    public function handle()
    {
        $this->info('Memulai proses pengecekan absensi...');

        // 1. Setting Timezone & Tanggal Kemarin
        // Kita cek data KEMARIN. Kenapa kemarin? Agar shift malam (lintas hari) aman.
        $timezone = config('app.timezone', 'Asia/Jakarta');
        $yesterday = Carbon::yesterday($timezone); 

        // 2. Ambil semua User Aktif (Kecuali Admin)
        $users = User::whereDoesntHave('roles', function($q) {
            $q->where('name', 'admin');
        })->where('id', '!=', 1)->get(); // Sesuaikan filter user Anda

        $count = 0;

        foreach ($users as $user) {
            // 3. Cek apakah user punya shift di tanggal KEMARIN
            // Kita pakai fungsi yang sudah kita buat di User.php
            $shifts = $user->getAllowedShiftsToday($yesterday);

            if ($shifts->isEmpty()) {
                continue; // Skip jika memang libur / tidak ada jadwal
            }

            // 4. Cek apakah sudah ada data absensi (Hadir/Sakit/Izin)
            $attendance = Attendance::where('user_id', $user->id)
                ->whereDate('date', $yesterday)
                ->exists();

            // 5. Jika Punya Jadwal TAPI Tidak Ada Data Absen -> Tandai Alpha
            if (!$attendance) {
                // Ambil shift pertama sebagai referensi (untuk data shift_id)
                $assignment = $shifts->first();
                
                Attendance::create([
                    'user_id' => $user->id,
                    'shift_id' => $assignment->shift_id,
                    'date' => $yesterday,
                    'status' => 'absent', // Status Alpha
                    'check_in_time' => null,
                    'check_out_time' => null,
                    // Field lain bisa dikosongkan atau diberi default
                ]);

                $this->info("User {$user->name} ditandai Alpha untuk tanggal {$yesterday->toDateString()}");
                $count++;
            }
        }

        $this->info("Selesai! Total karyawan alpha: {$count}");
    }
}