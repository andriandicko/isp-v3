<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\ItemCategory;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $items = Item::with('category')->latest()->paginate(10);
        return view('item.index', compact('items'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $categories = ItemCategory::where('status', 'active')->get();
        return view('item.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $validated = $request->validate([
            'category_id' => 'required|exists:item_categories,id',
            'code' => 'required|unique:items,code',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'unit' => 'required|string|max:50',
            'minimum_stock' => 'required|numeric|min:0',
            'price' => 'required|numeric|min:0',
            'status' => 'required|in:active,inactive',
        ]);

        Item::create($validated);

        return redirect()->route('item.index')
            ->with('success', 'Item berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Item $item)
    {
        //
        $item->load(['category', 'warehouseStocks.warehouse']);
        return view('item.show', compact('item'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Item $item)
    {
        //
        $categories = ItemCategory::all();
        return view('item.edit', compact('item', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Item $item)
    {
        //
        $validated = $request->validate([
            'category_id' => 'required|exists:item_categories,id',
            'code' => 'required|unique:items,code,' . $item->id,
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'unit' => 'required|string|max:50',
            'minimum_stock' => 'required|numeric|min:0',
            'price' => 'required|numeric|min:0',
            'status' => 'required|in:active,inactive',
        ]);

        $item->update($validated);

        return redirect()->route('item.index')
            ->with('success', 'Item berhasil diupdate.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Item $item)
    {
        //
        $item->delete();

        return redirect()->route('item.index')
            ->with('success', 'Item berhasil dihapus.');
    }
}
