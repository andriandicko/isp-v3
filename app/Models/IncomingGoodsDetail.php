<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IncomingGoodsDetail extends Model
{
    protected $fillable = [
        'incoming_goods_id',  // ← Ini benar
        'item_id',
        'quantity',
        'price',
        'subtotal',
        'notes'
    ];

    protected $casts = [
        'quantity' => 'decimal:2',
        'price' => 'decimal:2',
        'subtotal' => 'decimal:2',
    ];

    public function incomingGoods()
    {
        return $this->belongsTo(IncomingGood::class, 'incoming_goods_id');
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
