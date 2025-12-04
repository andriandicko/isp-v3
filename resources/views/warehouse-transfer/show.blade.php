{{-- resources/views/warehouse-transfer/show.blade.php --}}
@extends('layouts.app')

@section('title', 'Detail Transfer')

@section('content')
    <div class="bg-white rounded-lg shadow-md">
        <div class="p-6 border-b border-gray-200">
            <div class="flex justify-between items-center">
                <h2 class="text-2xl font-bold text-gray-800">Detail Transfer Barang</h2>
                <div class="flex space-x-2">
                    @if ($warehouseTransfer->status == 'pending')
                        <form action="{{ route('warehouse-transfer.approve', $warehouseTransfer) }}" method="POST"
                            class="inline">
                            @csrf
                            <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700"
                                onclick="return confirm('Approve transfer ini? Stock akan dikurangi dari warehouse asal.')">
                                <i class="fas fa-check mr-2"></i>Approve (Set In Transit)
                            </button>
                        </form>

                        <form action="{{ route('warehouse-transfer.cancel', $warehouseTransfer) }}" method="POST"
                            class="inline">
                            @csrf
                            <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700"
                                onclick="return confirm('Batalkan transfer ini?')">
                                <i class="fas fa-times mr-2"></i>Cancel
                            </button>
                        </form>
                    @endif

                    @if ($warehouseTransfer->status == 'in_transit')
                        <form action="{{ route('warehouse-transfer.complete', $warehouseTransfer) }}" method="POST"
                            class="inline">
                            @csrf
                            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700"
                                onclick="return confirm('Selesaikan transfer ini? Stock akan ditambahkan ke warehouse tujuan.')">
                                <i class="fas fa-check-double mr-2"></i>Complete Transfer
                            </button>
                        </form>

                        <form action="{{ route('warehouse-transfer.cancel', $warehouseTransfer) }}" method="POST"
                            class="inline">
                            @csrf
                            <button type="submit" class="bg-orange-600 text-white px-4 py-2 rounded hover:bg-orange-700"
                                onclick="return confirm('Batalkan transfer ini? Stock akan dikembalikan ke warehouse asal.')">
                                <i class="fas fa-undo mr-2"></i>Cancel & Return Stock
                            </button>
                        </form>
                    @endif

                    <a href="{{ route('warehouse-transfer.index') }}"
                        class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700">
                        <i class="fas fa-arrow-left mr-2"></i>Kembali
                    </a>
                </div>
            </div>
        </div>

        <div class="p-6">
            <!-- Status Timeline -->
            <div class="mb-6 bg-gray-50 p-4 rounded-lg">
                <h3 class="font-semibold text-gray-700 mb-3">Status Transfer</h3>
                <div class="flex items-center justify-between">
                    <div class="flex-1">
                        <div class="flex items-center">
                            <div class="flex items-center text-center flex-col">
                                <div
                                    class="w-12 h-12 rounded-full flex items-center justify-center 
                                {{ $warehouseTransfer->status == 'pending' ? 'bg-yellow-500 text-white' : 'bg-green-500 text-white' }}">
                                    <i class="fas fa-file-alt"></i>
                                </div>
                                <p class="text-xs mt-2">Pending</p>
                            </div>
                            <div
                                class="flex-1 h-1 {{ in_array($warehouseTransfer->status, ['in_transit', 'completed']) ? 'bg-green-500' : 'bg-gray-300' }}">
                            </div>
                            <div class="flex items-center text-center flex-col">
                                <div
                                    class="w-12 h-12 rounded-full flex items-center justify-center 
                                {{ $warehouseTransfer->status == 'in_transit' ? 'bg-blue-500 text-white' : ($warehouseTransfer->status == 'completed' ? 'bg-green-500 text-white' : 'bg-gray-300 text-gray-600') }}">
                                    <i class="fas fa-truck"></i>
                                </div>
                                <p class="text-xs mt-2">In Transit</p>
                            </div>
                            <div
                                class="flex-1 h-1 {{ $warehouseTransfer->status == 'completed' ? 'bg-green-500' : 'bg-gray-300' }}">
                            </div>
                            <div class="flex items-center text-center flex-col">
                                <div
                                    class="w-12 h-12 rounded-full flex items-center justify-center 
                                {{ $warehouseTransfer->status == 'completed' ? 'bg-green-500 text-white' : 'bg-gray-300 text-gray-600' }}">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                                <p class="text-xs mt-2">Completed</p>
                            </div>
                        </div>
                    </div>
                </div>

                @if ($warehouseTransfer->status == 'cancelled')
                    <div class="mt-4 p-3 bg-red-100 border border-red-300 rounded">
                        <p class="text-red-800 font-semibold"><i class="fas fa-times-circle mr-2"></i>Transfer ini telah
                            dibatalkan</p>
                    </div>
                @endif
            </div>

            <!-- Info Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div class="bg-blue-50 p-4 rounded-lg border-l-4 border-blue-500">
                    <h3 class="font-semibold text-gray-700 mb-3">Informasi Transfer</h3>
                    <dl class="space-y-2">
                        <div class="flex justify-between">
                            <dt class="text-gray-600">Kode:</dt>
                            <dd class="font-semibold">{{ $warehouseTransfer->transaction_code }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-gray-600">Tanggal:</dt>
                            <dd class="font-semibold">{{ $warehouseTransfer->transaction_date->format('d/m/Y') }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-gray-600">Status:</dt>
                            <dd>{!! $warehouseTransfer->status_badge !!}</dd>
                        </div>
                    </dl>
                </div>

                <div class="bg-green-50 p-4 rounded-lg border-l-4 border-green-500">
                    <h3 class="font-semibold text-gray-700 mb-3">Warehouse Asal</h3>
                    <dl class="space-y-2">
                        <div>
                            <dt class="text-gray-600 text-sm">Nama:</dt>
                            <dd class="font-semibold">{{ $warehouseTransfer->fromWarehouse->name }}</dd>
                        </div>
                        <div>
                            <dt class="text-gray-600 text-sm">Lokasi:</dt>
                            <dd class="text-sm">{{ $warehouseTransfer->fromWarehouse->city }},
                                {{ $warehouseTransfer->fromWarehouse->province }}</dd>
                        </div>
                    </dl>
                </div>

                <div class="bg-purple-50 p-4 rounded-lg border-l-4 border-purple-500">
                    <h3 class="font-semibold text-gray-700 mb-3">Warehouse Tujuan</h3>
                    <dl class="space-y-2">
                        <div>
                            <dt class="text-gray-600 text-sm">Nama:</dt>
                            <dd class="font-semibold">{{ $warehouseTransfer->toWarehouse->name }}</dd>
                        </div>
                        <div>
                            <dt class="text-gray-600 text-sm">Lokasi:</dt>
                            <dd class="text-sm">{{ $warehouseTransfer->toWarehouse->city }},
                                {{ $warehouseTransfer->toWarehouse->province }}</dd>
                        </div>
                    </dl>
                </div>
            </div>

            <!-- User Info -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h3 class="font-semibold text-gray-700 mb-3">Informasi Pengguna</h3>
                    <dl class="space-y-2">
                        <div class="flex justify-between">
                            <dt class="text-gray-600">Dibuat Oleh:</dt>
                            <dd class="font-semibold">{{ $warehouseTransfer->user->name }}</dd>
                        </div>
                        @if ($warehouseTransfer->approved_by)
                            <div class="flex justify-between">
                                <dt class="text-gray-600">Diapprove Oleh:</dt>
                                <dd class="font-semibold">{{ $warehouseTransfer->approver->name }}</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-gray-600">Waktu Approve:</dt>
                                <dd class="font-semibold">{{ $warehouseTransfer->approved_at->format('d/m/Y H:i') }}</dd>
                            </div>
                        @endif
                        @if ($warehouseTransfer->completed_at)
                            <div class="flex justify-between">
                                <dt class="text-gray-600">Waktu Selesai:</dt>
                                <dd class="font-semibold">{{ $warehouseTransfer->completed_at->format('d/m/Y H:i') }}</dd>
                            </div>
                        @endif
                    </dl>
                </div>

                @if ($warehouseTransfer->notes)
                    <div class="bg-yellow-50 p-4 rounded-lg">
                        <h3 class="font-semibold text-gray-700 mb-2">Catatan</h3>
                        <p class="text-gray-700">{{ $warehouseTransfer->notes }}</p>
                    </div>
                @endif
            </div>

            <!-- Detail Items -->
            <div class="border-t pt-6">
                <h3 class="text-lg font-semibold mb-4">Detail Item Transfer</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Kode Item</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Nama Item</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Kategori</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Quantity</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Catatan</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($warehouseTransfer->details as $detail)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        {{ $detail->item->code }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-900">
                                        {{ $detail->item->name }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-900">
                                        {{ $detail->item->category->name }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">
                                        {{ number_format($detail->quantity, 2) }} {{ $detail->item->unit }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-900">
                                        {{ $detail->notes ?? '-' }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Info Box -->
            <div class="mt-6 bg-blue-50 border-l-4 border-blue-500 p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-info-circle text-blue-500 text-xl"></i>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-blue-800">Informasi Proses Transfer</h3>
                        <div class="mt-2 text-sm text-blue-700">
                            <ul class="list-disc list-inside space-y-1">
                                <li><strong>Pending:</strong> Transfer menunggu approval</li>
                                <li><strong>In Transit:</strong> Stock sudah dikurangi dari warehouse asal dan barang dalam
                                    perjalanan</li>
                                <li><strong>Completed:</strong> Stock sudah ditambahkan ke warehouse tujuan</li>
                                <li><strong>Cancelled:</strong> Transfer dibatalkan, stock dikembalikan jika sudah dikurangi
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
