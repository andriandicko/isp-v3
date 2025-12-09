@extends('layouts.app')

@section('title', 'Laporan Stok')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            
            <!-- 1. HEADER: Judul & Tombol Aksi Utama -->
            <div class="p-6 border-b border-gray-200 flex flex-col md:flex-row justify-between items-center gap-4 bg-gradient-to-r from-blue-50 to-white">
                <div>
                    <h2 class="text-2xl font-bold text-gray-800">Laporan Stok Gudang</h2>
                    <p class="text-sm text-gray-500 mt-1">Monitoring ketersediaan barang di seluruh gudang</p>
                </div>
                <div class="flex gap-2">
                    {{-- Tombol Cek Stok Menipis (Warning) --}}
                    <a href="{{ route('stock-report.minimum-stock') }}" class="inline-flex items-center px-4 py-2 bg-amber-100 text-amber-700 rounded-lg hover:bg-amber-200 transition text-sm font-medium shadow-sm">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                        Cek Stok Menipis
                    </a>
                    {{-- Tombol Print --}}
                    <button onclick="window.print()" class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition text-sm font-medium shadow-sm">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                        Print Laporan
                    </button>
                </div>
            </div>

            <!-- 2. FORM FILTER PENCARIAN -->
            <div class="p-6 bg-gray-50 border-b border-gray-200">
                <form method="GET" action="{{ route('stock-report.index') }}" class="grid grid-cols-1 md:grid-cols-12 gap-4">
                    
                    <!-- Filter Gudang -->
                    <div class="md:col-span-3">
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Gudang</label>
                        <div class="relative">
                            <select name="warehouse_id" class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 appearance-none bg-white text-sm">
                                <option value="">Semua Gudang</option>
                                @foreach ($warehouses as $warehouse)
                                    <option value="{{ $warehouse->id }}" {{ request('warehouse_id') == $warehouse->id ? 'selected' : '' }}>
                                        {{ $warehouse->name }}
                                    </option>
                                @endforeach
                            </select>
                            <svg class="w-5 h-5 text-gray-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                        </div>
                    </div>

                    <!-- Filter Kategori -->
                    <div class="md:col-span-3">
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Kategori</label>
                        <div class="relative">
                            <select name="category_id" class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 appearance-none bg-white text-sm">
                                <option value="">Semua Kategori</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            <svg class="w-5 h-5 text-gray-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                        </div>
                    </div>

                    <!-- Pencarian Text -->
                    <div class="md:col-span-4">
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Cari Barang</label>
                        <div class="relative">
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Kode atau Nama Item..."
                                class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 text-sm">
                            <svg class="w-5 h-5 text-gray-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        </div>
                    </div>

                    <!-- Tombol Filter & Reset -->
                    <div class="md:col-span-2 flex items-end gap-2">
                        <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white py-2.5 rounded-lg font-medium transition shadow-sm flex justify-center items-center text-sm">
                            Terapkan
                        </button>
                        <a href="{{ route('stock-report.index') }}" class="px-3 py-2.5 bg-white border border-gray-300 text-gray-600 rounded-lg hover:bg-gray-50 transition shadow-sm" title="Reset Filter">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                        </a>
                    </div>
                </form>
            </div>

            <!-- 3. TABEL DATA STOK -->
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="font-bold text-gray-700 text-lg">Data Stok Barang</h3>
                    <span class="text-xs bg-gray-100 text-gray-600 px-3 py-1 rounded-full border border-gray-200">Total: <strong>{{ $stocks->total() }}</strong> Item</span>
                </div>

                <div class="overflow-x-auto rounded-lg border border-gray-200">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Lokasi Gudang</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Barang</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Kategori</th>
                                <th class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Jumlah Stok</th>
                                <th class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($stocks as $stock)
                                <tr class="hover:bg-gray-50 transition">
                                    {{-- Kolom Gudang --}}
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <div class="font-medium">{{ $stock->warehouse->name }}</div>
                                        <div class="text-xs text-gray-500 flex items-center mt-0.5">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                            {{ $stock->warehouse->city ?? '-' }}
                                        </div>
                                    </td>
                                    
                                    {{-- Kolom Barang --}}
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-bold text-gray-900">{{ $stock->item->name }}</div>
                                        <div class="text-xs font-mono text-blue-600 bg-blue-50 px-1.5 py-0.5 rounded inline-block mt-1">
                                            {{ $stock->item->code }}
                                        </div>
                                    </td>

                                    {{-- Kolom Kategori --}}
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                        {{ $stock->item->category->name }}
                                    </td>

                                    {{-- Kolom Stok --}}
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <div class="text-base font-bold text-gray-900">{{ number_format($stock->quantity, 0, ',', '.') }}</div>
                                        <div class="text-xs text-gray-500 uppercase">{{ $stock->item->unit }}</div>
                                    </td>

                                    {{-- Kolom Status (Badge Visual) --}}
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        @if ($stock->quantity <= $stock->item->minimum_stock)
                                            {{-- Status Kritis (Merah) --}}
                                            <div class="flex flex-col items-center">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 mb-1">
                                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                                    Stok Kritis
                                                </span>
                                                <!-- PERBAIKAN: Menggunakan number_format dengan 0 desimal -->
                                                <span class="text-xs text-red-600 font-medium">Min: {{ number_format($stock->item->minimum_stock, 0, ',', '.') }}</span>
                                            </div>
                                        @elseif($stock->quantity <= $stock->item->minimum_stock * 1.5)
                                            {{-- Status Menipis (Kuning) --}}
                                            <div class="flex flex-col items-center">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 mb-1">
                                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                                    Menipis
                                                </span>
                                            </div>
                                        @else
                                            {{-- Status Aman (Hijau) --}}
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                                Aman
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-16 text-center">
                                        <div class="flex flex-col items-center justify-center text-gray-400">
                                            <svg class="w-16 h-16 mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                                            <p class="text-lg font-medium text-gray-600">Tidak ada data stok ditemukan</p>
                                            <p class="text-sm text-gray-400 mt-1">Coba sesuaikan filter pencarian Anda</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-6">
                    {{ $stocks->links() }}
                </div>
            </div>
        </div>
    </div>

    {{-- Style Khusus Print --}}
    @push('scripts')
        <style>
            @media print {
                nav, aside, .no-print, form, button, a {
                    display: none !important;
                }
                body { background: white; -webkit-print-color-adjust: exact; }
                .container { width: 100%; max-width: none; padding: 0; margin: 0; }
                .shadow-lg, .shadow-md { box-shadow: none !important; }
                .border, .border-b, .border-gray-200 { border: none !important; }
                table { width: 100%; border-collapse: collapse; }
                th, td { border: 1px solid #ddd; padding: 8px; }
            }
        </style>
    @endpush
@endsection