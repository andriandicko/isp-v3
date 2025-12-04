@extends('layouts.app')

@section('title', 'Detail Warehouse')

@section('content')
    <div class="bg-gradient-to-br from-slate-50 to-blue-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8">

            <!-- Header Section -->
            <div class="mb-6 sm:mb-8">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h1 class="text-2xl sm:text-3xl font-bold text-slate-900">Detail Warehouse</h1>
                        <p class="mt-1 sm:mt-2 text-sm text-slate-600">Informasi lengkap warehouse dan inventory</p>
                    </div>
                    <div class="flex flex-wrap gap-2 sm:gap-3">
                        <a href="{{ route('warehouse.edit', $warehouse) }}"
                            class="flex-1 sm:flex-none inline-flex items-center justify-center px-4 py-2.5 bg-gradient-to-r from-yellow-500 to-yellow-600 text-white font-semibold rounded-xl hover:from-yellow-600 hover:to-yellow-700 transition-all duration-300 shadow-lg shadow-yellow-500/25">
                            <i class="fas fa-edit mr-2"></i>
                            <span>Edit</span>
                        </a>
                        <a href="{{ route('warehouse.index') }}"
                            class="flex-1 sm:flex-none inline-flex items-center justify-center px-4 py-2.5 bg-white text-slate-700 font-semibold rounded-xl hover:bg-slate-50 transition-all duration-300 shadow-lg border border-slate-200">
                            <i class="fas fa-arrow-left mr-2"></i>
                            <span>Kembali</span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Main Content Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-6">

                <!-- Left Column - Warehouse Info -->
                <div class="lg:col-span-2 space-y-4 sm:space-y-6">

                    <!-- Warehouse Information Card -->
                    <div class="bg-white rounded-2xl shadow-xl border border-slate-200/60 overflow-hidden">
                        <div class="bg-gradient-to-r from-orange-500 to-red-600 px-4 sm:px-6 py-4">
                            <div class="flex items-center">
                                <div
                                    class="w-10 h-10 sm:w-12 sm:h-12 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center flex-shrink-0">
                                    <i class="fas fa-warehouse text-white text-lg sm:text-xl"></i>
                                </div>
                                <div class="ml-3 sm:ml-4">
                                    <h2 class="text-lg sm:text-xl font-bold text-white">Informasi Warehouse</h2>
                                    <p class="text-orange-100 text-xs sm:text-sm">Data lengkap gudang</p>
                                </div>
                            </div>
                        </div>

                        <div class="p-4 sm:p-6">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
                                <div class="flex items-start space-x-3">
                                    <div
                                        class="flex-shrink-0 w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-barcode text-orange-600"></i>
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <p class="text-xs text-slate-500 font-medium uppercase">Kode Warehouse</p>
                                        <p class="text-base sm:text-lg font-bold text-slate-900 truncate">
                                            {{ $warehouse->code }}</p>
                                    </div>
                                </div>

                                <div class="flex items-start space-x-3">
                                    <div
                                        class="flex-shrink-0 w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-warehouse text-blue-600"></i>
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <p class="text-xs text-slate-500 font-medium uppercase">Nama Warehouse</p>
                                        <p class="text-base sm:text-lg font-bold text-slate-900 break-words">
                                            {{ $warehouse->name }}</p>
                                    </div>
                                </div>

                                <div class="flex items-start space-x-3">
                                    <div
                                        class="flex-shrink-0 w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-city text-green-600"></i>
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <p class="text-xs text-slate-500 font-medium uppercase">Kota</p>
                                        <p class="text-base sm:text-lg font-bold text-slate-900">{{ $warehouse->city }}</p>
                                    </div>
                                </div>

                                <div class="flex items-start space-x-3">
                                    <div
                                        class="flex-shrink-0 w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-map text-purple-600"></i>
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <p class="text-xs text-slate-500 font-medium uppercase">Provinsi</p>
                                        <p class="text-base sm:text-lg font-bold text-slate-900">{{ $warehouse->province }}
                                        </p>
                                    </div>
                                </div>

                                <div class="flex items-start space-x-3">
                                    <div
                                        class="flex-shrink-0 w-10 h-10 bg-indigo-100 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-phone text-indigo-600"></i>
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <p class="text-xs text-slate-500 font-medium uppercase">Telepon</p>
                                        <p class="text-base sm:text-lg font-bold text-slate-900">
                                            {{ $warehouse->phone ?? '-' }}</p>
                                    </div>
                                </div>

                                <div class="flex items-start space-x-3">
                                    <div
                                        class="flex-shrink-0 w-10 h-10 bg-pink-100 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-user-tie text-pink-600"></i>
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <p class="text-xs text-slate-500 font-medium uppercase">Manager</p>
                                        <p class="text-base sm:text-lg font-bold text-slate-900 break-words">
                                            {{ $warehouse->manager_name ?? '-' }}</p>
                                    </div>
                                </div>
                            </div>

                            <div
                                class="mt-4 sm:mt-6 p-4 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl border border-blue-100">
                                <div class="flex items-start space-x-3">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-map-marker-alt text-blue-600 text-lg"></i>
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <p class="text-xs text-blue-700 font-semibold uppercase mb-1">Alamat Lengkap</p>
                                        <p class="text-sm text-slate-700 break-words">{{ $warehouse->address }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Stock Items -->
                    <div class="bg-white rounded-2xl shadow-xl border border-slate-200/60 overflow-hidden">
                        <div class="bg-gradient-to-r from-teal-500 to-cyan-600 px-4 sm:px-6 py-4">
                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                                <div class="flex items-center">
                                    <div
                                        class="w-10 h-10 sm:w-12 sm:h-12 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center flex-shrink-0">
                                        <i class="fas fa-boxes text-white text-lg sm:text-xl"></i>
                                    </div>
                                    <div class="ml-3 sm:ml-4">
                                        <h2 class="text-lg sm:text-xl font-bold text-white">Stock Items</h2>
                                        <p class="text-teal-100 text-xs sm:text-sm">Daftar barang di warehouse</p>
                                    </div>
                                </div>
                                <div class="bg-white/20 backdrop-blur-sm px-4 py-2 rounded-lg self-start sm:self-auto">
                                    <p class="text-white font-bold text-base sm:text-lg">{{ $warehouse->stocks->count() }}
                                        Items</p>
                                </div>
                            </div>
                        </div>

                        <div class="p-4 sm:p-6">
                            <div class="hidden lg:block overflow-x-auto">
                                <table class="min-w-full divide-y divide-slate-200">
                                    <thead>
                                        <tr class="bg-slate-50">
                                            <th class="px-4 py-3 text-left text-xs font-bold text-slate-700 uppercase">Kode
                                            </th>
                                            <th class="px-4 py-3 text-left text-xs font-bold text-slate-700 uppercase">Nama
                                            </th>
                                            <th class="px-4 py-3 text-left text-xs font-bold text-slate-700 uppercase">
                                                Kategori</th>
                                            <th class="px-4 py-3 text-left text-xs font-bold text-slate-700 uppercase">Qty
                                            </th>
                                            <th class="px-4 py-3 text-left text-xs font-bold text-slate-700 uppercase">Min
                                            </th>
                                            <th class="px-4 py-3 text-left text-xs font-bold text-slate-700 uppercase">
                                                Status</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-slate-200">
                                        @forelse($warehouse->stocks as $stock)
                                            <tr class="hover:bg-slate-50 transition-colors">
                                                <td class="px-4 py-3 whitespace-nowrap text-sm font-bold text-slate-900">
                                                    {{ $stock->item->code }}</td>
                                                <td class="px-4 py-3 text-sm text-slate-900">{{ $stock->item->name }}</td>
                                                <td class="px-4 py-3 whitespace-nowrap">
                                                    <span
                                                        class="px-2.5 py-1 text-xs font-semibold rounded-full bg-purple-100 text-purple-800">
                                                        {{ $stock->item->category->name }}
                                                    </span>
                                                </td>
                                                <td class="px-4 py-3 whitespace-nowrap text-sm font-bold text-blue-600">
                                                    {{ number_format($stock->quantity, 2) }} {{ $stock->item->unit }}
                                                </td>
                                                <td class="px-4 py-3 whitespace-nowrap text-sm text-slate-600">
                                                    {{ number_format($stock->item->minimum_stock, 2) }}
                                                    {{ $stock->item->unit }}
                                                </td>
                                                <td class="px-4 py-3 whitespace-nowrap">
                                                    @if ($stock->quantity <= $stock->item->minimum_stock)
                                                        <span
                                                            class="px-2.5 py-1 inline-flex items-center text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                                            <i class="fas fa-exclamation-triangle mr-1"></i>Rendah
                                                        </span>
                                                    @elseif($stock->quantity <= $stock->item->minimum_stock * 1.5)
                                                        <span
                                                            class="px-2.5 py-1 inline-flex items-center text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                            <i class="fas fa-exclamation-circle mr-1"></i>Perhatian
                                                        </span>
                                                    @else
                                                        <span
                                                            class="px-2.5 py-1 inline-flex items-center text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                                            <i class="fas fa-check-circle mr-1"></i>Aman
                                                        </span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="px-4 py-12 text-center">
                                                    <i class="fas fa-boxes text-slate-300 text-5xl mb-4"></i>
                                                    <p class="text-slate-500 font-medium">Belum ada stock</p>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            <div class="lg:hidden space-y-3">
                                @forelse($warehouse->stocks as $stock)
                                    <div class="bg-slate-50 border border-slate-200 rounded-xl p-4">
                                        <div class="flex items-start justify-between mb-3">
                                            <div class="flex-1 min-w-0">
                                                <p class="text-sm font-bold text-slate-900">{{ $stock->item->code }}</p>
                                                <p class="text-xs text-slate-600 mt-0.5">{{ $stock->item->name }}</p>
                                            </div>
                                            <span
                                                class="ml-2 px-2.5 py-1 text-xs font-semibold rounded-full bg-purple-100 text-purple-800">
                                                {{ $stock->item->category->name }}
                                            </span>
                                        </div>
                                        <div class="grid grid-cols-2 gap-3 mb-3">
                                            <div>
                                                <p class="text-xs text-slate-500 mb-1">Quantity</p>
                                                <p class="text-sm font-bold text-blue-600">
                                                    {{ number_format($stock->quantity, 2) }} {{ $stock->item->unit }}</p>
                                            </div>
                                            <div>
                                                <p class="text-xs text-slate-500 mb-1">Min. Stock</p>
                                                <p class="text-sm font-semibold text-slate-600">
                                                    {{ number_format($stock->item->minimum_stock, 2) }}
                                                    {{ $stock->item->unit }}</p>
                                            </div>
                                        </div>
                                        @if ($stock->quantity <= $stock->item->minimum_stock)
                                            <span
                                                class="inline-flex items-center px-2.5 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                                <i class="fas fa-exclamation-triangle mr-1"></i>Stock Rendah
                                            </span>
                                        @elseif($stock->quantity <= $stock->item->minimum_stock * 1.5)
                                            <span
                                                class="inline-flex items-center px-2.5 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                <i class="fas fa-exclamation-circle mr-1"></i>Perlu Perhatian
                                            </span>
                                        @else
                                            <span
                                                class="inline-flex items-center px-2.5 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                                <i class="fas fa-check-circle mr-1"></i>Stock Aman
                                            </span>
                                        @endif
                                    </div>
                                @empty
                                    <div class="text-center py-12">
                                        <i class="fas fa-boxes text-slate-300 text-5xl mb-4"></i>
                                        <p class="text-slate-500 font-medium">Belum ada stock</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Right Column -->
                <div class="space-y-4 sm:space-y-6">

                    <!-- Status Card -->
                    <div class="bg-white rounded-2xl shadow-xl border border-slate-200/60 p-4 sm:p-6">
                        <h3 class="text-base sm:text-lg font-bold text-slate-900 mb-4">Status</h3>
                        <div class="flex items-center justify-center p-4 sm:p-6">
                            @if ($warehouse->status == 'active')
                                <div class="text-center">
                                    <div
                                        class="w-16 h-16 sm:w-20 sm:h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                        <i class="fas fa-check-circle text-green-600 text-3xl sm:text-4xl"></i>
                                    </div>
                                    <p class="text-xl sm:text-2xl font-bold text-green-600">Aktif</p>
                                    <p class="text-xs sm:text-sm text-slate-500 mt-1">Warehouse beroperasi</p>
                                </div>
                            @else
                                <div class="text-center">
                                    <div
                                        class="w-16 h-16 sm:w-20 sm:h-20 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                        <i class="fas fa-times-circle text-red-600 text-3xl sm:text-4xl"></i>
                                    </div>
                                    <p class="text-xl sm:text-2xl font-bold text-red-600">Tidak Aktif</p>
                                    <p class="text-xs sm:text-sm text-slate-500 mt-1">Non-operasional</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Statistics -->
                    <div
                        class="bg-gradient-to-br from-violet-500 to-purple-600 rounded-2xl shadow-xl p-4 sm:p-6 text-white">
                        <h3 class="text-base sm:text-lg font-bold mb-4 flex items-center">
                            <i class="fas fa-chart-bar mr-2"></i>Statistik
                        </h3>
                        <div class="space-y-3">
                            <div class="flex items-center justify-between p-3 bg-white/10 backdrop-blur-sm rounded-xl">
                                <span class="text-sm font-medium">Total Item</span>
                                <span class="text-lg font-bold">{{ $warehouse->stocks->count() }}</span>
                            </div>
                            <div class="flex items-center justify-between p-3 bg-white/10 backdrop-blur-sm rounded-xl">
                                <span class="text-sm font-medium">Transaksi Masuk</span>
                                <span class="text-lg font-bold">{{ $warehouse->incomingGoods->count() }}</span>
                            </div>
                            <div class="flex items-center justify-between p-3 bg-white/10 backdrop-blur-sm rounded-xl">
                                <span class="text-sm font-medium">Transaksi Keluar</span>
                                <span class="text-lg font-bold">{{ $warehouse->outgoingGoods->count() }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Transactions -->
                    <div class="bg-white rounded-2xl shadow-xl border border-slate-200/60 overflow-hidden">
                        <div class="px-4 sm:px-6 py-4 border-b border-slate-200">
                            <h3 class="text-base sm:text-lg font-bold text-slate-900">Transaksi Terbaru</h3>
                        </div>
                        <div class="p-4 space-y-3">
                            @forelse($warehouse->incomingGoods()->latest()->take(3)->get() as $incoming)
                                <div class="p-3 bg-green-50 rounded-xl border border-green-100">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center min-w-0 flex-1">
                                            <div class="w-8 h-8 bg-green-500 rounded-lg flex items-center justify-center">
                                                <i class="fas fa-arrow-down text-white text-sm"></i>
                                            </div>
                                            <div class="ml-3 min-w-0">
                                                <p class="text-sm font-semibold text-slate-900 truncate">
                                                    {{ $incoming->transaction_code }}</p>
                                                <p class="text-xs text-slate-500">
                                                    {{ $incoming->transaction_date->format('d M Y') }}</p>
                                            </div>
                                        </div>
                                        <span
                                            class="ml-2 px-2 py-1 text-xs font-semibold rounded-full {{ $incoming->status == 'approved' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                            {{ ucfirst($incoming->status) }}
                                        </span>
                                    </div>
                                </div>
                            @empty
                                <p class="text-center py-6 text-slate-400 text-sm">Belum ada transaksi masuk</p>
                            @endforelse

                            @forelse($warehouse->outgoingGoods()->latest()->take(3)->get() as $outgoing)
                                <div class="p-3 bg-red-50 rounded-xl border border-red-100">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center min-w-0 flex-1">
                                            <div class="w-8 h-8 bg-red-500 rounded-lg flex items-center justify-center">
                                                <i class="fas fa-arrow-up text-white text-sm"></i>
                                            </div>
                                            <div class="ml-3 min-w-0">
                                                <p class="text-sm font-semibold text-slate-900 truncate">
                                                    {{ $outgoing->transaction_code }}</p>
                                                <p class="text-xs text-slate-500">
                                                    {{ $outgoing->transaction_date->format('d M Y') }}</p>
                                            </div>
                                        </div>
                                        <span
                                            class="ml-2 px-2 py-1 text-xs font-semibold rounded-full {{ $outgoing->status == 'approved' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                            {{ ucfirst($outgoing->status) }}
                                        </span>
                                    </div>
                                </div>
                            @empty
                                <p class="text-center py-6 text-slate-400 text-sm">Belum ada transaksi keluar</p>
                            @endforelse
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="bg-white rounded-2xl shadow-xl border border-slate-200/60 overflow-hidden">
                        <div class="px-4 sm:px-6 py-4 border-b border-slate-200">
                            <h3 class="text-base sm:text-lg font-bold text-slate-900">Quick Actions</h3>
                        </div>
                        <div class="p-4 space-y-2">
                            <a href="{{ route('incoming-goods.create') }}"
                                class="flex items-center justify-between p-3 bg-gradient-to-r from-green-50 to-emerald-50 rounded-xl hover:from-green-100 hover:to-emerald-100 transition-all border border-green-200">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-green-500 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-arrow-down text-white"></i>
                                    </div>
                                    <span class="ml-3 text-sm font-semibold text-slate-900">Barang Masuk</span>
                                </div>
                                <i class="fas fa-chevron-right text-slate-400"></i>
                            </a>
                            <a href="{{ route('outgoing-goods.create') }}"
                                class="flex items-center justify-between p-3 bg-gradient-to-r from-red-50 to-rose-50 rounded-xl hover:from-red-100 hover:to-rose-100 transition-all border border-red-200">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-red-500 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-arrow-up text-white"></i>
                                    </div>
                                    <span class="ml-3 text-sm font-semibold text-slate-900">Barang Keluar</span>
                                </div>
                                <i class="fas fa-chevron-right text-slate-400"></i>
                            </a>
                            <a href="{{ route('warehouse-transfer.create') }}"
                                class="flex items-center justify-between p-3 bg-gradient-to-r from-blue-50 to-cyan-50 rounded-xl hover:from-blue-100 hover:to-cyan-100 transition-all border border-blue-200">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-blue-500 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-exchange-alt text-white"></i>
                                    </div>
                                    <span class="ml-3 text-sm font-semibold text-slate-900">Transfer Gudang</span>
                                </div>
                                <i class="fas fa-chevron-right text-slate-400"></i>
                            </a>
                            <a href="{{ route('stock-report.index') }}"
                                class="flex items-center justify-between p-3 bg-gradient-to-r from-purple-50 to-violet-50 rounded-xl hover:from-purple-100 hover:to-violet-100 transition-all border border-purple-200">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-purple-500 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-chart-bar text-white"></i>
                                    </div>
                                    <span class="ml-3 text-sm font-semibold text-slate-900">Laporan Stock</span>
                                </div>
                                <i class="fas fa-chevron-right text-slate-400"></i>
                            </a>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
@endsection
