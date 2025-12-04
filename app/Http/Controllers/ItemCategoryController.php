<?php

namespace App\Http\Controllers;

use App\Models\ItemCategory;
use Illuminate\Http\Request;

class ItemCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $categories = ItemCategory::withCount('items')->latest()->paginate(10);
        return view('item-category.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('item-category.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:item_categories,name',
            'description' => 'nullable|string',
        ]);

        ItemCategory::create($validated);

        return redirect()->route('item-category.index')
            ->with('success', 'Kategori berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(ItemCategory $itemCategory)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ItemCategory $itemCategory)
    {
        //
        return view('item-category.edit', compact('itemCategory'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ItemCategory $itemCategory)
    {
        //
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:item_categories,name,' . $itemCategory->id,
            'description' => 'nullable|string',
        ]);

        $itemCategory->update($validated);

        return redirect()->route('item-category.index')
            ->with('success', 'Kategori berhasil diupdate.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ItemCategory $itemCategory)
    {
        //
        if ($itemCategory->items()->count() > 0) {
            return back()->with('error', 'Kategori tidak dapat dihapus karena masih memiliki item.');
        }

        $itemCategory->delete();

        return redirect()->route('item-category.index')
            ->with('success', 'Kategori berhasil dihapus.');
    }
}
