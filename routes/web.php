<?php

use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BillingController;
use App\Http\Controllers\CoverageAreaController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\IncomingGoodController;
use App\Http\Controllers\ItemCategoryController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\KorlapController;
use App\Http\Controllers\LeaveController;
use App\Http\Controllers\OfficeController;
use App\Http\Controllers\OutgoingGoodController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\RoleController; 
use App\Http\Controllers\ShiftController;
use App\Http\Controllers\StockReportController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserShiftController;
use App\Http\Controllers\WarehouseController;
use App\Http\Controllers\WarehouseTransferController;
use Illuminate\Support\Facades\Route;

// === ROUTE PUBLIK ===
Route::get('/', function () {
    return view('welcome');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});

// Cek Coverage (Public dengan Throttle)
Route::post('/check-coverage', [CustomerController::class, 'checkCoverage'])
    ->middleware('throttle:60,1')
    ->name('customers.check-coverage');

// === ROUTE PROTECTED (HARUS LOGIN) ===
Route::middleware('auth')->group(function () {
    
    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Dashboard: Minimal punya permission dashboard.index
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard')
        ->middleware('can:dashboard.index');

    // === MANAJEMEN USER & ROLE (ADMINISTRASI) ===
    Route::resource('roles', RoleController::class)->middleware('can:roles.index');
    Route::resource('users', UserController::class)->middleware('can:users.index');
    Route::resource('packages', PackageController::class)->middleware('can:packages.index');
    Route::resource('coverage_areas', CoverageAreaController::class)->middleware('can:coverage_areas.index');
    Route::resource('korlaps', KorlapController::class)->middleware('can:korlaps.index');
    Route::resource('offices', OfficeController::class)->middleware('can:offices.index');
    Route::resource('shifts', ShiftController::class)->middleware('can:shifts.index');
    Route::resource('user-shifts', UserShiftController::class)->middleware('can:user-shifts.index');
    
    // Toggle Status Shift User
    Route::patch('user-shifts/{userShift}/toggle', [UserShiftController::class, 'toggleActive'])
        ->name('user-shifts.toggle')
        ->middleware('can:user-shifts.edit');


    // === OPERASIONAL & SUPPORT ===
    Route::resource('customers', CustomerController::class)->middleware('can:customers.index');
    
    // Tiket: Semua bisa lihat index (jika punya akses), tapi reply butuh izin khusus
    Route::resource('tickets', TicketController::class)->middleware('can:tickets.index');
    Route::post('tickets/{ticket}/reply', [TicketController::class, 'reply'])
        ->name('tickets.reply')
        ->middleware('can:tickets.reply');


    // === KEUANGAN (BILLING & PAYMENT) ===
    Route::resource('billings', BillingController::class)->middleware('can:billings.index');
    Route::resource('payments', PaymentController::class)->middleware('can:payments.index');
    
    // Billing Actions
    Route::get('billings/{billing}/payment', [BillingController::class, 'payment'])
        ->name('billings.payment')
        ->middleware('can:billings.payment');
    Route::post('billings/{billing}/payment', [BillingController::class, 'processPayment'])
        ->name('billings.process-payment')
        ->middleware('can:billings.payment');
    Route::post('/billings/generate-bulk', [BillingController::class, 'generateBulk'])
        ->name('billings.generate-bulk')
        ->middleware('can:billings.generate');
    Route::post('/billings/check-overdue', [BillingController::class, 'checkOverdue'])
        ->name('billings.check-overdue');


    // === GUDANG / LOGISTIK ===
    // Master Data Gudang
    Route::resource('warehouse', WarehouseController::class)->middleware('can:warehouse.index');
    Route::resource('item-category', ItemCategoryController::class)->middleware('can:item-category.index');
    Route::resource('item', ItemController::class)->middleware('can:item.index');
    Route::resource('supplier', SupplierController::class)->middleware('can:supplier.index');

    // Barang Masuk
    Route::resource('incoming-goods', IncomingGoodController::class)->middleware('can:incoming-goods.index');
    Route::post('incoming-goods/{incomingGood}/approve', [IncomingGoodController::class, 'approve'])
        ->name('incoming-goods.approve')
        ->middleware('can:incoming-goods.approve');
    Route::post('incoming-goods/{incomingGood}/reject', [IncomingGoodController::class, 'reject'])
        ->name('incoming-goods.reject')
        ->middleware('can:incoming-goods.reject');

    // Barang Keluar
    Route::resource('outgoing-goods', OutgoingGoodController::class)->middleware('can:outgoing-goods.index');
    Route::get('outgoing-goods/stock/check', [OutgoingGoodController::class, 'getStock'])
        ->name('outgoing-goods.stock.check');
        
    // Approval Barang Keluar
    Route::middleware(['can:outgoing-goods.approve'])->group(function () {
        Route::post('outgoing-goods/{outgoingGood}/approve', [OutgoingGoodController::class, 'approve'])
            ->name('outgoing-goods.approve');
        Route::post('outgoing-goods/{outgoingGood}/reject', [OutgoingGoodController::class, 'reject'])
            ->name('outgoing-goods.reject');
    });

    // Transfer Gudang
    Route::resource('warehouse-transfer', WarehouseTransferController::class)->middleware('can:warehouse-transfer.index');
    Route::get('warehouse-transfer/stock/check', [WarehouseTransferController::class, 'getStock'])
        ->name('warehouse-transfer.stock.check');
        
    // Approval & Aksi Transfer
    Route::middleware(['can:warehouse-transfer.approve'])->group(function () {
        Route::post('warehouse-transfer/{warehouseTransfer}/approve', [WarehouseTransferController::class, 'approve'])
            ->name('warehouse-transfer.approve');
        Route::post('warehouse-transfer/{warehouseTransfer}/complete', [WarehouseTransferController::class, 'complete'])
            ->name('warehouse-transfer.complete');
        Route::post('warehouse-transfer/{warehouseTransfer}/cancel', [WarehouseTransferController::class, 'cancel'])
            ->name('warehouse-transfer.cancel');
    });

    // Laporan Stok
    Route::middleware('can:stock-report.index')->group(function(){
        Route::get('stock-report', [StockReportController::class, 'index'])->name('stock-report.index');
        Route::get('stock-report/export', [StockReportController::class, 'export'])
            ->name('stock-report.export')
            ->middleware('can:stock-report.export');
        Route::get('stock-report/minimum-stock', [StockReportController::class, 'minimumStock'])
            ->name('stock-report.minimum-stock');
    });


    // === ABSENSI (ATTENDANCE) ===
    Route::prefix('attendance')->name('attendance.')->middleware('can:attendance.index')->group(function () {
        Route::get('/', [AttendanceController::class, 'index'])->name('index');
        
        Route::post('/checkin', [AttendanceController::class, 'checkIn'])
            ->name('checkin')
            ->middleware('can:attendance.checkin');
            
        Route::post('/checkout', [AttendanceController::class, 'checkOut'])
            ->name('checkout')
            ->middleware('can:attendance.checkout');
            
        Route::get('/recap', [AttendanceController::class, 'recap'])
            ->name('recap')
            ->middleware('can:attendance.recap');
    });


    // === IZIN / CUTI (LEAVE) ===
    Route::prefix('leave')->name('leave.')->middleware('can:leave.index')->group(function () {
        Route::get('/', [LeaveController::class, 'index'])->name('index');
        
        // Buat Izin Baru
        Route::get('/create', [LeaveController::class, 'create'])
            ->name('create')
            ->middleware('can:leave.create');
        Route::post('/', [LeaveController::class, 'store'])
            ->name('store')
            ->middleware('can:leave.create');
            
        Route::get('/{leave}', [LeaveController::class, 'show'])->name('show');
        Route::delete('/{leave}', [LeaveController::class, 'destroy'])->name('destroy');
        
        // Approval Izin (Admin/HRD)
        Route::middleware(['can:leave.approve'])->group(function () {
            Route::get('/admin/list', [LeaveController::class, 'adminIndex'])->name('admin.index');
            Route::post('/{leave}/approve', [LeaveController::class, 'approve'])->name('approve');
        });
    });

});