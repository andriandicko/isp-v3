<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Schedule untuk generate absent attendance setiap hari jam 00:30
Schedule::command('attendance:generate-absent')
    ->dailyAt('00:30')
    ->timezone('Asia/Jakarta');


Schedule::command('billing:update-status')
    ->dailyAt('00:01')
    ->timezone('Asia/Jakarta');
