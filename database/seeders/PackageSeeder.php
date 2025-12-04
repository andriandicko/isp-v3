<?php

namespace Database\Seeders;

use App\Models\CoverageArea;
use App\Models\Package;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PackageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $coverageArea = CoverageArea::first(); // ambil area pertama

        Package::create([
            'coverage_area_id' => $coverageArea->id,
            'name' => 'Home 20 Mbps',
            'type' => 'residential',
            'speed' => '20 Mbps',
            'price' => 250000,
            'description' => 'Paket internet untuk rumah',
        ]);

        Package::create([
            'coverage_area_id' => $coverageArea->id,
            'name' => 'Biz 50 Mbps',
            'type' => 'business',
            'speed' => '50 Mbps',
            'price' => 750000,
            'description' => 'Paket internet untuk bisnis kecil',
        ]);
    }
}
