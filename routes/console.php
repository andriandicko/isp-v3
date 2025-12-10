<?php

use Illuminate\Foundation\Inspect\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

// Command bawaan Laravel
Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// --- SCHEDULER PROJECT ANDA ---

// 1. BILLING: Cek tagihan jatuh tempo & isolir (Setiap jam 01:00 pagi)
Schedule::command('billing:check-overdue')
    ->dailyAt('01:00')
    ->withoutOverlapping()
    ->appendOutputTo(storage_path('logs/scheduler.log'));

// 2. ABSENSI: Auto set 'Alpha' jika tidak absen (Setiap jam 23:00 malam)
// (Pastikan command 'attendance:auto-absent' sudah ada di app/Console/Commands)
Schedule::command('attendance:auto-absent')
    ->dailyAt('01:00') 
    ->withoutOverlapping()
    ->appendOutputTo(storage_path('logs/scheduler.log'));