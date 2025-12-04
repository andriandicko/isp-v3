<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class CoverageArea extends Model
{
    //
    use SoftDeletes;

    protected $fillable = [
        'name',
        'boundary',
        'description',
    ];

    protected $casts = [
        'boundary' => 'string', // disimpan dalam format WKT (Well-Known Text)
    ];

    // Konversi ke GeoJSON otomatis saat diambil
    public function getBoundaryGeoJsonAttribute()
    {
        $result = DB::selectOne("SELECT ST_AsGeoJSON(boundary) as geojson FROM coverage_areas WHERE id = ?", [$this->id]);
        return $result ? json_decode($result->geojson) : null;
    }

    public function korlaps()
    {
        return $this->hasMany(Korlap::class);
    }
    public function customers()
    {
        return $this->hasMany(Customer::class);
    }
    public function packages()
    {
        return $this->hasMany(Package::class);
    }
}
