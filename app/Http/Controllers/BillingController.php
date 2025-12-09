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
     * Helper Penting: Hitung Tanggal Berakhir (Cut-Off Cycle)
     * Aturan:
     * - Start Tgl 1-15  -> End Date tgl 10 bulan depan
     * - Start Tgl 16-31 -> End Date akhir bulan depan
     */
    private function calculateEndDate($startDate, $months = 1)
    {
        $date = Carbon::parse($startDate);
        $day = $date->day;
        
        // Tambahkan bulan sesuai jumlah pembayaran (default 1)
        $targetDate = $date->copy()->addMonths($months);

        if ($day >= 1 && $day <= 15) {
            // Siklus 1: Jatuh tempo tgl 10
            return $targetDate->day(10);
        } else {
            // Siklus 2: Jatuh tempo Akhir Bulan
            return $targetDate->endOfMonth();
        }
    }

    public function index(Request $request)
    {
        $query = Billing::with(['customer.user', 'package', 'coverageArea'])
            ->whereHas('customer');

        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

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
     * Create Billing Awal (Instalasi Baru / Manual)
     */
    public function store(Request $request)
    {
        // 1. Handle Paket Custom
        if ($request->package_id === 'custom') {
            $request->merge(['package_id' => null]);
        }

        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'coverage_area_id' => 'nullable|exists:coverage_areas,id',
            'package_id' => 'nullable|exists:packages,id',
            'billing_date' => 'required|date',
            'amount' => 'required|numeric|min:0',
            'notes' => 'nullable|string|max:255', 
        ]);

        DB::beginTransaction();
        try {
            $validated['billing_code'] = Billing::generateBillingCode();

            if (empty($validated['package_id']) && empty($validated['notes'])) {
                $validated['notes'] = 'Paket Custom (Manual Price)';
            }

            // 2. Set Tanggal Berdasarkan Logika Cut-Off
            $billDate = Carbon::parse($validated['billing_date']);
            
            $validated['start_date'] = $billDate;
            
            // Batas bayar tagihan pertama: 3 hari dari pembuatan
            $validated['due_date'] = $billDate->copy()->addDays(3);
            
            // Hitung End Date otomatis sesuai siklus (1-15 / 16-31)
            $validated['end_date'] = $this->calculateEndDate($billDate, 1);
            
            $validated['status'] = 'pending';

            Billing::create($validated);

            DB::commit();
            return redirect()->route('billings.index')->with('success', 'Tagihan berhasil dibuat. Jatuh tempo dalam 3 hari.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    /**
     * Proses Pembayaran & Perpanjangan (Single Billing)
     */
    public function processPayment(Request $request, Billing $billing)
    {
        $request->validate([
            'payment_method' => 'required|string',
            'months' => 'required|integer|min:1|max:12',
            'notes' => 'nullable|string'
        ]);

        DB::beginTransaction();
        try {
            $totalMonths = (int) $request->months;
            $newTotalAmount = $billing->amount * $totalMonths; // Harga Paket * Bulan

            // --- HITUNG MASA AKTIF BARU ---
            
            if ($billing->status == 'pending') {
                // KASUS 1: Pembayaran Pertama
                // Hitung dari Start Date awal menggunakan siklus yang sama
                $startDate = Carbon::parse($billing->start_date);
                $newEndDate = $this->calculateEndDate($startDate, $totalMonths);
            
            } else {
                // KASUS 2: Perpanjangan (Renewal)
                // Lanjutkan dari End Date terakhir
                $currentEndDate = Carbon::parse($billing->end_date);
                
                // Cek siklus aslinya dari start_date awal tagihan
                // Agar konsisten: Tgl 10 tetap tgl 10, Akhir bulan tetap akhir bulan
                $originalStartDay = Carbon::parse($billing->start_date)->day;
                
                // Tambah bulan ke end date sekarang
                $newEndDate = $currentEndDate->copy()->addMonths($totalMonths);

                // Koreksi tanggal hari-nya
                if ($originalStartDay >= 1 && $originalStartDay <= 15) {
                    $newEndDate->day(10); // Paksa ke tanggal 10
                } else {
                    $newEndDate->endOfMonth(); // Paksa ke akhir bulan
                }
            }
            // ------------------------------

            $billing->update([
                'status' => 'paid',
                'paid_at' => Carbon::now(),
                'payment_method' => $request->payment_method,
                
                'amount' => $newTotalAmount, 
                'end_date' => $newEndDate, // Update masa aktif
                
                'notes' => $request->notes . ($totalMonths > 1 ? " (Lunas $totalMonths bulan s/d " . $newEndDate->format('d M Y') . ")" : ""),
            ]);

            // Buka Isolir
            if ($billing->customer->status == 'isolir') {
                $billing->customer->update(['status' => 'active']);
            }

            DB::commit();
            
            return redirect()->route('billings.index')
                ->with('success', "Pembayaran Berhasil! Masa aktif diperpanjang hingga " . $newEndDate->format('d F Y'));
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal: ' . $e->getMessage());
        }
    }

    /**
     * Cek Status Overdue & Isolir (Harian)
     */
    public function checkOverdue()
    {
        $today = Carbon::now();
        DB::beginTransaction();
        try {
            $countExpired = 0;
            $countIsolir = 0;

            // 1. CEK MASA AKTIF HABIS (Paid -> Overdue)
            // Jika status 'paid' TAPI sudah melewati 'end_date'
            // Maka ubah jadi 'overdue' dan beri waktu 5 hari (due_date baru)
            $expiredBillings = Billing::where('status', 'paid')
                ->where('end_date', '<', $today)
                ->get();

            foreach ($expiredBillings as $bill) {
                $bill->update([
                    'status' => 'overdue',
                    // Beri nafas 5 hari dari tanggal expired
                    'due_date' => Carbon::parse($bill->end_date)->addDays(5) 
                ]);
                $countExpired++;
            }

            // 2. CEK JATUH TEMPO (Pending/Overdue -> Isolir)
            // Jika lewat due_date belum bayar juga -> ISOLIR
            $overdueBills = Billing::with('customer')
                ->whereIn('status', ['pending', 'overdue'])
                ->where('due_date', '<', $today)
                ->get();

            foreach ($overdueBills as $bill) {
                // Pastikan status billing overdue
                if ($bill->status != 'overdue') {
                    $bill->update(['status' => 'overdue']);
                }

                // Isolir Customer jika masih aktif
                if ($bill->customer && $bill->customer->status == 'active') {
                    $bill->customer->update(['status' => 'isolir']);
                    // TODO: Script Mikrotik Isolir disini
                    $countIsolir++;
                }
            }

            DB::commit();
            return back()->with('success', "Proses selesai. $countExpired tagihan masuk masa tenggang, $countIsolir pelanggan diisolir.");
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal: ' . $e->getMessage());
        }
    }

    // --- CRUD Lainnya ---

    public function create()
    {
        $customers = Customer::where('status', 'active')->with('user')->get();
        $packages = Package::all();
        $coverageAreas = CoverageArea::all();
        return view('billings.create', compact('customers', 'packages', 'coverageAreas'));
    }

    public function show(Billing $billing)
    {
        $billing->load(['customer.user', 'package', 'coverageArea']);
        return view('billings.show', compact('billing'));
    }

    public function edit(Billing $billing)
    {
        $packages = Package::all();
        return view('billings.edit', compact('billing', 'packages'));
    }

    public function update(Request $request, Billing $billing)
    {
        $validated = $request->validate([
            'due_date' => 'required|date',
            'amount' => 'required|numeric|min:0',
            'status' => 'required|in:pending,paid,overdue',
            'notes' => 'nullable|string|max:500',
        ]);

        $billing->update($validated);
        
        // Manual Status Override
        if ($validated['status'] == 'paid' && $billing->customer->status == 'isolir') {
            $billing->customer->update(['status' => 'active']);
        }
        if ($validated['status'] == 'overdue' && $billing->customer->status == 'active') {
            $billing->customer->update(['status' => 'isolir']);
        }

        return redirect()->route('billings.index')->with('success', 'Billing diperbarui.');
    }

    public function payment(Billing $billing)
    {
        if ($billing->status == 'paid' && !$billing->shouldShowPayButton()) {
             return back()->with('info', 'Tagihan ini belum masuk masa pembayaran (H-10).');
        }
        return view('billings.payment', compact('billing'));
    }

    public function destroy(Billing $billing)
    {
        $billing->delete();
        return redirect()->route('billings.index')->with('success', 'Tagihan dihapus.');
    }
}