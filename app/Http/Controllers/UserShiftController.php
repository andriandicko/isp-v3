<?php

namespace App\Http\Controllers;

use App\Models\Office;
use App\Models\Shift;
use App\Models\User;
use App\Models\UserShift;
use Illuminate\Http\Request;

class UserShiftController extends Controller
{
    public function index()
    {
        $userShifts = UserShift::with(['user', 'shift', 'office'])
            ->orderBy('user_id')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('user-shifts.index', compact('userShifts'));
    }

    public function create()
    {
        $users = User::orderBy('name')->get();
        $shifts = Shift::where('is_active', true)->orderBy('name')->get();
        $offices = Office::where('is_active', true)->orderBy('name')->get();

        return view('user-shifts.create', compact('users', 'shifts', 'offices'));
    }

   public function store(Request $request)
    {
        $request->validate([
            'user_id'        => 'required|exists:users,id',
            'shift_id'       => 'required|exists:shifts,id',
            'office_ids'     => 'required|array|min:1', // Wajib array
            'office_ids.*'   => 'exists:offices,id',    // Tiap item harus valid
            'effective_date' => 'required|date',
        ]);

        // Loop untuk menyimpan setiap kantor yang dipilih
        foreach ($request->office_ids as $officeId) {
            
            // Opsional: Cek duplikasi agar tidak double entry untuk user+shift+office yang sama
            $exists = UserShift::where('user_id', $request->user_id)
                        ->where('shift_id', $request->shift_id)
                        ->where('office_id', $officeId)
                        ->where('is_active', true)
                        ->exists();
            
            if (!$exists) {
                UserShift::create([
                    'user_id'        => $request->user_id,
                    'shift_id'       => $request->shift_id,
                    'office_id'      => $officeId, // Ambil dari loop
                    'effective_date' => $request->effective_date,
                    'is_active'      => $request->has('is_active'),
                ]);
            }
        }

        return redirect()->route('user-shifts.index')->with('success', 'Jadwal berhasil ditambahkan ke ' . count($request->office_ids) . ' kantor.');
    }

    public function edit(UserShift $userShift)
    {
        $users = User::orderBy('name')->get();
        $shifts = Shift::where('is_active', true)->orderBy('name')->get();
        $offices = Office::where('is_active', true)->orderBy('name')->get();

        return view('user-shifts.edit', compact('userShift', 'users', 'shifts', 'offices'));
    }

    public function update(Request $request, UserShift $userShift)
    {
        $request->validate([
            'user_id'        => 'required|exists:users,id',
            'shift_id'       => 'required|exists:shifts,id',
            // Ubah validasi office_id menjadi office_ids (array)
            'office_ids'     => 'required|array|min:1',
            'office_ids.*'   => 'exists:offices,id',
            'effective_date' => 'required|date',
        ]);

        // Ambil array kantor yang dipilih
        $offices = $request->office_ids;

        // 1. UPDATE DATA YANG SEDANG DIEDIT DENGAN KANTOR PERTAMA
        // Kita ambil kantor pertama dari array untuk mengupdate data ini
        $firstOfficeId = array_shift($offices); // Ambil dan hapus elemen pertama

        $userShift->update([
            'user_id'        => $request->user_id,
            'shift_id'       => $request->shift_id,
            'office_id'      => $firstOfficeId, // Update jadi kantor pertama
            'effective_date' => $request->effective_date,
            'is_active'      => $request->has('is_active'),
        ]);

        // 2. JIKA ADA KANTOR LAIN YANG DIPILIH, BUAT DATA BARU (CREATE)
        // Sisa array $offices akan diloop
        foreach ($offices as $officeId) {
            // Cek duplikasi biar tidak spam data kembar
            $exists = UserShift::where('user_id', $request->user_id)
                ->where('shift_id', $request->shift_id)
                ->where('office_id', $officeId)
                ->where('is_active', true)
                ->exists();

            if (!$exists) {
                UserShift::create([
                    'user_id'        => $request->user_id,
                    'shift_id'       => $request->shift_id,
                    'office_id'      => $officeId,
                    'effective_date' => $request->effective_date,
                    'is_active'      => $request->has('is_active'),
                ]);
            }
        }

        return redirect()->route('user-shifts.index')->with('success', 'Jadwal berhasil diperbarui (dan ditambahkan jika memilih banyak kantor).');
    }

    public function destroy(UserShift $userShift)
    {
        $userShift->delete();
        return redirect()->route('user-shifts.index')->with('success', 'User shift berhasil dihapus!');
    }

    // Toggle active status
    public function toggleActive(UserShift $userShift)
    {
        // Overlap validate
        // if (!$userShift->is_active) {
        //     $thisShift = $userShift->shift;

        //     $existingShifts = UserShift::where('user_id', $userShift->user_id)
        //         ->where('id', '!=', $userShift->id)
        //         ->where('is_active', true)
        //         ->with('shift')
        //         ->get();

        //     foreach ($existingShifts as $existing) {
        //         $overlappingDays = array_intersect($existing->shift->days, $thisShift->days);

        //         if (!empty($overlappingDays)) {
        //             $daysString = implode(', ', array_map('ucfirst', $overlappingDays));
        //             return back()->with('error', "Tidak dapat mengaktifkan shift ini karena ada overlap di hari: {$daysString}");
        //         }
        //     }
        // }

        $userShift->update([
            'is_active' => !$userShift->is_active
        ]);

        $status = $userShift->is_active ? 'diaktifkan' : 'dinonaktifkan';
        return back()->with('success', "User shift berhasil {$status}!");
    }
}
