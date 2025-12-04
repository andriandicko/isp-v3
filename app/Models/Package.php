<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Package extends Model
{
    //
    use SoftDeletes;

    protected $fillable = [
        'coverage_area_id',
        'name',
        'type',
        'speed',
        'price',
        'description',
    ];

    public function coverageArea()
    {
        return $this->belongsTo(CoverageArea::class);
    }
    public function billings()
    {
        return $this->hasMany(Billing::class);
    }
}
