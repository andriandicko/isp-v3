<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        Role::create(['name' => 'admin']);
        Role::create(['name' => 'finance']);
        Role::create(['name' => 'teknisi']);
        Role::create(['name' => 'staff']);
        Role::create(['name' => 'employee']);
        Role::create(['name' => 'noc']);
        Role::create(['name' => 'korlap']);
        Role::create(['name' => 'customer']);
    }
}
