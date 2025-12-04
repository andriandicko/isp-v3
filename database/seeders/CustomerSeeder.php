<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\CoverageArea;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $coverageArea = CoverageArea::first(); // Ambil area pertama

        // Buat user-nya dulu
        $user = User::create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'phone' => '081234567890',
            'password' => Hash::make('password'),
        ]);

        // Tambahkan role 'customer'
        $user->assignRole('customer');

        // Buat data customer terhubung ke user dan area
        Customer::create([
            'user_id' => $user->id,
            'coverage_area_id' => $coverageArea->id,
            'type' => 'residential',
            'address' => 'Jl. Contoh No.1',
            'lat' => -6.917464, // Contoh: Bandung
            'lng' => 107.619123,
        ]);
    }
}
