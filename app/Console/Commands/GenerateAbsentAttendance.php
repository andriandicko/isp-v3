<?php

namespace App\Console\Commands;

use App\Models\Attendance;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;

class GenerateAbsentAttendance extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'attendance:generate-absent {date?}';

    /**
     * The description of the console command.
     *
     * @var string
     */
    protected $description = 'Generate absent attendance for users who did not check in';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $date = $this->argument('date') ? Carbon::parse($this->argument('date')) : Carbon::yesterday();

        $this->info("Generating absent attendance for date: {$date->toDateString()}");

        // Get all users dengan shift aktif
        $users = User::whereHas('userShifts', function ($query) use ($date) {
            $query->where('is_active', true)
                ->whereDate('effective_date', '<=', $date);
        })->get();

        $absentCount = 0;

        foreach ($users as $user) {
            // Check apakah user punya cuti/izin di tanggal ini
            if ($user->hasLeaveOnDate($date)) {
                continue;
            }

            // Get shift user
            $userShift = $user->getActiveShift();

            if (!$userShift) {
                continue;
            }

            // Check apakah hari ini termasuk hari kerja
            $dayName = strtolower($date->format('l'));

            if (!$userShift->shift->isActiveOnDay($dayName)) {
                continue;
            }

            // Check apakah sudah ada attendance
            $attendance = Attendance::where('user_id', $user->id)
                ->whereDate('date', $date)
                ->first();

            // Jika belum ada attendance, buat sebagai absent
            if (!$attendance) {
                Attendance::create([
                    'user_id' => $user->id,
                    'shift_id' => $userShift->shift_id,
                    'date' => $date,
                    'status' => 'absent',
                    'notes' => 'Auto-generated: Tidak hadir tanpa keterangan',
                ]);

                $absentCount++;
                $this->line("- {$user->name}: Marked as absent");
            }
        }

        $this->info("Done! Generated {$absentCount} absent records.");

        return Command::SUCCESS;
    }
}
