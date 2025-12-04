<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Supplier extends Model
{
    //
    use SoftDeletes;

    protected $fillable = [
        'code',
        'name',
        'address',
        'phone',
        'email',
        'contact_person',
        'status'
    ];

    public function incomingGoods()
    {
        return $this->hasMany(IncomingGood::class);
    }
}
