<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Carbon\Carbon;

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
     * The attributes that should be cast.
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

    // --- RELATIONSHIPS ---

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

    // --- LOGIC METHODS ---

    public function getActiveShift($date = null)
    {
        // Mengambil shift pertama yang valid untuk hari ini
        $shifts = $this->getAllowedShiftsToday($date);
        return $shifts->first();
    }

    public function getAllowedShiftsToday($date = null)
    {
        // 1. Ambil Timezone dari settingan aplikasi (.env)
        // Default ke Asia/Jakarta jika config kosong
        $appTimezone = config('app.timezone', 'Asia/Jakarta');

        // Jika $date null, pakai now(). Jika ada, parse dulu.
        $targetDate = $date ? Carbon::parse($date) : now();
        
        // Set timezone sesuai konfigurasi aplikasi
        $targetDate->setTimezone($appTimezone);
        
        // 2. Ambil nama hari dan PAKSA jadi huruf kecil
        $dayName = strtolower($targetDate->format('l')); 

        // 3. Ambil data UserShift dari DB
        $assignments = $this->userShifts()
            ->where('is_active', true)
            ->whereDate('effective_date', '<=', $targetDate)
            ->with(['shift', 'office'])
            ->get();

        // 4. Filter Logic (Normalisasi Huruf Besar/Kecil & Spasi)
        return $assignments->filter(function ($assignment) use ($dayName) {
            $shift = $assignment->shift;

            if (!$shift || empty($shift->days)) {
                return false;
            }

            $rawDays = is_array($shift->days) ? $shift->days : json_decode($shift->days, true);
            
            if (!is_array($rawDays)) {
                return false;
            }

            $shiftDaysNormalized = array_map(function($day) {
                return strtolower(trim($day));
            }, $rawDays);

            return in_array($dayName, $shiftDaysNormalized);
        });
    }

    // Get semua shift aktif untuk user (tanpa filter hari)
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