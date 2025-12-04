<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Shift extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'start_time',
        'end_time',
        'days',
        'is_active',
    ];

    protected $casts = [
        'days' => 'array',
        'is_active' => 'boolean',
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
    ];

    public function userShifts()
    {
        return $this->hasMany(UserShift::class);
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    // Check apakah shift berlaku di hari tertentu
    public function isActiveOnDay($day)
    {
        return in_array(strtolower($day), array_map('strtolower', $this->days));
    }
}
