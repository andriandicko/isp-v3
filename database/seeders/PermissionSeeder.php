<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // Daftar Permission berdasarkan Modul yang ada di Route Anda
        $permissions = [
            // Dashboard
            'dashboard.index',

            // Manajemen User & Role
            'users.index', 'users.create', 'users.edit', 'users.delete',
            'roles.index', 'roles.create', 'roles.edit', 'roles.delete',

            // Master Data (Coverage, Package, Office, Shift)
            'coverage_areas.index', 'coverage_areas.create', 'coverage_areas.edit', 'coverage_areas.delete',
            'packages.index', 'packages.create', 'packages.edit', 'packages.delete',
            'offices.index', 'offices.create', 'offices.edit', 'offices.delete',
            'shifts.index', 'shifts.create', 'shifts.edit', 'shifts.delete',
            'user-shifts.index', 'user-shifts.create', 'user-shifts.edit', 'user-shifts.delete',

            // Operasional (Korlap, Customer, Ticket)
            'korlaps.index', 'korlaps.create', 'korlaps.edit', 'korlaps.delete',
            'customers.index', 'customers.create', 'customers.edit', 'customers.delete',
            'tickets.index', 'tickets.create', 'tickets.edit', 'tickets.delete', 'tickets.reply',

            // Keuangan (Billing, Payment)
            'billings.index', 'billings.create', 'billings.edit', 'billings.delete', 
            'billings.payment', 'billings.generate',
            'payments.index', 'payments.create', 'payments.edit', 'payments.delete',

            // Inventory & Warehouse
            'warehouse.index', 'warehouse.create', 'warehouse.edit', 'warehouse.delete',
            'item-category.index', 'item-category.create', 'item-category.edit', 'item-category.delete',
            'item.index', 'item.create', 'item.edit', 'item.delete',
            'supplier.index', 'supplier.create', 'supplier.edit', 'supplier.delete',
            
            // Goods Management (Incoming, Outgoing, Transfer)
            'incoming-goods.index', 'incoming-goods.create', 'incoming-goods.approve', 'incoming-goods.reject',
            'outgoing-goods.index', 'outgoing-goods.create', 'outgoing-goods.approve', 'outgoing-goods.reject',
            'warehouse-transfer.index', 'warehouse-transfer.create', 'warehouse-transfer.approve', 'warehouse-transfer.complete',
            
            // Laporan Stok
            'stock-report.index', 'stock-report.export',

            // Absensi & Cuti
            'attendance.index', 'attendance.checkin', 'attendance.checkout', 'attendance.recap',
            'leave.index', 'leave.create', 'leave.approve',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // --- AUTO ASSIGN KE ADMIN ---
        // Agar admin tidak terkunci, kita berikan semua permission ke role 'admin'
        $adminRole = Role::where('name', 'admin')->first();
        if ($adminRole) {
            $adminRole->givePermissionTo(Permission::all());
        }
    }
}