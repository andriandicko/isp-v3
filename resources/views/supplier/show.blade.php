@extends('layouts.app')

@section('title', 'Detail Item')

@section('content')
    <div class="bg-white rounded-lg shadow-md">
        <div class="p-6 border-b border-gray-200">
            <div class="flex justify-between items-center">
                <h2 class="text-2xl font-bold text-gray-800">Detail Item</h2>
                <div class="flex space-x-2">
                    <a href="{{ route('item.edit', $item) }}"
                        class="bg-yellow-600 text-white px-4 py-2 rounded hover:bg-yellow-700">
                        <i class="fas fa-edit mr-2"></i>Edit
                    </a>
                    <a href="{{ route('item.index') }}" class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700">
                        <i class="fas fa-arrow-left mr-2"></i>Kembali
                    </a>
                </div>
            </div>
        </div>

        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h3 class="font-semibold text-gray-700 mb-3">Informasi Item</h3>
                    <dl class="space-y-2">
                        <div class="flex justify-between">
                            <dt class="text-gray-600">Kode:</dt>
                            <dd class="font-semibold">{{ $item->code }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-gray-600">Nama:</dt>
                            <dd class="font-semibold">{{ $item->name }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-gray-600">Kategori:</dt>
                            <dd class="font-semibold">{{ $item->category->name }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-gray-600">Unit:</dt>
                            <dd class="font-semibold">{{ $item->unit }}</dd>
                        </div>
                    </dl>
                </div>

                <div class="bg-gray-50 p-4 rounded-lg">
                    <h3 class="font-semibold text-gray-700 mb-3">Stock & Harga</h3>
                    <dl class="space-y-2">
                        <div class="flex justify-between">
                            <dt class="text-gray-600">Total Stock:</dt>
                            <dd class="font-semibold text-blue-600">{{ number_format($item->total_stock, 2) }}
                                {{ $item->unit }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-gray-600">Minimum Stock:</dt>
                            <dd class="font-semibold">{{ number_format($item->minimum_stock, 2) }} {{ $item->unit }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-gray-600">Harga:</dt>
                            <dd class="font-semibold">Rp {{ number_format($item->price, 0, ',', '.') }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-gray-600">Status:</dt>
                            <dd>
                                <span
                                    class="px-2 py-1 text-xs font-semibold rounded-full 
                                {{ $item->status == 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $item->status == 'active' ? 'Aktif' : 'Tidak Aktif' }}
                                </span>
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>

            @if ($item->description)
                <div class="bg-blue-50 p-4 rounded-lg mb-6">
                    <h3 class="font-semibold text-gray-700 mb-2">Deskripsi</h3>
                    <p class="text-gray-700">{{ $item->description }}</p>
                </div>
            @endif

            <div class="border-t pt-6">
                <h3 class="text-lg font-semibold mb-4">Stock per Warehouse</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Warehouse</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Lokasi</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Quantity</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($item->warehouseStocks as $stock)
                                <tr>
                                    <td class="px-6 py-4 text-sm text-gray-900">{{ $stock->warehouse->name }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-900">{{ $stock->warehouse->city }}</td>
                                    <td class="px-6 py-4 text-sm font-semibold text-gray-900">
                                        {{ number_format($stock->quantity, 2) }} {{ $item->unit }}
                                    </td>
                                    <td class="px-6 py-4">
                                        @if ($stock->quantity <= $item->minimum_stock)
                                            <span
                                                class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                                <i class="fas fa-exclamation-triangle mr-1"></i> Rendah
                                            </span>
                                        @else
                                            <span
                                                class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                                <i class="fas fa-check-circle mr-1"></i> Aman
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-4 text-center text-gray-500">
                                        Belum ada stock di warehouse manapun
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
