<?php

namespace App\Http\Controllers;

use App\Models\WarehouseTransfer;
use App\Models\WarehouseTransferDetail;
use App\Models\Warehouse;
use App\Models\Item;
use App\Models\WarehouseStock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WarehouseTransferController extends Controller
{
    public function index()
    {
        $transfers = WarehouseTransfer::with(['fromWarehouse', 'toWarehouse', 'user'])
            ->latest()
            ->paginate(10);

        return view('warehouse-transfer.index', compact('transfers'));
    }

    public function create()
    {
        $warehouses = Warehouse::where('status', 'active')->get();
        $items = Item::where('status', 'active')->with('category')->get();

        // Generate kode transaksi
        $lastTransaction = WarehouseTransfer::latest()->first();
        $number = $lastTransaction ? intval(substr($lastTransaction->transaction_code, -5)) + 1 : 1;
        $transactionCode = 'TRF-' . date('Ymd') . '-' . str_pad($number, 5, '0', STR_PAD_LEFT);

        return view('warehouse-transfer.create', compact('warehouses', 'items', 'transactionCode'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'transaction_code' => 'required|unique:warehouse_transfers,transaction_code',
            'from_warehouse_id' => 'required|exists:warehouses,id',
            'to_warehouse_id' => 'required|exists:warehouses,id|different:from_warehouse_id',
            'transaction_date' => 'required|date',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.item_id' => 'required|exists:items,id',
            'items.*.quantity' => 'required|numeric|min:0.01',
        ], [
            'to_warehouse_id.different' => 'Warehouse tujuan harus berbeda dengan warehouse asal',
        ]);

        DB::beginTransaction();
        try {
            // Validasi stock untuk setiap item
            foreach ($validated['items'] as $item) {
                $stock = WarehouseStock::where('from_warehouse_id', $validated['from_warehouse_id'])
                    ->where('item_id', $item['item_id'])
                    ->first();

                if (!$stock || $stock->quantity < $item['quantity']) {
                    $itemName = Item::find($item['item_id'])->name;
                    throw new \Exception("Stok {$itemName} di warehouse asal tidak mencukupi.");
                }
            }

            $transfer = WarehouseTransfer::create([
                'transaction_code' => $validated['transaction_code'],
                'from_warehouse_id' => $validated['from_warehouse_id'],
                'to_warehouse_id' => $validated['to_warehouse_id'],
                'user_id' => auth()->id(),
                'transaction_date' => $validated['transaction_date'],
                'notes' => $validated['notes'],
                'status' => 'pending',
            ]);

            foreach ($validated['items'] as $item) {
                WarehouseTransferDetail::create([
                    'warehouse_transfer_id' => $transfer->id,
                    'item_id' => $item['item_id'],
                    'quantity' => $item['quantity'],
                    'notes' => $item['notes'] ?? null,
                ]);
            }

            DB::commit();
            return redirect()->route('warehouse-transfer.index')
                ->with('success', 'Transfer barang berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    public function show(WarehouseTransfer $warehouseTransfer)
    {
        $warehouseTransfer->load([
            'fromWarehouse',
            'toWarehouse',
            'user',
            'approver',
            'details.item'
        ]);

        return view('warehouse-transfer.show', compact('warehouseTransfer'));
    }

    public function approve(WarehouseTransfer $warehouseTransfer)
    {
        if ($warehouseTransfer->status !== 'pending') {
            return back()->with('error', 'Transaksi ini sudah diproses.');
        }

        DB::beginTransaction();
        try {
            // Validasi stock lagi sebelum approve
            foreach ($warehouseTransfer->details as $detail) {
                $stock = WarehouseStock::where('warehouse_id', $warehouseTransfer->from_warehouse_id)
                    ->where('item_id', $detail->item_id)
                    ->first();

                if (!$stock || $stock->quantity < $detail->quantity) {
                    throw new \Exception("Stok {$detail->item->name} tidak mencukupi.");
                }
            }

            // Update status ke in_transit
            $warehouseTransfer->update([
                'status' => 'in_transit',
                'approved_by' => auth()->id(),
                'approved_at' => now(),
            ]);

            // Kurangi stock dari warehouse asal
            foreach ($warehouseTransfer->details as $detail) {
                $stock = WarehouseStock::where('warehouse_id', $warehouseTransfer->from_warehouse_id)
                    ->where('item_id', $detail->item_id)
                    ->first();

                $stock->decrement('quantity', $detail->quantity);
            }

            DB::commit();
            return redirect()->route('warehouse-transfer.show', $warehouseTransfer)
                ->with('success', 'Transfer diapprove. Status: In Transit. Stock sudah dikurangi dari warehouse asal.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function complete(WarehouseTransfer $warehouseTransfer)
    {
        if ($warehouseTransfer->status !== 'in_transit') {
            return back()->with('error', 'Transfer harus dalam status In Transit untuk diselesaikan.');
        }

        DB::beginTransaction();
        try {
            // Update status
            $warehouseTransfer->update([
                'status' => 'completed',
                'completed_at' => now(),
            ]);

            // Tambahkan stock ke warehouse tujuan
            foreach ($warehouseTransfer->details as $detail) {
                $stock = WarehouseStock::firstOrCreate(
                    [
                        'warehouse_id' => $warehouseTransfer->to_warehouse_id,
                        'item_id' => $detail->item_id,
                    ],
                    ['quantity' => 0]
                );

                $stock->increment('quantity', $detail->quantity);
            }

            DB::commit();
            return redirect()->route('warehouse-transfer.show', $warehouseTransfer)
                ->with('success', 'Transfer selesai. Stock sudah ditambahkan ke warehouse tujuan.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function cancel(WarehouseTransfer $warehouseTransfer)
    {
        if ($warehouseTransfer->status === 'completed') {
            return back()->with('error', 'Transfer yang sudah selesai tidak dapat dibatalkan.');
        }

        DB::beginTransaction();
        try {
            // Jika status in_transit, kembalikan stock ke warehouse asal
            if ($warehouseTransfer->status === 'in_transit') {
                foreach ($warehouseTransfer->details as $detail) {
                    $stock = WarehouseStock::where('warehouse_id', $warehouseTransfer->from_warehouse_id)
                        ->where('item_id', $detail->item_id)
                        ->first();

                    $stock->increment('quantity', $detail->quantity);
                }
            }

            $warehouseTransfer->update([
                'status' => 'cancelled',
            ]);

            DB::commit();
            return redirect()->route('warehouse-transfer.show', $warehouseTransfer)
                ->with('success', 'Transfer dibatalkan. Stock dikembalikan ke warehouse asal.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function destroy(WarehouseTransfer $warehouseTransfer)
    {
        if (in_array($warehouseTransfer->status, ['in_transit', 'completed'])) {
            return back()->with('error', 'Transfer yang sedang dalam perjalanan atau sudah selesai tidak dapat dihapus.');
        }

        $warehouseTransfer->delete();

        return redirect()->route('warehouse-transfer.index')
            ->with('success', 'Transfer berhasil dihapus.');
    }

    /**
     * Get available stock for transfer
     */
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
