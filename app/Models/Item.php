<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Item extends Model
{
    //
    use SoftDeletes;

    protected $fillable = [
        'category_id',
        'code',
        'name',
        'description',
        'unit',
        'minimum_stock',
        'price',
        'status'
    ];

    protected $casts = [
        'minimum_stock' => 'decimal:2',
        'price' => 'decimal:2',
    ];

    public function category()
    {
        return $this->belongsTo(ItemCategory::class, 'category_id');
    }

    public function warehouseStocks()
    {
        return $this->hasMany(WarehouseStock::class);
    }

    public function getTotalStockAttribute()
    {
        return $this->warehouseStocks()->sum('quantity');
    }
}
