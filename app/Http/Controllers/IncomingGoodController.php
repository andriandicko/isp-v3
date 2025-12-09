<?php

namespace App\Http\Controllers;

use App\Models\IncomingGood;
use App\Models\IncomingGoodsDetail;
use App\Models\Warehouse;
use App\Models\Supplier;
use App\Models\Item;
use App\Models\WarehouseStock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class IncomingGoodController extends Controller
{
    public function index()
    {
        $incomingGoods = IncomingGood::with(['warehouse', 'supplier', 'user'])
            ->latest()
            ->paginate(10);
        return view('incoming-goods.index', compact('incomingGoods'));
    }

    public function create()
    {
        $warehouses = Warehouse::where('status', 'active')->get();
        $suppliers = Supplier::where('status', 'active')->get();
        $items = Item::where('status', 'active')->with('category')->get();

        // --- PERBAIKAN GENERATE KODE (RESET PER HARI) ---
        $today = date('Ymd');
        $prefix = 'IN-' . $today . '-';

        // Cari transaksi terakhir yang kodenya berawalan prefix HARI INI
        $lastTransaction = IncomingGood::where('transaction_code', 'like', $prefix . '%')
            ->orderBy('id', 'desc') // Ambil yang paling baru dibuat hari ini
            ->first();

        if ($lastTransaction) {
            // Ambil 5 angka di belakang, lalu tambah 1
            $lastNumber = (int) substr($lastTransaction->transaction_code, -5);
            $number = $lastNumber + 1;
        } else {
            // Jika belum ada transaksi hari ini, mulai dari 1
            $number = 1;
        }

        $transactionCode = $prefix . str_pad($number, 5, '0', STR_PAD_LEFT);
        // ------------------------------------------------

        return view('incoming-goods.create', compact('warehouses', 'suppliers', 'items', 'transactionCode'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'transaction_code' => 'required|unique:incoming_goods,transaction_code',
            'warehouse_id' => 'required|exists:warehouses,id',
            'supplier_id' => 'required|exists:suppliers,id',
            'transaction_date' => 'required|date',
            'invoice_number' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.item_id' => 'required|exists:items,id',
            'items.*.quantity' => 'required|numeric|min:0.01',
            'items.*.price' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            // 1. Simpan Header (Tanpa total_amount karena pakai Accessor)
            $incomingGoods = IncomingGood::create([
                'transaction_code' => $validated['transaction_code'],
                'warehouse_id' => $validated['warehouse_id'],
                'supplier_id' => $validated['supplier_id'],
                'user_id' => auth()->id(),
                'transaction_date' => $validated['transaction_date'],
                'invoice_number' => $validated['invoice_number'],
                'notes' => $validated['notes'],
                'status' => 'pending',
            ]);

            // 2. Simpan Detail Items
            foreach ($validated['items'] as $item) {
                $subtotal = $item['quantity'] * $item['price'];
                
                IncomingGoodsDetail::create([
                    'incoming_goods_id' => $incomingGoods->id,
                    'item_id' => $item['item_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'subtotal' => $subtotal,
                    'notes' => $item['notes'] ?? null,
                ]);
            }

            DB::commit();
            return redirect()->route('incoming-goods.index')
                ->with('success', 'Barang masuk berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function show(IncomingGood $incomingGood)
    {
        // Load relasi details.item agar nama barang muncul di view
        $incomingGood->load(['warehouse', 'supplier', 'user', 'approver', 'details.item']);
        return view('incoming-goods.show', compact('incomingGood'));
    }

    public function approve(IncomingGood $incomingGood)
    {
        if ($incomingGood->status !== 'pending') {
            return back()->with('error', 'Transaksi ini sudah diproses.');
        }

        DB::beginTransaction();
        try {
            // Update status
            $incomingGood->update([
                'status' => 'approved',
                'approved_by' => auth()->id(),
                'approved_at' => now(),
            ]);

            // Update Stok di WarehouseStock
            foreach ($incomingGood->details as $detail) {
                $stock = WarehouseStock::firstOrCreate(
                    [
                        'warehouse_id' => $incomingGood->warehouse_id,
                        'item_id' => $detail->item_id,
                    ],
                    ['quantity' => 0] // Default jika belum ada
                );

                // Tambahkan stok
                $stock->increment('quantity', $detail->quantity);
            }

            DB::commit();
            return redirect()->route('incoming-goods.show', $incomingGood)
                ->with('success', 'Barang masuk disetujui & stok bertambah.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Gagal approve: ' . $e->getMessage());
        }
    }

    public function reject(IncomingGood $incomingGood)
    {
        if ($incomingGood->status !== 'pending') {
            return back()->with('error', 'Transaksi ini sudah diproses.');
        }

        $incomingGood->update([
            'status' => 'rejected',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);

        return redirect()->route('incoming-goods.show', $incomingGood)
            ->with('success', 'Barang masuk ditolak.');
    }

    public function destroy(IncomingGood $incomingGood)
    {
        if ($incomingGood->status === 'approved') {
            return back()->with('error', 'Transaksi yang sudah diapprove tidak dapat dihapus.');
        }

        DB::beginTransaction();
        try {
            // Hapus detailnya dulu (Hard Delete karena detail tidak pakai SoftDeletes)
            $incomingGood->details()->delete();
            
            // Hapus headernya (Soft Delete sesuai model)
            $incomingGood->delete();
            
            DB::commit();
            return redirect()->route('incoming-goods.index')
                ->with('success', 'Barang masuk berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Gagal hapus: ' . $e->getMessage());
        }
    }
}