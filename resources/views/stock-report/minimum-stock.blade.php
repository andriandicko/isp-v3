@extends('layouts.app')

@section('title', 'Peringatan Stok Minimum')

@section('content')
    <div class="container mx-auto px-4 py-8">
        
        <!-- HEADER & ACTIONS -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-6 no-print">
            <div class="p-6 border-b border-gray-200 flex flex-col md:flex-row justify-between items-center gap-4 bg-gradient-to-r from-red-50 to-white">
                <div>
                    <h2 class="text-2xl font-bold text-gray-800 flex items-center">
                        <svg class="w-8 h-8 text-red-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                        Laporan Stok Menipis
                    </h2>
                    <p class="text-sm text-gray-600 mt-1 ml-11">Daftar barang yang stoknya berada di bawah batas minimum.</p>
                </div>
                <div class="flex gap-2">
                    <button onclick="window.print()" class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition text-sm font-medium shadow-sm">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                        Cetak Laporan
                    </button>
                    <a href="{{ route('stock-report.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg transition text-sm font-medium shadow-sm">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                        Kembali
                    </a>
                </div>
            </div>
        </div>

        <!-- SUMMARY CARDS -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8 print-grid">
            
            <!-- Card 1: Total Item Kritis -->
            <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-red-500 flex items-center">
                <div class="p-3 rounded-full bg-red-100 text-red-600 mr-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500 uppercase">Total Item Kritis</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $lowStockItems->count() }}</p>
                </div>
            </div>

            <!-- Card 2: Warehouse Terdampak -->
            <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-yellow-500 flex items-center">
                <div class="p-3 rounded-full bg-yellow-100 text-yellow-600 mr-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500 uppercase">Gudang Terdampak</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $lowStockItems->groupBy('warehouse_id')->count() }}</p>
                </div>
            </div>

            <!-- Card 3: Jenis Item Unik -->
            <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-orange-500 flex items-center">
                <div class="p-3 rounded-full bg-orange-100 text-orange-600 mr-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500 uppercase">Jenis Barang</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $itemsSummary->count() }}</p>
                </div>
            </div>
        </div>

        <!-- DETAIL TABLE -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
                <h3 class="font-bold text-gray-700 text-lg">Rincian Stok Per Gudang</h3>
                <span class="text-xs text-gray-500 italic">*Data diurutkan berdasarkan prioritas kebutuhan</span>
            </div>
            
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Lokasi Gudang</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Barang</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Kategori</th>
                            <th class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Sisa Stok</th>
                            <th class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Min. Stok</th>
                            <th class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($lowStockItems as $stock)
                            @php
                                $percentage = 0;
                                if($stock->item->minimum_stock > 0) {
                                    $percentage = ($stock->quantity / $stock->item->minimum_stock) * 100;
                                }
                            @endphp
                            <tr class="hover:bg-red-50 transition">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <div class="font-medium">{{ $stock->warehouse->name }}</div>
                                    <div class="text-xs text-gray-500">{{ $stock->warehouse->city ?? '-' }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-bold text-gray-900">{{ $stock->item->name }}</div>
                                    <div class="text-xs font-mono text-blue-600 bg-blue-50 px-1.5 py-0.5 rounded inline-block mt-1">
                                        {{ $stock->item->code }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                    {{ $stock->item->category->name }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <span class="text-lg font-bold text-red-600">{{ number_format($stock->quantity) }}</span>
                                    <span class="text-xs text-gray-500 uppercase ml-1">{{ $stock->item->unit }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-600">
                                    {{ number_format($stock->item->minimum_stock) }} {{ $stock->item->unit }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    @if ($percentage <= 25)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                            Sangat Kritis
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                            Perlu Restock
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                    <div class="flex flex-col items-center justify-center">
                                        <svg class="w-16 h-16 text-green-200 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        <p class="text-lg font-medium text-gray-600">Semua Stok Aman!</p>
                                        <p class="text-sm text-gray-400">Tidak ada barang yang berada di bawah batas minimum.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @push('scripts')
        <style>
            @media print {
                nav, aside, .no-print, button, a {
                    display: none !important;
                }
                body { background: white; -webkit-print-color-adjust: exact; }
                .container { width: 100%; max-width: none; padding: 0; margin: 0; }
                .shadow-lg, .shadow-md { box-shadow: none !important; }
                .border, .border-b, .border-l-4 { border: 1px solid #ddd !important; }
                
                /* Atur Grid agar tetap rapi saat diprint */
                .print-grid {
                    display: grid;
                    grid-template-columns: repeat(3, 1fr);
                    gap: 1rem;
                }
            }
        </style>
    @endpush
@endsection