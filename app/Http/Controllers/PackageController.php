<?php

namespace App\Http\Controllers;

use App\Models\Package;
use App\Models\CoverageArea;
use Illuminate\Http\Request;

class PackageController extends Controller
{
    /**
     * Tampilkan daftar package.
     */
    public function index()
    {
        $packages = Package::with('coverageArea')->latest()->get();
        return view('packages.index', compact('packages'));
    }

    /**
     * Form tambah package.
     */
    public function create()
    {
        $coverageAreas = CoverageArea::select('id', 'name')->get();
        return view('packages.create', compact('coverageAreas'));
    }

    /**
     * Simpan data package baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'coverage_area_id' => 'required|exists:coverage_areas,id',
            'name' => 'required|string|max:255',
            'type' => 'required|in:residential,business',
            'speed' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
        ]);

        Package::create($request->all());

        return redirect()->route('packages.index')
            ->with('success', 'Paket berhasil ditambahkan.');
    }

    /**
     * Form edit package.
     */
    public function edit(Package $package)
    {
        $coverageAreas = CoverageArea::select('id', 'name')->get();
        return view('packages.edit', compact('package', 'coverageAreas'));
    }

    /**
     * Update data package.
     */
    public function update(Request $request, Package $package)
    {
        $request->validate([
            'coverage_area_id' => 'required|exists:coverage_areas,id',
            'name' => 'required|string|max:255',
            'type' => 'required|in:residential,business',
            'speed' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
        ]);

        $package->update($request->all());

        return redirect()->route('packages.index')
            ->with('success', 'Paket berhasil diperbarui.');
    }

    /**
     * Hapus package.
     */
    public function destroy(Package $package)
    {
        $package->delete();

        return redirect()->route('packages.index')
            ->with('success', 'Paket berhasil dihapus.');
    }
}
