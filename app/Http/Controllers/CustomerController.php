<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\User;
use App\Models\CoverageArea;
use App\Models\Korlap;
use App\Http\Requests\StoreCustomerRequest;
use App\Http\Requests\UpdateCustomerRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $query = Customer::with(['user', 'coverageArea', 'korlap']);

        // 1. Filter Pencarian
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', function ($u) use ($search) {
                    $u->where('name', 'like', "%{$search}%");
                })
                    ->orWhere('company_name', 'like', "%{$search}%")
                    ->orWhere('contact_person', 'like', "%{$search}%")
                    ->orWhere('no_odp', 'like', "%{$search}%");
            });
        }

        // 2. Filter Status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        $customers = $query->latest()->paginate(10);

        return view('customers.index', compact('customers'));
    }

    public function create()
    {
        $users = User::all();
        // Pastikan dropdown manual juga tidak memuat data sampah
        $coverageAreas = CoverageArea::whereNull('deleted_at')->get(); 
        $korlaps = Korlap::all();

        return view('customers.create', compact('users', 'coverageAreas', 'korlaps'));
    }

    public function store(StoreCustomerRequest $request)
    {
        $validated = $request->validated();

        // Pastikan jika coverage_area_id tidak ada di request (karena disabled), set jadi null secara eksplisit
        if (!array_key_exists('coverage_area_id', $validated)) {
            $validated['coverage_area_id'] = null;
        }
        
        // Logika korlap null jika area tidak ada
        if (empty($validated['coverage_area_id'])) {
             $validated['korlap_id'] = null;
        }

        try {
            DB::beginTransaction();

            // Handle Uploads
            if ($request->hasFile('foto_rumah')) {
                $validated['foto_rumah'] = $request->file('foto_rumah')->store('customers/rumah', 'public');
            }
            if ($request->hasFile('foto_ktp')) {
                $validated['foto_ktp'] = $request->file('foto_ktp')->store('customers/ktp', 'public');
            }
            if ($request->hasFile('foto_redaman')) {
                $validated['foto_redaman'] = $request->file('foto_redaman')->store('customers/redaman', 'public');
            }

            Customer::create($validated);

            DB::commit();
            return redirect()->route('customers.index')->with('success', 'Customer berhasil ditambahkan');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', 'Gagal: ' . $e->getMessage());
        }
    }

    public function show(Customer $customer)
    {
        $customer->load(['user', 'coverageArea', 'korlap', 'billings']);

        return view('customers.show', compact('customer'));
    }

    public function edit(Customer $customer)
    {
        $users = User::all();
        $coverageAreas = CoverageArea::all();
        $korlaps = Korlap::all();

        return view('customers.edit', compact('customer', 'users', 'coverageAreas', 'korlaps'));
    }

    public function update(UpdateCustomerRequest $request, Customer $customer)
    {
        $validated = $request->validated();

        try {
            DB::beginTransaction();

            // Logic Upload Foto
            if ($request->hasFile('foto_rumah')) {
                if ($customer->foto_rumah) \Illuminate\Support\Facades\Storage::disk('public')->delete($customer->foto_rumah);
                $validated['foto_rumah'] = $request->file('foto_rumah')->store('customers/rumah', 'public');
            }

            if ($request->hasFile('foto_ktp')) {
                if ($customer->foto_ktp) \Illuminate\Support\Facades\Storage::disk('public')->delete($customer->foto_ktp);
                $validated['foto_ktp'] = $request->file('foto_ktp')->store('customers/ktp', 'public');
            }

            if ($request->hasFile('foto_redaman')) {
                if ($customer->foto_redaman) \Illuminate\Support\Facades\Storage::disk('public')->delete($customer->foto_redaman);
                $validated['foto_redaman'] = $request->file('foto_redaman')->store('customers/redaman', 'public');
            }

            $customer->update($validated);

            DB::commit();

            return redirect()->route('customers.index')->with('success', 'Customer berhasil diupdate');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', 'Gagal update: ' . $e->getMessage());
        }
    }

    public function destroy(Customer $customer)
    {
        try {
            $customer->delete();

            return redirect()->route('customers.index')
                ->with('success', 'Customer berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal menghapus customer: ' . $e->getMessage());
        }
    }

    /**
     * Check if a location is covered by any coverage area
     */
    public function checkCoverage(Request $request)
    {
        try {
            $lat = $request->input('lat');
            $lng = $request->input('lng');

            if (!$lat || !$lng) {
                return response()->json(['covered' => false], 400);
            }

            // Format WKT: POINT(lng lat)
            // Ingat: Urutan standar GIS adalah Longitude dulu, baru Latitude
            $pointWKT = "POINT($lng $lat)";

            // QUERY RAW YANG LEBIH AMAN UNTUK MARIADB
            // Kita gunakan ST_Contains langsung tanpa memaksa ST_SRID
            // Asumsinya data di DB sudah disimpan dengan benar
            $area = DB::table('coverage_areas')
                ->select('id', 'name')
                ->whereNotNull('boundary')
                
                // --- PERBAIKAN UTAMA DISINI ---
                // Tambahkan filter ini agar data Soft Delete tidak ikut terpanggil
                ->whereNull('deleted_at') 
                // ------------------------------

                ->whereRaw("ST_Contains(boundary, ST_GeomFromText(?))", [$pointWKT])
                ->first();

            if ($area) {
                // Untuk Korlap, kita pakai Eloquent jadi aman (soft delete otomatis terfilter)
                $korlap = Korlap::where('coverage_area_id', $area->id)->with('user')->first();

                return response()->json([
                    'covered' => true,
                    'coverage_area' => [
                        'id' => $area->id,
                        'name' => $area->name
                    ],
                    'korlap' => $korlap ? [
                        'id' => $korlap->id,
                        'name' => $korlap->user->name
                    ] : null
                ]);
            }

            return response()->json(['covered' => false]);

        } catch (\Exception $e) {
            Log::error("Check Coverage Error: " . $e->getMessage());
            
            return response()->json([
                'error' => true, 
                'message' => 'Gagal cek lokasi: ' . $e->getMessage()
            ], 500);
        }
    }
}