<?php

namespace App\Http\Controllers;

use App\Models\OutgoingGood;
use App\Models\OutgoingGoodsDetail;
use App\Models\Warehouse;
use App\Models\Item;
use App\Models\WarehouseStock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OutgoingGoodController extends Controller
{
    public function index()
    {
        $outgoingGoods = OutgoingGood::with(['warehouse', 'user'])
            ->latest()
            ->paginate(10);
        return view('outgoing-goods.index', compact('outgoingGoods'));
    }

    public function create()
    {
        $warehouses = Warehouse::where('status', 'active')->get();
        $items = Item::where('status', 'active')->with('category')->get();

        // Generate kode transaksi
        $lastTransaction = OutgoingGood::latest()->first();
        $number = $lastTransaction ? intval(substr($lastTransaction->transaction_code, -5)) + 1 : 1;
        $transactionCode = 'OUT-' . date('Ymd') . '-' . str_pad($number, 5, '0', STR_PAD_LEFT);

        return view('outgoing-goods.create', compact('warehouses', 'items', 'transactionCode'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'transaction_code' => 'required|unique:outgoing_goods,transaction_code',
            'warehouse_id' => 'required|exists:warehouses,id',
            'transaction_date' => 'required|date',
            'recipient_name' => 'required|string|max:255',
            'department' => 'nullable|string|max:255',
            'purpose' => 'nullable|string',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.item_id' => 'required|exists:items,id',
            'items.*.quantity' => 'required|numeric|min:0.01',
        ]);

        DB::beginTransaction();
        try {
            $outgoingGoods = OutgoingGood::create([
                'transaction_code' => $validated['transaction_code'],
                'warehouse_id' => $validated['warehouse_id'],
                'user_id' => auth()->id(),
                'transaction_date' => $validated['transaction_date'],
                'recipient_name' => $validated['recipient_name'],
                'department' => $validated['department'],
                'purpose' => $validated['purpose'],
                'notes' => $validated['notes'],
                'status' => 'pending',
            ]);

            foreach ($validated['items'] as $item) {
                OutgoingGoodsDetail::create([
                    'outgoing_goods_id' => $outgoingGoods->id,
                    'item_id' => $item['item_id'],
                    'quantity' => $item['quantity'],
                    'notes' => $item['notes'] ?? null,
                ]);
            }

            DB::commit();
            return redirect()->route('outgoing-goods.index')
                ->with('success', 'Barang keluar berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function show(OutgoingGood $outgoingGood)
    {
        $outgoingGood->load(['warehouse', 'user', 'approver', 'details.item']);
        return view('outgoing-goods.show', compact('outgoingGood'));
    }

    public function approve(OutgoingGood $outgoingGood)
    {
        // if (auth()->user()->role !== 'admin|warehouse') {
        //     return back()->with('error', 'Anda tidak memiliki akses untuk approve transaksi.');
        // }

        if ($outgoingGood->status !== 'pending') {
            return back()->with('error', 'Transaksi ini sudah diproses.');
        }

        DB::beginTransaction();
        try {
            // Validasi stock
            foreach ($outgoingGood->details as $detail) {
                $stock = WarehouseStock::where('warehouse_id', $outgoingGood->warehouse_id)
                    ->where('item_id', $detail->item_id)
                    ->first();

                if (!$stock || $stock->quantity < $detail->quantity) {
                    throw new \Exception("Stok {$detail->item->name} tidak mencukupi.");
                }
            }

            // Update status
            $outgoingGood->update([
                'status' => 'approved',
                'approved_by' => auth()->id(),
                'approved_at' => now(),
            ]);

            // Update stock
            foreach ($outgoingGood->details as $detail) {
                $stock = WarehouseStock::where('warehouse_id', $outgoingGood->warehouse_id)
                    ->where('item_id', $detail->item_id)
                    ->first();

                $stock->decrement('quantity', $detail->quantity);
            }

            DB::commit();
            return redirect()->route('outgoing-goods.show', $outgoingGood)
                ->with('success', 'Barang keluar berhasil diapprove dan stok telah diupdate.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function reject(OutgoingGood $outgoingGood)
    {
        if ($outgoingGood->status !== 'pending') {
            return back()->with('error', 'Transaksi ini sudah diproses.');
        }

        $outgoingGood->update([
            'status' => 'rejected',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);

        return redirect()->route('outgoing-goods.show', $outgoingGood)
            ->with('success', 'Barang keluar telah ditolak.');
    }

    public function destroy(OutgoingGood $outgoingGood)
    {
        if ($outgoingGood->status === 'approved') {
            return back()->with('error', 'Transaksi yang sudah diapprove tidak dapat dihapus.');
        }

        $outgoingGood->delete();

        return redirect()->route('outgoing-goods.index')
            ->with('success', 'Barang keluar berhasil dihapus.');
    }

    public function getStock(Request $request)
    {
        $stock = WarehouseStock::where('warehouse_id', $request->warehouse_id)
            ->where('item_id', $request->item_id)
            ->first();

        return response()->json([
            'quantity' => $stock ? $stock->quantity : 0
        ]);
    }
}
