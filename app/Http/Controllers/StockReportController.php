<?php

namespace App\Http\Controllers;

use App\Models\WarehouseStock;
use App\Models\Warehouse;
use App\Models\ItemCategory;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StockReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //
        $warehouses = Warehouse::where('status', 'active')->get();
        $categories = ItemCategory::all();

        $query = WarehouseStock::with(['warehouse', 'item.category']);

        // Filter by warehouse
        if ($request->filled('warehouse_id')) {
            $query->where('warehouse_id', $request->warehouse_id);
        }

        // Filter by category
        if ($request->filled('category_id')) {
            $query->whereHas('item', function ($q) use ($request) {
                $q->where('category_id', $request->category_id);
            });
        }

        // Search by item name or code
        if ($request->filled('search')) {
            $query->whereHas('item', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('code', 'like', '%' . $request->search . '%');
            });
        }

        $stocks = $query->paginate(20)->withQueryString();

        return view('stock-report.index', compact('stocks', 'warehouses', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    /**
     * Display items with minimum stock alert
     */
    public function minimumStock()
    {
        // Get items where current stock is less than or equal to minimum stock
        $lowStockItems = WarehouseStock::with(['warehouse', 'item.category'])
            ->select('warehouse_stocks.*')
            ->join('items', 'warehouse_stocks.item_id', '=', 'items.id')
            ->whereRaw('warehouse_stocks.quantity <= items.minimum_stock')
            ->orderBy('warehouse_stocks.quantity', 'asc')
            ->get();

        // Group by item for summary
        $itemsSummary = Item::with('category')
            ->whereHas('warehouseStocks', function ($q) {
                $q->whereRaw('warehouse_stocks.quantity <= items.minimum_stock');
            })
            ->withSum('warehouseStocks', 'quantity')
            ->get();

        return view('stock-report.minimum-stock', compact('lowStockItems', 'itemsSummary'));
    }


    /**
     * Export stock report to Excel
     * Note: Requires maatwebsite/excel package
     */
    public function export(Request $request)
    {
        // Check if maatwebsite/excel is installed
        if (!class_exists('\Maatwebsite\Excel\Facades\Excel')) {
            return back()->with('error', 'Excel export package not installed. Run: composer require maatwebsite/excel');
        }

        // Implementation example:
        // return Excel::download(new StockReportExport($request->all()), 'stock-report-' . date('Y-m-d') . '.xlsx');

        return back()->with('info', 'Export feature requires maatwebsite/excel package installation');
    }



    /**
     * Get stock movement history
     */
    public function stockMovement(Request $request)
    {
        $items = Item::where('status', 'active')->get();
        $warehouses = Warehouse::where('status', 'active')->get();

        // This would require a stock_movements table to track all changes
        // For now, we can show incoming and outgoing transactions

        $query = DB::table('incoming_goods_details')
            ->join('incoming_goods', 'incoming_goods_details.incoming_goods_id', '=', 'incoming_goods.id')
            ->join('items', 'incoming_goods_details.item_id', '=', 'items.id')
            ->join('warehouses', 'incoming_goods.warehouse_id', '=', 'warehouses.id')
            ->select(
                'incoming_goods.transaction_code',
                'incoming_goods.transaction_date',
                'warehouses.name as warehouse_name',
                'items.code as item_code',
                'items.name as item_name',
                'incoming_goods_details.quantity',
                DB::raw("'IN' as type"),
                'incoming_goods.status'
            )
            ->where('incoming_goods.status', 'approved');

        $movements = DB::table('outgoing_goods_details')
            ->join('outgoing_goods', 'outgoing_goods_details.outgoing_goods_id', '=', 'outgoing_goods.id')
            ->join('items', 'outgoing_goods_details.item_id', '=', 'items.id')
            ->join('warehouses', 'outgoing_goods.warehouse_id', '=', 'warehouses.id')
            ->select(
                'outgoing_goods.transaction_code',
                'outgoing_goods.transaction_date',
                'warehouses.name as warehouse_name',
                'items.code as item_code',
                'items.name as item_name',
                DB::raw('outgoing_goods_details.quantity * -1 as quantity'),
                DB::raw("'OUT' as type"),
                'outgoing_goods.status'
            )
            ->where('outgoing_goods.status', 'approved')
            ->union($query);

        // Apply filters
        if ($request->filled('item_id')) {
            $movements->where('items.id', $request->item_id);
        }

        if ($request->filled('warehouse_id')) {
            $movements->where('warehouses.id', $request->warehouse_id);
        }

        if ($request->filled('date_from')) {
            $movements->whereDate('transaction_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $movements->whereDate('transaction_date', '<=', $request->date_to);
        }

        $movements = $movements->orderBy('transaction_date', 'desc')->paginate(20);

        return view('stock-report.movement', compact('movements', 'items', 'warehouses'));
    }



    /**
     * Get stock value report (total nilai persediaan)
     */
    public function stockValue()
    {
        $stockValue = WarehouseStock::with(['warehouse', 'item'])
            ->get()
            ->map(function ($stock) {
                return [
                    'warehouse' => $stock->warehouse->name,
                    'item_code' => $stock->item->code,
                    'item_name' => $stock->item->name,
                    'quantity' => $stock->quantity,
                    'price' => $stock->item->price,
                    'total_value' => $stock->quantity * $stock->item->price,
                ];
            });

        // Group by warehouse
        $valueByWarehouse = $stockValue->groupBy('warehouse')->map(function ($items) {
            return [
                'items' => $items,
                'total_value' => $items->sum('total_value'),
            ];
        });

        $grandTotal = $stockValue->sum('total_value');

        return view('stock-report.value', compact('valueByWarehouse', 'grandTotal'));
    }



    /**
     * Stock card / Kartu Stock for specific item
     */
    public function stockCard(Item $item)
    {
        $item->load(['category', 'warehouseStocks.warehouse']);

        // Get all transactions for this item
        $incomingTransactions = DB::table('incoming_goods_details')
            ->join('incoming_goods', 'incoming_goods_details.incoming_goods_id', '=', 'incoming_goods.id')
            ->join('warehouses', 'incoming_goods.warehouse_id', '=', 'warehouses.id')
            ->where('incoming_goods_details.item_id', $item->id)
            ->where('incoming_goods.status', 'approved')
            ->select(
                'incoming_goods.transaction_date',
                'incoming_goods.transaction_code',
                'warehouses.name as warehouse_name',
                'incoming_goods_details.quantity',
                DB::raw("'IN' as type"),
                'incoming_goods_details.price',
                DB::raw('incoming_goods_details.quantity * incoming_goods_details.price as value')
            )
            ->get();

        $outgoingTransactions = DB::table('outgoing_goods_details')
            ->join('outgoing_goods', 'outgoing_goods_details.outgoing_goods_id', '=', 'outgoing_goods.id')
            ->join('warehouses', 'outgoing_goods.warehouse_id', '=', 'warehouses.id')
            ->where('outgoing_goods_details.item_id', $item->id)
            ->where('outgoing_goods.status', 'approved')
            ->select(
                'outgoing_goods.transaction_date',
                'outgoing_goods.transaction_code',
                'warehouses.name as warehouse_name',
                DB::raw('outgoing_goods_details.quantity * -1 as quantity'),
                DB::raw("'OUT' as type"),
                DB::raw('0 as price'),
                DB::raw('0 as value')
            )
            ->get();

        $transactions = $incomingTransactions->concat($outgoingTransactions)
            ->sortBy('transaction_date')
            ->values();

        return view('stock-report.stock-card', compact('item', 'transactions'));
    }
}
