<?php

// app/Models/WarehouseTransfer.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WarehouseTransfer extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'transaction_code',
        'from_warehouse_id',
        'to_warehouse_id',
        'user_id',
        'transaction_date',
        'notes',
        'status',
        'approved_by',
        'approved_at',
        'completed_at'
    ];

    protected $casts = [
        'transaction_date' => 'date',
        'approved_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function fromWarehouse()
    {
        return $this->belongsTo(Warehouse::class, 'from_warehouse_id');
    }

    public function toWarehouse()
    {
        return $this->belongsTo(Warehouse::class, 'to_warehouse_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function details()
    {
        return $this->hasMany(WarehouseTransferDetail::class);
    }

    public function getStatusBadgeAttribute()
    {
        $badges = [
            'pending' => '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">Pending</span>',
            'in_transit' => '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">In Transit</span>',
            'completed' => '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Completed</span>',
            'cancelled' => '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Cancelled</span>',
        ];

        return $badges[$this->status] ?? $this->status;
    }
}

// app/Models/WarehouseTransferDetail.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WarehouseTransferDetail extends Model
{
    protected $fillable = [
        'warehouse_transfer_id',
        'item_id',
        'quantity',
        'notes'
    ];

    protected $casts = [
        'quantity' => 'decimal:2',
    ];

    public function warehouseTransfer()
    {
        return $this->belongsTo(WarehouseTransfer::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
