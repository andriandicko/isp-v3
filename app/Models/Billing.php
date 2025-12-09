<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Billing extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'billing_code',
        'customer_id',
        'coverage_area_id',
        'package_id',       // Bisa NULL jika paket custom
        'billing_date',     // Tanggal tagihan dibuat
        'due_date',         // Tanggal jatuh tempo (Batas akhir bayar)
        'start_date',       // Periode awal
        'end_date',         // Periode akhir (Masa aktif)
        'amount',
        'status',           // unpaid, paid, overdue, pending
        'payment_method',   // cash, transfer, dll
        'paid_at',          // Kapan dibayar realnya
        'notes',            // <--- WAJIB DITAMBAHKAN
    ];

    protected $casts = [
        'billing_date' => 'date',
        'start_date' => 'date',
        'end_date' => 'date',
        'due_date' => 'date',
        'paid_at' => 'datetime',
        'amount' => 'decimal:2',
    ];

    // --- RELATIONSHIPS ---

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

    // --- HELPER METHODS ---

    public static function generateBillingCode()
    {
        $prefix = 'SPD';
        $date = Carbon::now()->format('dmY'); 
        $random = strtoupper(substr(uniqid(), -5)); 
        
        return $prefix . $date . $random;
    }

    public function getDaysUntilDue()
    {
        // Hitung selisih hari ini dengan Due Date
        $today = Carbon::now()->startOfDay();
        $dueDate = Carbon::parse($this->due_date)->startOfDay();

        return (int) $today->diffInDays($dueDate, false);
    }

    public function isNearDueDate()
    {
        // Cek apakah sudah mendekati masa expired (H-10 dari end_date)
        if ($this->status == 'paid' && $this->end_date) {
            $today = Carbon::now()->startOfDay();
            $endDate = Carbon::parse($this->end_date)->startOfDay();
            $diff = $today->diffInDays($endDate, false);
            
            // Muncul tombol bayar jika sisa 10 hari atau kurang (tapi belum lewat)
            return $diff <= 10 && $diff >= 0;
        }
        return false;
    }

    public function isOverdue()
    {
        return $this->status === 'overdue' || ($this->status !== 'paid' && $this->getDaysUntilDue() < 0);
    }

    // --- LOGIKA TOMBOL & STATUS ---

    public function shouldShowPayButton()
    {
        // 1. Jika Paid: Muncul H-10 sebelum expired
        if ($this->status === 'paid') {
            return $this->isNearDueDate();
        }

        // 2. Jika Pending/Overdue: Muncul selama belum lewat batas toleransi (misal 1 minggu setelah due_date)
        // Atau muncul terus sampai admin menghapusnya/isolir permanen
        if (in_array($this->status, ['pending', 'overdue', 'unpaid'])) {
            return true; 
        }

        return false;
    }

    public function getStatusBadgeClass()
    {
        return match ($this->status) {
            'paid' => 'bg-green-100 text-green-800',
            'overdue' => 'bg-red-100 text-red-800',
            'pending', 'unpaid' => 'bg-yellow-100 text-yellow-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    public function getStatusLabel()
    {
        return match ($this->status) {
            'paid' => 'Lunas / Aktif',
            'overdue' => 'Terlambat',
            'pending' => 'Belum Bayar',
            'unpaid' => 'Belum Bayar',
            default => ucfirst($this->status),
        };
    }

    public function getGoogleMapsLinkAttribute()
    {
        if ($this->customer && $this->customer->lat && $this->customer->lng) {
            return "https://www.google.com/maps?q={$this->customer->lat},{$this->customer->lng}";
        }
        return null;
    }
}