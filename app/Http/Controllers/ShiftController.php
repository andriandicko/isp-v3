<?php

namespace App\Http\Controllers;

use App\Models\Shift;
use Illuminate\Http\Request;

class ShiftController extends Controller
{
    public function index()
    {
        $shifts = Shift::orderBy('name')->paginate(10);
        return view('shifts.index', compact('shifts'));
    }

    public function create()
    {
        return view('shifts.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'start_time' => 'required|date_format:H:i',
            // VALIDASI DIPERBAIKI: Menghapus 'after:start_time' agar support shift malam
            'end_time' => 'required|date_format:H:i',
            'days' => 'required|array|min:1',
            'days.*' => 'in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
        ]);

        Shift::create([
            'name' => $request->name,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'days' => $request->days,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('shifts.index')->with('success', 'Shift berhasil ditambahkan!');
    }

    public function edit(Shift $shift)
    {
        return view('shifts.edit', compact('shift'));
    }

    public function update(Request $request, Shift $shift)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'start_time' => 'required|date_format:H:i',
            // VALIDASI DIPERBAIKI: Menghapus 'after:start_time' agar support shift malam
            'end_time' => 'required|date_format:H:i',
            'days' => 'required|array|min:1',
            'days.*' => 'in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
        ]);

        $shift->update([
            'name' => $request->name,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'days' => $request->days,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('shifts.index')->with('success', 'Shift berhasil diupdate!');
    }

    public function destroy(Shift $shift)
    {
        // Check if shift is being used
        if ($shift->userShifts()->count() > 0) {
            return back()->with('error', 'Shift tidak dapat dihapus karena masih digunakan oleh karyawan!');
        }

        $shift->delete();
        return redirect()->route('shifts.index')->with('success', 'Shift berhasil dihapus!');
    }
}