<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Billing;
use App\Models\Customer;
use App\Models\Package;
use App\Models\CoverageArea;
use Carbon\Carbon;

class BillingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Pastikan ada data customer, package, dan coverage area
        $customer = Customer::first();
        $package = Package::first();
        $coverageArea = CoverageArea::first();

        if (!$customer || !$package || !$coverageArea) {
            $this->command->warn('Silakan seed Customer, Package, dan CoverageArea terlebih dahulu!');
            return;
        }

        // Billing yang akan jatuh tempo dalam 5 hari (harus muncul tombol bayar)
        Billing::create([
            'billing_code' => 'BIL-' . strtoupper(uniqid()),
            'customer_id' => $customer->id,
            'coverage_area_id' => $coverageArea->id,
            'package_id' => $package->id,
            'billing_date' => Carbon::now()->subDays(25),
            'start_date' => Carbon::now()->subDays(25),
            'due_date' => Carbon::now()->addDays(5),
            'amount' => 150000,
            'status' => 'pending',
        ]);

        // Billing yang sudah jatuh tempo 10 hari (tombol bayar merah)
        Billing::create([
            'billing_code' => 'BIL-' . strtoupper(uniqid()),
            'customer_id' => $customer->id,
            'coverage_area_id' => $coverageArea->id,
            'package_id' => $package->id,
            'billing_date' => Carbon::now()->subDays(40),
            'start_date' => Carbon::now()->subDays(40),
            'due_date' => Carbon::now()->subDays(10),
            'amount' => 150000,
            'status' => 'overdue',
        ]);

        // Billing yang sudah lunas
        Billing::create([
            'billing_code' => 'BIL-' . strtoupper(uniqid()),
            'customer_id' => $customer->id,
            'coverage_area_id' => $coverageArea->id,
            'package_id' => $package->id,
            'billing_date' => Carbon::now()->subMonths(2),
            'start_date' => Carbon::now()->subMonths(2),
            'due_date' => Carbon::now()->subMonths(2)->addDays(30),
            'amount' => 150000,
            'status' => 'paid',
        ]);

        // Billing yang masih jauh dari jatuh tempo (tidak ada tombol bayar)
        Billing::create([
            'billing_code' => 'BIL-' . strtoupper(uniqid()),
            'customer_id' => $customer->id,
            'coverage_area_id' => $coverageArea->id,
            'package_id' => $package->id,
            'billing_date' => Carbon::now()->subDays(5),
            'start_date' => Carbon::now()->subDays(5),
            'due_date' => Carbon::now()->addDays(25),
            'amount' => 150000,
            'status' => 'pending',
        ]);

        $this->command->info('Billing seeder berhasil dijalankan!');
    }
}
