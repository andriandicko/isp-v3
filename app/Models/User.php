<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }


    public function customer()
    {
        return $this->hasOne(Customer::class);
    }

    public function korlap()
    {
        return $this->hasOne(Korlap::class);
    }


    public function userShifts()
    {
        return $this->hasMany(UserShift::class);
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function leaves()
    {
        return $this->hasMany(Leave::class);
    }

    public function getActiveShift($date = null)
    {
    // Get shift aktif untuk user berdasarkan hari tertentu
        // $date = $date ? \Carbon\Carbon::parse($date) : now();
        // $dayName = strtolower($date->format('l')); // monday, tuesday, etc

        // return $this->userShifts()
        //     ->with(['shift', 'office'])
        //     ->where('is_active', true)
        //     ->whereDate('effective_date', '<=', $date)
        //     ->whereHas('shift', function ($query) use ($dayName) {
        //         $query->whereJsonContains('days', $dayName);
        //     })
        //     ->orderBy('effective_date', 'desc')
        //     ->first();
        
        
        $shifts = $this->getAllowedShiftsToday($date);
        return $shifts->first();
    }

    public function getAllowedShiftsToday($date = null)
    {
        $date = $date ? \Carbon\Carbon::parse($date) : now();
        $dayName = strtolower($date->format('l')); // monday, tuesday...

        // Ambil semua user_shift aktif
        $assignments = $this->userShifts()
            ->where('is_active', true)
            ->whereDate('effective_date', '<=', $date)
            ->with(['shift', 'office'])
            ->get();

        // Filter manual di PHP karena kolom 'days' bentuknya JSON/Array di tabel shifts
        return $assignments->filter(function ($assignment) use ($dayName) {
            return in_array($dayName, $assignment->shift->days ?? []);
        });
    }

    // Get semua shift aktif untuk user
    public function getActiveShifts()
    {
        return $this->userShifts()
            ->with(['shift', 'office'])
            ->where('is_active', true)
            ->whereDate('effective_date', '<=', now())
            ->orderBy('effective_date', 'desc')
            ->get();
    }

    // Check apakah user punya cuti/izin di tanggal tertentu
    public function hasLeaveOnDate($date)
    {
        return $this->leaves()
            ->where('status', 'approved')
            ->whereDate('start_date', '<=', $date)
            ->whereDate('end_date', '>=', $date)
            ->exists();
    }

    // Get tipe cuti di tanggal tertentu
    public function getLeaveTypeOnDate($date)
    {
        return $this->leaves()
            ->where('status', 'approved')
            ->whereDate('start_date', '<=', $date)
            ->whereDate('end_date', '>=', $date)
            ->first();
    }
}
