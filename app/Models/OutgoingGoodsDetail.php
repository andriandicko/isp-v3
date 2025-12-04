<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OutgoingGoodsDetail extends Model
{
    protected $fillable = [
        'outgoing_goods_id',  // ← Pastikan ini benar
        'item_id',
        'quantity',
        'notes'
    ];

    protected $casts = [
        'quantity' => 'decimal:2',
    ];

    public function outgoingGoods()
    {
        // FIX: Tambahkan explicit foreign key
        return $this->belongsTo(OutgoingGood::class, 'outgoing_goods_id');
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
