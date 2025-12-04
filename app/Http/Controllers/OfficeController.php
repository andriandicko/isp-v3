<?php

namespace App\Http\Controllers;

use App\Models\Office;
use Illuminate\Http\Request;

class OfficeController extends Controller
{
    public function index()
    {
        $offices = Office::orderBy('name')->paginate(10);
        return view('offices.index', compact('offices'));
    }

    public function create()
    {
        return view('offices.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:500',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'radius' => 'required|integer|min:10|max:5000',
        ]);

        Office::create([
            'name' => $request->name,
            'address' => $request->address,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'radius' => $request->radius,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('offices.index')->with('success', 'Office berhasil ditambahkan!');
    }

    public function edit(Office $office)
    {
        return view('offices.edit', compact('office'));
    }

    public function update(Request $request, Office $office)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:500',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'radius' => 'required|integer|min:10|max:5000',
        ]);

        $office->update([
            'name' => $request->name,
            'address' => $request->address,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'radius' => $request->radius,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('offices.index')->with('success', 'Office berhasil diupdate!');
    }

    public function destroy(Office $office)
    {
        // Check if office is being used
        if ($office->userShifts()->count() > 0) {
            return back()->with('error', 'Office tidak dapat dihapus karena masih digunakan oleh karyawan!');
        }

        $office->delete();
        return redirect()->route('offices.index')->with('success', 'Office berhasil dihapus!');
    }
}
