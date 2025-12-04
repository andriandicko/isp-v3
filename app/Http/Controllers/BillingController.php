<?php

namespace App\Http\Controllers;

use App\Models\Billing;
use App\Models\Customer;
use App\Models\CoverageArea;
use App\Models\Package;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BillingController extends Controller
{
    /**
     * Menampilkan daftar tagihan dengan filter
     */
    public function index(Request $request)
    {
        $query = Billing::with(['customer.user', 'package', 'coverageArea'])
            ->whereHas('customer'); // Pastikan customer masih ada

        // Filter by status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        // Search (Berdasarkan Kode Tagihan atau Nama Customer)
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('billing_code', 'like', "%{$search}%")
                    ->orWhereHas('customer', function ($q2) use ($search) {
                        $q2->where('company_name', 'like', "%{$search}%")
                            ->orWhere('contact_person', 'like', "%{$search}%");
                    });
            });
        }

        $billings = $query->latest()->paginate(10);

        return view('billings.index', compact('billings'));
    }

    /**
     * [BARU & PENTING] Generate Tagihan Massal untuk Bulan Ini
     * Biasanya dipanggil via tombol di Dashboard atau CronJob
     */
    public function generateBulk()
    {
        // 1. Ambil semua customer yang statusnya ACTIVE
        // Customer isolir atau inactive tidak dibuatkan tagihan baru
        $activeCustomers = Customer::where('status', 'active')->get();

        $count = 0;
        $month = Carbon::now()->month;
        $year = Carbon::now()->year;

        DB::beginTransaction();
        try {
            foreach ($activeCustomers as $customer) {
                // 2. Cek apakah tagihan bulan ini sudah ada?
                $exists = Billing::where('customer_id', $customer->id)
                    ->whereMonth('billing_date', $month)
                    ->whereYear('billing_date', $year)
                    ->exists();

                if (!$exists) {
                    // 3. Ambil harga paket
                    // Logic: Ambil paket dari relasi customer (jika ada), atau cari paket di area dia
                    // Disini kita asumsi ambil paket default dari area coverage
                    $package = Package::where('coverage_area_id', $customer->coverage_area_id)->first();

                    if ($package) {
                        Billing::create([
                            'billing_code' => Billing::generateBillingCode(),
                            'customer_id'  => $customer->id,
                            'package_id'   => $package->id,
                            'coverage_area_id' => $customer->coverage_area_id,
                            'billing_date' => Carbon::now(),
                            'start_date'   => Carbon::now()->startOfMonth(), // 01-Bulan-Ini
                            'end_date'     => Carbon::now()->endOfMonth(),   // 30-Bulan-Ini
                            'due_date'     => Carbon::now()->addDays(10),    // Jatuh tempo tgl 10
                            'amount'       => $package->price,
                            'status'       => 'unpaid',
                        ]);
                        $count++;
                    }
                }
            }
            DB::commit();
            return back()->with('success', "Berhasil generate $count tagihan untuk periode ini.");
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal generate bulk: ' . $e->getMessage());
        }
    }

    /**
     * Form Manual Create (Untuk tagihan susulan / instalasi baru)
     */
    public function create()
    {
        $customers = Customer::where('status', 'active')->get();
        $packages = Package::all();
        $coverageAreas = CoverageArea::all();

        return view('billings.create', compact('customers', 'packages', 'coverageAreas'));
    }

    public function store(Request $request)
    {
        // Validasi bersih dari file upload teknis
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'coverage_area_id' => 'required|exists:coverage_areas,id',
            'package_id' => 'required|exists:packages,id',
            'billing_date' => 'required|date',
            'amount' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            $validated['billing_code'] = Billing::generateBillingCode();

            // Set periode pemakaian
            $date = Carbon::parse($validated['billing_date']);
            $validated['start_date'] = $date->copy()->startOfMonth();
            $validated['end_date']   = $date->copy()->endOfMonth();
            $validated['due_date']   = $date->copy()->addDays(10); // Kebijakan jatuh tempo
            $validated['status']     = 'pending'; // Default unpaid

            Billing::create($validated);

            DB::commit();
            return redirect()->route('billings.index')->with('success', 'Tagihan manual berhasil dibuat.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function show(Billing $billing)
    {
        // Load customer beserta data teknisnya (mac_ont, foto_rumah ada di tabel customer)
        $billing->load(['customer.user', 'package', 'coverageArea']);
        return view('billings.show', compact('billing'));
    }

    public function edit(Billing $billing)
    {
        // Ambil semua data paket untuk dropdown
        $packages = \App\Models\Package::all();

        return view('billings.edit', compact('billing', 'packages'));
    }

    public function update(Request $request, Billing $billing)
    {
        $validated = $request->validate([
            'due_date' => 'required|date',
            'amount' => 'required|numeric|min:0',
            'status' => 'required|in:pending,paid,overdue',
            'notes' => 'nullable|string|max:500', // Tambahan catatan admin
        ]);

        DB::beginTransaction();
        try {
            // Update data tagihan
            $billing->update([
                'due_date' => $validated['due_date'],
                'amount' => $validated['amount'],
                'status' => $validated['status'],
                'notes' => $validated['notes'] ?? $billing->notes,
            ]);

            // LOGIC HOOK: Sinkronisasi Status Customer
            // Jika admin manual ubah status jadi 'paid', cek apakah perlu aktifkan internet?
            if ($validated['status'] == 'paid' && $billing->customer->status == 'isolir') {
                $billing->customer->update(['status' => 'active']);
            }

            // Jika admin manual ubah status jadi 'overdue', cek apakah perlu isolir?
            if ($validated['status'] == 'overdue' && $billing->customer->status == 'active') {
                $billing->customer->update(['status' => 'isolir']);
            }

            DB::commit();

            return redirect()->route('billings.index')
                ->with('success', 'Billing berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Gagal update: ' . $e->getMessage());
        }
    }

    /**
     * Form Bayar
     */
    public function payment(Billing $billing)
    {
        if ($billing->status == 'paid') {
            return back()->with('info', 'Tagihan ini sudah lunas.');
        }
        return view('billings.payment', compact('billing'));
    }

    /**
     * [KRUSIAL] Proses Pembayaran & Auto-Aktivasi Internet
     */
    public function processPayment(Request $request, Billing $billing)
    {
        $request->validate([
            'payment_method' => 'required|string',
            'months' => 'required|integer|min:1|max:12', // Validasi minimal 1 bulan, maksimal 12
            'notes' => 'nullable|string'
        ]);

        DB::beginTransaction();
        try {
            $totalMonths = (int) $request->months;
            $paidAmount = $billing->amount; // Harga per bulan

            // ---------------------------------------------------
            // 1. LUNASKAN TAGIHAN BULAN INI (CURRENT BILL)
            // ---------------------------------------------------
            $billing->update([
                'status' => 'paid',
                'paid_at' => now(),
                'payment_method' => $request->payment_method,
                'notes' => $request->notes . ($totalMonths > 1 ? " (Pembayaran 1 dari $totalMonths bulan)" : ""),
            ]);

            // Cek Auto-Aktivasi jika isolir
            if ($billing->customer->status == 'isolir') {
                $billing->customer->update(['status' => 'active']);
            }

            // ---------------------------------------------------
            // 2. GENERATE & LUNASKAN TAGIHAN MASA DEPAN (LOOPING)
            // ---------------------------------------------------
            // Jika user pilih lebih dari 1 bulan
            if ($totalMonths > 1) {
                // Ambil tanggal referensi dari tagihan saat ini
                $lastStartDate = Carbon::parse($billing->start_date);

                for ($i = 1; $i < $totalMonths; $i++) {
                    // Geser bulan ke depan
                    $nextStartDate = $lastStartDate->copy()->addMonths($i); // Bulan depan, depannya lagi, dst
                    $nextEndDate = $nextStartDate->copy()->endOfMonth();
                    $nextDueDate = $nextStartDate->copy()->addDays(10); // Tetap tgl 10 bulan depannya

                    Billing::create([
                        'billing_code' => Billing::generateBillingCode(),
                        'customer_id' => $billing->customer_id,
                        'coverage_area_id' => $billing->coverage_area_id,
                        'package_id' => $billing->package_id,

                        // Set Tanggal Masa Depan
                        'billing_date' => now(), // Dibuat hari ini
                        'start_date' => $nextStartDate,
                        'end_date' => $nextEndDate,
                        'due_date' => $nextDueDate,

                        'amount' => $paidAmount, // Asumsi harga paket sama

                        // LANGSUNG LUNAS
                        'status' => 'paid',
                        'paid_at' => now(),
                        'payment_method' => $request->payment_method,
                        'notes' => "Pembayaran dimuka (Bulan " . ($i + 1) . " dari $totalMonths). Ref Tagihan Awal: " . $billing->billing_code,
                    ]);
                }
            }

            DB::commit();
            return redirect()->route('billings.index')
                ->with('success', "Pembayaran untuk $totalMonths bulan berhasil diproses. Tagihan masa depan telah dibuat otomatis.");
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal: ' . $e->getMessage());
        }
    }

    /**
     * [KRUSIAL] Cek Jatuh Tempo & Auto-Isolir
     * Dijalankan harian (via Scheduler atau tombol admin)
     */
    public function checkOverdue()
    {
        $today = Carbon::now();

        DB::beginTransaction();
        try {
            // 1. Cari tagihan yang 'pending' DAN sudah lewat jatuh tempo
            $overdueBills = Billing::with('customer')
                ->where('status', 'pending')
                ->where('due_date', '<', $today)
                ->get();

            $count = 0;
            foreach ($overdueBills as $bill) {
                // Update tagihan jadi overdue
                $bill->update(['status' => 'overdue']);

                // Update customer jadi ISOLIR
                // Hanya jika statusnya masih active
                if ($bill->customer && $bill->customer->status == 'active') {
                    $bill->customer->update(['status' => 'isolir']);

                    // (TODO: Panggil Service Mikrotik untuk disable user)
                    // MikrotikService::isolateUser($bill->customer->pppoe_user);

                    $count++;
                }
            }

            DB::commit();
            return back()->with('success', "Proses selesai. $count pelanggan telah diisolir karena menunggak.");
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    public function destroy(Billing $billing)
    {
        // Soft delete tagihan saja, data customer aman
        $billing->delete();
        return redirect()->route('billings.index')->with('success', 'Tagihan dihapus.');
    }
}
