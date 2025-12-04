<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Billing;
use App\Models\Attendance;
use App\Models\WarehouseStock;
use App\Models\IncomingGood;
use App\Models\OutgoingGood;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();
        $now = Carbon::now();
        $currentMonth = $now->month;
        $currentYear = $now->year;

        // --- 1. STATISTIK PELANGGAN ---
        $newCustomersThisMonth = Customer::whereMonth('created_at', $currentMonth)
            ->whereYear('created_at', $currentYear)
            ->count();
            
        $lastMonthDate = $now->copy()->subMonth();
        $lastMonthCustomers = Customer::whereMonth('created_at', $lastMonthDate->month)
            ->whereYear('created_at', $lastMonthDate->year)
            ->count();

        $growth = 0;
        if ($lastMonthCustomers > 0) {
            $growth = (($newCustomersThisMonth - $lastMonthCustomers) / $lastMonthCustomers) * 100;
        } elseif ($newCustomersThisMonth > 0) {
            $growth = 100;
        }

        // REVISI: Jika hasil perhitungan minus (turun), paksa jadi 0%
        if ($growth < 0) {
            $growth = 0;
        }

        $stats = [
            'customers' => Customer::count(),
            'new_customers' => $newCustomersThisMonth,
            'growth_percentage' => round($growth, 1),
            'pending_bills' => Billing::where('status', 'pending')->count(),
            'overdue_bills' => Billing::where('status', 'overdue')->count(),
        ];

        // --- 2. MONITORING TIKET ---
        $ticketStats = [
            'open' => Ticket::where('status', 'open')->count(),
            'process' => Ticket::where('status', 'in_progress')->count(),
            'today' => Ticket::whereDate('created_at', $today)->count(),
            'latest' => Ticket::with('customer.user')->latest()->take(5)->get()
        ];

        // --- 3. MONITORING GUDANG (LOGISTIK) ---
        $lowStockItems = WarehouseStock::with(['item', 'warehouse'])
            ->whereHas('item', function($query) {
                $query->whereColumn('warehouse_stocks.quantity', '<=', 'items.minimum_stock');
            })
            ->take(5)
            ->get();

        $logisticStats = [
            'low_stock_count' => WarehouseStock::whereHas('item', function($query) {
                $query->whereColumn('warehouse_stocks.quantity', '<=', 'items.minimum_stock');
            })->count(),
            'pending_incoming' => IncomingGood::where('status', 'pending')->count(),
            'pending_outgoing' => OutgoingGood::where('status', 'pending')->count(),
        ];

        // --- 4. MONITORING SDM (ABSENSI) ---
        // PERBAIKAN: Hapus 'master' dari sini karena role tersebut belum dibuat di database
        $excludedRoles = ['admin', 'korlap', 'customer']; 

        // Filter: Hanya hitung User yang BUKAN role di atas
        $attendanceStats = [
            'present' => Attendance::whereDate('date', $today)
                ->where('status', 'present')
                ->whereHas('user', function($q) use ($excludedRoles) {
                    $q->withoutRole($excludedRoles);
                })
                ->count(),
            'late' => Attendance::whereDate('date', $today)
                ->where('status', 'late')
                ->whereHas('user', function($q) use ($excludedRoles) {
                    $q->withoutRole($excludedRoles);
                })
                ->count(),
            'leave' => Attendance::whereDate('date', $today)
                ->whereIn('status', ['sick', 'leave', 'business_trip'])
                ->whereHas('user', function($q) use ($excludedRoles) {
                    $q->withoutRole($excludedRoles);
                })
                ->count(),
        ];
        
        // Hitung Total Tim (Penyebut): Total User dikurangi Admin, Korlap, Customer
        $totalStaff = User::withoutRole($excludedRoles)->count();

        // --- 5. KEUANGAN ---
        $incomeThisMonth = Billing::where('status', 'paid')
            ->whereMonth('paid_at', $currentMonth)
            ->whereYear('paid_at', $currentYear)
            ->sum('amount');

        $expenseThisMonth = DB::table('incoming_goods_details')
            ->join('incoming_goods', 'incoming_goods_details.incoming_goods_id', '=', 'incoming_goods.id')
            ->where('incoming_goods.status', 'approved')
            ->whereMonth('incoming_goods.transaction_date', $currentMonth)
            ->whereYear('incoming_goods.transaction_date', $currentYear)
            ->sum(DB::raw('incoming_goods_details.quantity * incoming_goods_details.price'));

        $finance = [
            'income' => $incomeThisMonth,
            'expense' => $expenseThisMonth,
            'profit' => $incomeThisMonth - $expenseThisMonth
        ];

        // --- 6. GRAFIK ---
        $labels = [];
        $incomeData = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = $now->copy()->subMonths($i);
            $labels[] = $date->isoFormat('MMMM');
            $incomeData[] = Billing::where('status', 'paid')
                ->whereMonth('paid_at', $date->month)
                ->whereYear('paid_at', $date->year)
                ->sum('amount');
        }

        $chart = [
            'labels' => $labels,
            'data' => $incomeData
        ];

        return view('dashboard', compact(
            'stats',
            'ticketStats',
            'logisticStats',
            'lowStockItems',
            'attendanceStats',
            'totalStaff',
            'finance',
            'chart'
        ));
    }
}