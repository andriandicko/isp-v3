<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Leave extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'type',
        'start_date',
        'end_date',
        'reason',
        'attachment',
        'status',
        'approved_by',
        'approval_notes',
        'approved_at',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'approved_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    // Hitung jumlah hari
    public function getDaysCount()
    {
        return $this->start_date->diffInDays($this->end_date) + 1;
    }

    // Check apakah tanggal tertentu termasuk dalam periode cuti/izin
    public function isDateIncluded($date)
    {
        $checkDate = \Carbon\Carbon::parse($date);
        return $checkDate->between($this->start_date, $this->end_date);
    }
}
