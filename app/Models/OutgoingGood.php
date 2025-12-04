<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OutgoingGood extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'transaction_code',
        'warehouse_id',
        'user_id',
        'transaction_date',
        'recipient_name',
        'department',
        'purpose',
        'notes',
        'status',
        'approved_by',
        'approved_at'
    ];

    protected $casts = [
        'transaction_date' => 'date',
        'approved_at' => 'datetime',
    ];

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function details()
    {
        // FIX: Tambahkan explicit foreign key
        return $this->hasMany(OutgoingGoodsDetail::class, 'outgoing_goods_id');
    }
}
