<?php

namespace App\Http\Controllers;

use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class WarehouseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $warehouses = Warehouse::latest()->paginate(10);
        return view('warehouse.index', compact('warehouses'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('warehouse.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $validated = $request->validate([
            'code' => 'required|unique:warehouses,code',
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'city' => 'required|string|max:255',
            'province' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'manager_name' => 'nullable|string|max:255',
            'status' => 'required|in:active,inactive',
        ]);

        Warehouse::create($validated);

        return redirect()->route('warehouse.index')
            ->with('success', 'Warehouse berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Warehouse $warehouse)
    {
        //
        $warehouse->load(['stocks.item', 'incomingGoods', 'outgoingGoods']);
        return view('warehouse.show', compact('warehouse'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Warehouse $warehouse)
    {
        //
        return view('warehouse.edit', compact('warehouse'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Warehouse $warehouse)
    {
        //
        $validated = $request->validate([
            'code' => 'required|unique:warehouses,code,' . $warehouse->id,
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'city' => 'required|string|max:255',
            'province' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'manager_name' => 'nullable|string|max:255',
            'status' => 'required|in:active,inactive',
        ]);

        $warehouse->update($validated);

        return redirect()->route('warehouse.index')
            ->with('success', 'Warehouse berhasil diupdate.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Warehouse $warehouse)
    {
        //
        $warehouse->delete();

        return redirect()->route('warehouse.index')
            ->with('success', 'Warehouse berhasil dihapus.');
    }
}
