<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Warehouse extends Model
{
    //
    use SoftDeletes;

    protected $fillable = [
        'code',
        'name',
        'address',
        'city',
        'province',
        'phone',
        'manager_name',
        'status'
    ];

    public function stocks()
    {
        return $this->hasMany(WarehouseStock::class);
    }

    public function incomingGoods()
    {
        return $this->hasMany(IncomingGood::class);
    }

    public function outgoingGoods()
    {
        return $this->hasMany(OutgoingGood::class);
    }
}
