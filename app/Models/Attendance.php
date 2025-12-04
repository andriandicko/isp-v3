<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Attendance extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'shift_id',
        'date',
        'check_in_time',
        'check_in_latitude',
        'check_in_longitude',
        'check_in_photo',
        'check_in_distance',
        'check_out_time',
        'check_out_latitude',
        'check_out_longitude',
        'check_out_photo',
        'check_out_distance',
        'status',
        'notes',
    ];

    protected $casts = [
        'date' => 'date',
        'check_in_latitude' => 'float',
        'check_in_longitude' => 'float',
        'check_in_distance' => 'integer',
        'check_out_latitude' => 'float',
        'check_out_longitude' => 'float',
        'check_out_distance' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function shift()
    {
        return $this->belongsTo(Shift::class);
    }

    // Check apakah terlambat
    public function isLate()
    {
        if (!$this->check_in_time || !$this->shift) {
            return false;
        }

        return $this->check_in_time > $this->shift->start_time;
    }

    // Hitung durasi kerja (dalam menit)
    public function getWorkDurationInMinutes()
{
    if (!$this->check_in_time || !$this->check_out_time) {
        return 0;
    }

    // Gunakan tanggal absensi untuk referensi
    $date = \Carbon\Carbon::parse($this->date)->format('Y-m-d');
    
    $start = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $date . ' ' . $this->check_in_time, 'Asia/Jakarta');
    $end = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $date . ' ' . $this->check_out_time, 'Asia/Jakarta');

    // Jika jam checkout lebih kecil dari checkin (misal lembur lewat tengah malam),
    // asumsikan checkout terjadi besoknya
    if ($end->lt($start)) {
        $end->addDay();
    }

    return $start->diffInMinutes($end);
}
}
