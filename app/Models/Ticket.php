<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_code',
        'customer_id',
        'user_id', // Teknisi / Admin yang menangani
        'subject',
        'description',
        'priority', // low, medium, high, critical
        'status',   // open, in_progress, resolved, closed
        'photo',
        'resolved_at'
    ];

    protected $casts = [
        'resolved_at' => 'datetime',
    ];

    // Relasi ke Customer (Pelapor)
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    // Relasi ke User (Teknisi)
    public function user() // Bisa diganti nama functionnya jadi 'technician' jika mau
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Helper: Warna Badge Prioritas
    public function getPriorityBadgeClass()
    {
        return match ($this->priority) {
            'critical' => 'bg-red-100 text-red-800 border-red-200',
            'high'     => 'bg-orange-100 text-orange-800 border-orange-200',
            'medium'   => 'bg-yellow-100 text-yellow-800 border-yellow-200',
            'low'      => 'bg-blue-100 text-blue-800 border-blue-200',
            default    => 'bg-gray-100 text-gray-800',
        };
    }

    // Helper: Warna Badge Status
    public function getStatusBadgeClass()
    {
        return match ($this->status) {
            'open'        => 'bg-red-500 text-white',
            'in_progress' => 'bg-blue-500 text-white',
            'resolved'    => 'bg-green-500 text-white',
            'closed'      => 'bg-gray-500 text-white',
            default       => 'bg-gray-400 text-white',
        };
    }

    // Helper: Label Status yang enak dibaca
    public function getStatusLabel()
    {
        return match ($this->status) {
            'in_progress' => 'Sedang Dikerjakan',
            'resolved'    => 'Selesai (Resolved)',
            'closed'      => 'Ditutup',
            default       => ucfirst($this->status),
        };
    }

    public function replies()
    {
        return $this->hasMany(TicketReply::class)->orderBy('created_at', 'asc'); // Chat urut dari lama ke baru
    }
}
