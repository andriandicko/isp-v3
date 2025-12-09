<?php

namespace App\Http\Controllers;

use App\Models\OutgoingGood;
use App\Models\OutgoingGoodsDetail;
use App\Models\Warehouse;
use App\Models\Item;
use App\Models\WarehouseStock;
use App\Models\User;
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
        
        // PERBAIKAN DI SINI:
        // 1. Hapus where('status', 'active') karena kolom tidak ada
        // 2. Filter user selain role 'customer' dan 'korlap'
        // 3. Gunakan email sebagai info tambahan (karena employee_id tidak ada)
        $users = User::whereDoesntHave('roles', function ($q) {
            $q->whereIn('name', ['customer', 'korlap']); 
        })->get()->map(function($user) {
            return [
                'value' => $user->id,
                'text' => $user->name . ' (' . $user->email . ')'
            ];
        });

        // Generate kode transaksi
        $today = date('Ymd');
        $prefix = 'OUT-' . $today . '-';
        $lastTransaction = OutgoingGood::where('transaction_code', 'like', $prefix . '%')->orderBy('id', 'desc')->first();
        
        if ($lastTransaction) {
            $lastNumber = (int) substr($lastTransaction->transaction_code, -5);
            $number = $lastNumber + 1;
        } else {
            $number = 1;
        }
        
        $transactionCode = $prefix . str_pad($number, 5, '0', STR_PAD_LEFT);

        return view('outgoing-goods.create', compact('warehouses', 'items', 'users', 'transactionCode'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'warehouse_id' => 'required|exists:warehouses,id',
            'transaction_date' => 'required|date',
            'recipient_ids' => 'required|array|min:1', 
            'recipient_ids.*' => 'exists:users,id',
            'department' => 'nullable|string|max:255',
            'purpose' => 'nullable|string',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.item_id' => 'required|exists:items,id',
            'items.*.quantity' => 'required|numeric|min:0.01',
        ]);

        DB::beginTransaction();
        try {
            $totalRecipients = count($validated['recipient_ids']);

            // 1. Generate Kode Transaksi Berurut untuk Bulk Insert
            $today = date('Ymd', strtotime($validated['transaction_date']));
            $prefix = 'OUT-' . $today . '-';
            
            // Lock dan ambil last number terbaru agar aman saat concurrent
            $lastTransaction = OutgoingGood::where('transaction_code', 'like', $prefix . '%')
                ->orderBy('id', 'desc')
                ->lockForUpdate()
                ->first();
                
            $counter = $lastTransaction ? ((int) substr($lastTransaction->transaction_code, -5)) + 1 : 1;

            // 2. Looping Create Transaksi
            foreach ($validated['recipient_ids'] as $userId) {
                $user = User::find($userId);
                
                // Format kode: OUT-YYYYMMDD-00001, 00002, dst
                $code = $prefix . str_pad($counter, 5, '0', STR_PAD_LEFT);

                $outgoingGoods = OutgoingGood::create([
                    'transaction_code' => $code,
                    'warehouse_id' => $validated['warehouse_id'],
                    'user_id' => auth()->id(), // Admin pembuat
                    'transaction_date' => $validated['transaction_date'],
                    'recipient_name' => $user->name, // Simpan nama penerima
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

                $counter++; // Naikkan nomor urut untuk user berikutnya
            }

            DB::commit();
            return redirect()->route('outgoing-goods.index')
                ->with('success', "$totalRecipients Transaksi barang keluar berhasil dibuat.");
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
        if ($outgoingGood->status !== 'pending') {
            return back()->with('error', 'Transaksi ini sudah diproses.');
        }

        DB::beginTransaction();
        try {
            // Validasi stok
            foreach ($outgoingGood->details as $detail) {
                $stock = WarehouseStock::where('warehouse_id', $outgoingGood->warehouse_id)
                    ->where('item_id', $detail->item_id)
                    ->first();

                $currentQty = $stock ? $stock->quantity : 0;

                if ($currentQty < $detail->quantity) {
                    throw new \Exception("Stok tidak mencukupi untuk item: {$detail->item->name} (Sisa: {$currentQty}, Minta: {$detail->quantity})");
                }
            }

            // Kurangi Stok
            foreach ($outgoingGood->details as $detail) {
                $stock = WarehouseStock::where('warehouse_id', $outgoingGood->warehouse_id)
                    ->where('item_id', $detail->item_id)
                    ->first();
                
                $stock->decrement('quantity', $detail->quantity);
            }

            $outgoingGood->update([
                'status' => 'approved',
                'approved_by' => auth()->id(),
                'approved_at' => now(),
            ]);

            DB::commit();
            return redirect()->route('outgoing-goods.show', $outgoingGood)
                ->with('success', 'Barang keluar disetujui. Stok telah dikurangi.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Gagal approve: ' . $e->getMessage());
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
            ->with('success', 'Permintaan barang keluar ditolak.');
    }

    public function destroy(OutgoingGood $outgoingGood)
    {
        if ($outgoingGood->status === 'approved') {
            return back()->with('error', 'Transaksi yang sudah diapprove tidak dapat dihapus.');
        }

        DB::beginTransaction();
        try {
            $outgoingGood->details()->delete();
            $outgoingGood->delete();
            
            DB::commit();
            return redirect()->route('outgoing-goods.index')
                ->with('success', 'Data berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Gagal hapus: ' . $e->getMessage());
        }
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