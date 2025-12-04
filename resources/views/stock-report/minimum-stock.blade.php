@extends('layouts.app')

@section('title', 'Peringatan Stok Minimum')

@section('content')
    <div class="bg-white rounded-lg shadow-md">
        <div class="p-6 border-b border-gray-200">
            <div class="flex justify-between items-center">
                <div>
                    <h2 class="text-2xl font-bold text-gray-800 flex items-center">
                        <i class="fas fa-exclamation-triangle text-red-500 mr-3"></i>
                        Peringatan Stok Minimum
                    </h2>
                    <p class="text-gray-600 mt-1">Item dengan stok di bawah atau sama dengan minimum stock</p>
                </div>
                <div class="flex space-x-2">
                    <button onclick="window.print()" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                        <i class="fas fa-print mr-2"></i>Print
                    </button>
                    <a href="{{ route('stock-report.index') }}"
                        class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700">
                        <i class="fas fa-arrow-left mr-2"></i>Kembali
                    </a>
                </div>
            </div>
        </div>

        <div class="p-6">
            <!-- Summary Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-circle text-3xl text-red-500"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm text-gray-600">Total Item Kritis</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $lowStockItems->count() }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-yellow-50 border-l-4 border-yellow-500 p-4 rounded">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-warehouse text-3xl text-yellow-500"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm text-gray-600">Warehouse Terpengaruh</p>
                            <p class="text-2xl font-bold text-gray-900">
                                {{ $lowStockItems->groupBy('warehouse_id')->count() }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-orange-50 border-l-4 border-orange-500 p-4 rounded">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-boxes text-3xl text-orange-500"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm text-gray-600">Item Unik</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $itemsSummary->count() }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Detail Table -->
            <div class="mb-6">
                <h3 class="text-lg font-semibold mb-4">Detail Stock per Warehouse</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Warehouse</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kode Item</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Item</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kategori</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Stok Saat Ini
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Min. Stock</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Selisih</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Prioritas</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($lowStockItems as $stock)
                                <tr class="hover:bg-red-50">
                                    <td class="px-6 py-4 text-sm text-gray-900">{{ $stock->warehouse->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        {{ $stock->item->code }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-900">{{ $stock->item->name }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-900">{{ $stock->item->category->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-red-600">
                                        {{ number_format($stock->quantity) }} {{ $stock->item->unit }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ number_format($stock->item->minimum_stock) }} {{ $stock->item->unit }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-red-600">
                                        {{ number_format($stock->item->minimum_stock - $stock->quantity) }}
                                        {{ $stock->item->unit }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @php
                                            $percentage = ($stock->quantity / $stock->item->minimum_stock) * 100;
                                        @endphp
                                        @if ($percentage <= 25)
                                            <span
                                                class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                <i class="fas fa-exclamation-circle mr-1"></i> Sangat Kritis
                                            </span>
                                        @elseif($percentage <= 50)
                                            <span
                                                class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-orange-100 text-orange-800">
                                                <i class="fas fa-exclamation-triangle mr-1"></i> Kritis
                                            </span>
                                        @else
                                            <span
                                                class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                <i class="fas fa-exclamation mr-1"></i> Perhatian
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-6 py-8 text-center">
                                        <div class="flex flex-col items-center justify-center">
                                            <i class="fas fa-check-circle text-6xl text-green-500 mb-4"></i>
                                            <p class="text-xl font-semibold text-gray-700">Semua Stok Aman!</p>
                                            <p class="text-gray-500 mt-2">Tidak ada item dengan stok di bawah minimum</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Summary by Item -->
            @if ($itemsSummary->count() > 0)
                <div class="border-t pt-6">
                    <h3 class="text-lg font-semibold mb-4">Ringkasan per Item (Semua Warehouse)</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kode</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Item
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kategori
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total Stock
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Min. Stock
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Warehouse
                                        Terdampak</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($itemsSummary as $item)
                                    <tr>
                                        <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $item->code }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-900">{{ $item->name }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-900">{{ $item->category->name }}</td>
                                        <td class="px-6 py-4 text-sm font-semibold text-gray-900">
                                            {{ number_format($item->warehouse_stocks_sum_quantity ?? 0, 2) }}
                                            {{ $item->unit }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-900">
                                            {{ number_format($item->minimum_stock, 2) }} {{ $item->unit }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-900">
                                            {{ $lowStockItems->where('item_id', $item->id)->count() }} warehouse
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        </div>
    </div>

    @push('scripts')
        <style>
            @media print {

                nav,
                aside,
                button,
                .no-print {
                    display: none !important;
                }

                body {
                    background: white;
                }
            }
        </style>
    @endpush
@endsection
