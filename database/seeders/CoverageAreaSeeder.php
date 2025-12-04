<?php

namespace Database\Seeders;

use App\Models\CoverageArea;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CoverageAreaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        DB::statement("
            INSERT INTO coverage_areas (name, description, boundary, created_at, updated_at)
            VALUES (
                'Area A',
                'Wilayah utama pelanggan residensial',
                ST_GeomFromText('POLYGON((110.4 -7.8, 110.5 -7.8, 110.5 -7.7, 110.4 -7.7, 110.4 -7.8))'),
                NOW(),
                NOW()
            )
        ");
    }
}
