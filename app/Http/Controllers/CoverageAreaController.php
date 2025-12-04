<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CoverageArea;
use Illuminate\Support\Facades\DB;

class CoverageAreaController extends Controller
{
    public function index()
    {
        $areas = CoverageArea::select(
            'id',
            'name',
            DB::raw('ST_AsGeoJSON(boundary) as boundary'),
            'description'
        )->get();

        return view('coverage_areas.index', compact('areas'));
    }

    public function create()
    {
        return view('coverage_areas.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'boundary'    => 'nullable|string',
            'description' => 'nullable|string',
        ]);

        // 1. Simpan data dasar dulu
        $coverage = CoverageArea::create([
            'name'        => $request->name,
            'description' => $request->description,
        ]);

        // 2. Simpan boundary (SAFE MODE: MariaDB & MySQL)
        // ST_GeomFromGeoJSON(?) -> Ubah JSON jadi Geometry (Default SRID 0)
        // ST_SRID(..., 4326) -> Paksa jadi SRID 4326 (GPS)
        if ($request->filled('boundary')) {
            DB::statement(
                "UPDATE coverage_areas 
                 SET boundary = ST_GeomFromGeoJSON(?, 2, 4326) 
                 WHERE id = ?",
                [$request->boundary, $coverage->id]
            );
        }

        return redirect()
            ->route('coverage_areas.index')
            ->with('success', 'Coverage area berhasil ditambahkan.');
    }

    public function show(CoverageArea $coverageArea)
    {
        $coverageArea->boundary = DB::selectOne(
            "SELECT ST_AsGeoJSON(boundary) AS boundary FROM coverage_areas WHERE id = ?",
            [$coverageArea->id]
        )->boundary ?? null;

        return view('coverage_areas.show', compact('coverageArea'));
    }

    public function edit(CoverageArea $coverageArea)
    {
        $coverageArea->boundary = DB::selectOne(
            "SELECT ST_AsGeoJSON(boundary) AS boundary FROM coverage_areas WHERE id = ?",
            [$coverageArea->id]
        )->boundary ?? null;

        return view('coverage_areas.edit', compact('coverageArea'));
    }

    public function update(Request $request, CoverageArea $coverageArea)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'boundary'    => 'nullable|string',
            'description' => 'nullable|string',
        ]);

        $coverageArea->update([
            'name'        => $request->name,
            'description' => $request->description,
        ]);

        if ($request->filled('boundary')) {
            // SAFE MODE JUGA DI SINI
            DB::statement(
                "UPDATE coverage_areas 
                 SET boundary = ST_GeomFromGeoJSON(?, 2, 4326) 
                 WHERE id = ?",
                [$request->boundary, $coverageArea->id]
            );
        } else {
            // Jika boundary dihapus
            DB::statement(
                "UPDATE coverage_areas SET boundary = NULL WHERE id = ?",
                [$coverageArea->id]
            );
        }

        return redirect()
            ->route('coverage_areas.index')
            ->with('success', 'Coverage area berhasil diperbarui.');
    }

    public function destroy(CoverageArea $coverageArea)
    {
        $coverageArea->delete();

        return redirect()
            ->route('coverage_areas.index')
            ->with('success', 'Coverage area berhasil dihapus.');
    }
}