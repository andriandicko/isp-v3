<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class IncomingGood extends Model
{
    //
    use SoftDeletes;

    protected $fillable = [
        'transaction_code',
        'warehouse_id',
        'supplier_id',
        'user_id',
        'transaction_date',
        'invoice_number',
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

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
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
        return $this->hasMany(IncomingGoodsDetail::class, 'incoming_goods_id');
    }

    public function getTotalAmountAttribute()
    {
        return $this->details()->sum('subtotal');
    }
}
