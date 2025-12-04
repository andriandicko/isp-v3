<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Billing extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'billing_code',
        'customer_id',
        'coverage_area_id',
        'package_id', // Snapshot paket saat tagihan dibuat
        'billing_date', // Tanggal tagihan dibuat (misal tgl 1)
        'due_date', // Tanggal jatuh tempo (misal tgl 10)
        'start_date', // Periode awal (01-10-2025)
        'end_date', // Periode akhir (31-10-2025)
        'amount',
        'status', // unpaid, paid, overdue
        'payment_method', // cash, transfer
        'paid_at', // Kapan dibayar realnya
    ];

    protected $casts = [
        'billing_date' => 'date',
        'start_date' => 'date',
        'end_date' => 'date',
        'due_date' => 'date',
        'paid_at' => 'datetime',
        'amount' => 'decimal:2',
    ];

    // Relationships
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function coverageArea()
    {
        return $this->belongsTo(CoverageArea::class);
    }

    public function package()
    {
        return $this->belongsTo(Package::class);
    }

    // Helper methods
    public static function generateBillingCode()
    {
        $prefix = 'SPD';
        $date = Carbon::now()->format('dmY'); // 26102025
        $random = strtoupper(substr(uniqid(), -5)); // FA161
        return $prefix . $date . $random;
    }

    public function getDaysUntilDue()
    {
        $today = Carbon::now()->startOfDay();
        $dueDate = Carbon::parse($this->due_date)->startOfDay();

        return (int) $today->diffInDays($dueDate, false);
    }

    public function isNearDueDate()
    {
        $daysUntilDue = $this->getDaysUntilDue();
        return $daysUntilDue <= 10 && $daysUntilDue >= 0;
    }

    public function isOverdue()
    {
        return $this->getDaysUntilDue() < 0;
    }

    public function isOverdueOneWeek()
    {
        return $this->getDaysUntilDue() <= -7;
    }


    // tombol bayar
    public function shouldShowPayButton()
    {
        if ($this->status === 'paid') {
            return $this->isNearDueDate();
        }

        if ($this->status === 'pending') {
            // GANTI DARI: return !$this->isOverdueTenDays();
            // MENJADI:
            return !$this->isOverdueOneWeek();
        }

        return false;
    }

    public function shouldShowContactAdmin()
    {
        return $this->status === 'pending' && $this->isOverdueOneWeek();
    }

    public function getStatusBadgeClass()
    {
        return match ($this->status) {
            'paid' => 'bg-green-100 text-green-800',
            'overdue' => 'bg-red-100 text-red-800',
            default => 'bg-yellow-100 text-yellow-800',
        };
    }

    public function getStatusLabel()
    {
        return match ($this->status) {
            'paid' => 'Lunas',
            'overdue' => 'Terlambat',
            default => 'Pending',
        };
    }

    // generate gmaps
    public function getGoogleMapsLinkAttribute()
    {
        if ($this->customer && $this->customer->lat && $this->customer->lng) {
            return "https://www.google.com/maps?q={$this->customer->lat},{$this->customer->lng}";
        }
        return null;
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }

    public function scopeOverdue($query)
    {
        return $query->where('status', 'overdue');
    }

    public function scopeNearDueDate($query)
    {
        $today = Carbon::now();
        $tenDaysFromNow = Carbon::now()->addDays(10);

        return $query->where('status', 'pending')
            ->whereBetween('due_date', [$today, $tenDaysFromNow]);
    }
}
