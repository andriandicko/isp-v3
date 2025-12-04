<?php

namespace App\Http\Controllers;

use App\Models\Korlap;
use App\Models\User;
use App\Models\CoverageArea;
use Illuminate\Http\Request;

class KorlapController extends Controller
{
    /**
     * Tampilkan daftar korlap
     */
    public function index()
    {
        $korlaps = Korlap::with(['user', 'coverageArea'])->latest()->get();
        return view('korlaps.index', compact('korlaps'));
    }

    /**
     * Form tambah korlap
     */
    public function create()
    {
        // PENTING: Filter hanya user dengan role 'korlap' menggunakan Spatie Permission
        $users = User::role('korlap')
            ->select('id', 'name', 'email')
            ->whereNotIn('id', Korlap::pluck('user_id')) // Exclude user yang sudah jadi korlap
            ->orderBy('name')
            ->get();

        $areas = CoverageArea::select('id', 'name')->orderBy('name')->get();

        return view('korlaps.create', compact('users', 'areas'));
    }

    /**
     * Simpan data korlap baru
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id|unique:korlaps,user_id',
            'coverage_area_id' => 'required|exists:coverage_areas,id',
        ], [
            'user_id.required' => 'User harus dipilih',
            'user_id.exists' => 'User tidak ditemukan',
            'user_id.unique' => 'User ini sudah terdaftar sebagai korlap',
            'coverage_area_id.required' => 'Area cakupan harus dipilih',
            'coverage_area_id.exists' => 'Area cakupan tidak ditemukan',
        ]);

        // Validasi tambahan: pastikan user memiliki role 'korlap'
        $user = User::findOrFail($validated['user_id']);
        if (!$user->hasRole('korlap')) {
            return back()
                ->withErrors(['user_id' => 'User yang dipilih tidak memiliki role Korlap'])
                ->withInput();
        }

        Korlap::create($validated);

        return redirect()
            ->route('korlaps.index')
            ->with('success', 'Korlap berhasil ditambahkan.');
    }

    /**
     * Form edit korlap
     */
    public function edit(Korlap $korlap)
    {
        // Filter user dengan role 'korlap', termasuk user yang sedang diedit
        $users = User::role('korlap')
            ->select('id', 'name', 'email')
            ->where(function ($query) use ($korlap) {
                $query->whereNotIn('id', Korlap::pluck('user_id'))
                    ->orWhere('id', $korlap->user_id); // Include current user
            })
            ->orderBy('name')
            ->get();

        $areas = CoverageArea::select('id', 'name')->orderBy('name')->get();

        return view('korlaps.edit', compact('korlap', 'users', 'areas'));
    }

    /**
     * Update korlap
     */
    public function update(Request $request, Korlap $korlap)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id|unique:korlaps,user_id,' . $korlap->id,
            'coverage_area_id' => 'required|exists:coverage_areas,id',
        ], [
            'user_id.required' => 'User harus dipilih',
            'user_id.exists' => 'User tidak ditemukan',
            'user_id.unique' => 'User ini sudah terdaftar sebagai korlap',
            'coverage_area_id.required' => 'Area cakupan harus dipilih',
            'coverage_area_id.exists' => 'Area cakupan tidak ditemukan',
        ]);

        // Validasi tambahan: pastikan user memiliki role 'korlap'
        $user = User::findOrFail($validated['user_id']);
        if (!$user->hasRole('korlap')) {
            return back()
                ->withErrors(['user_id' => 'User yang dipilih tidak memiliki role Korlap'])
                ->withInput();
        }

        $korlap->update($validated);

        return redirect()
            ->route('korlaps.index')
            ->with('success', 'Data korlap berhasil diperbarui.');
    }

    /**
     * Hapus korlap
     */
    public function destroy(Korlap $korlap)
    {
        $korlap->delete();

        return redirect()
            ->route('korlaps.index')
            ->with('success', 'Korlap berhasil dihapus.');
    }
}
