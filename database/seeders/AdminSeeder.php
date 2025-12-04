<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        // Pastikan role admin sudah ada
        $adminRole = Role::firstOrCreate(['name' => 'admin']);

        // Buat akun admin dummy
        $admin = User::firstOrCreate(
            ['email' => 'admin@spdlink.net'],
            [
                'name' => 'Admin',
                'phone' => '089876543210',
                'password' => Hash::make('password'),
            ]
        );

        // Beri role admin
        $admin->assignRole($adminRole);
    }
}
