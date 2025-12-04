<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    //
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'coverage_area_id',
        'korlap_id',
        'type',
        'status',
        'company_name',
        'contact_person',
        'address',
        'lat',
        'lng',
        'no_odp',
        'mac_ont',
        'foto_ktp',
        'foto_rumah',
        'foto_redaman',
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function coverageArea()
    {
        return $this->belongsTo(CoverageArea::class);
    }
    public function korlap()
    {
        return $this->belongsTo(Korlap::class);
    }
    public function billings()
    {
        return $this->hasMany(Billing::class);
    }

    public function getGoogleMapsLinkAttribute()
    {
        if ($this->lat && $this->lng) {
            return "https://www.google.com/maps?q={$this->lat},{$this->lng}";
        }
        return null;
    }

    public function hasLocation()
    {
        return !empty($this->lat) && !empty($this->lng);
    }
}
