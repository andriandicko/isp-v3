@extends('layouts.app')

@section('content')
    <div class="bg-white rounded-lg shadow-md">
        <!-- Header Section -->
        <div class="p-4 sm:p-6 border-b border-gray-200">
            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
                <div>
                    <h2 class="text-xl sm:text-2xl font-bold text-gray-800">Daftar Warehouse</h2>
                    <p class="text-sm text-gray-600 mt-1">Kelola data warehouse Anda</p>
                </div>
                <a href="{{ route('warehouse.create') }}"
                    class="inline-flex items-center justify-center bg-blue-600 text-white px-4 py-2.5 rounded-lg hover:bg-blue-700 transition-colors duration-200 shadow-sm">
                    <i class="fas fa-plus mr-2"></i>
                    <span>Tambah Warehouse</span>
                </a>
            </div>
        </div>

        <div class="p-4 sm:p-6">
            <!-- Desktop Table View -->
            <div class="hidden lg:block overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Kode
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Nama
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Lokasi
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Manager
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status
                            </th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($warehouses as $warehouse)
                            <tr class="hover:bg-gray-50 transition-colors duration-150">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm font-semibold text-gray-900">{{ $warehouse->code }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm font-medium text-gray-900">{{ $warehouse->name }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900">{{ $warehouse->city }}</div>
                                    <div class="text-xs text-gray-500">{{ $warehouse->province }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $warehouse->manager_name ?? '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span
                                        class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        {{ $warehouse->status == 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $warehouse->status == 'active' ? 'Aktif' : 'Tidak Aktif' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('warehouse.show', $warehouse) }}"
                                            class="inline-flex items-center justify-center w-8 h-8 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors duration-150"
                                            title="Lihat Detail">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('warehouse.edit', $warehouse) }}"
                                            class="inline-flex items-center justify-center w-8 h-8 text-yellow-600 hover:bg-yellow-50 rounded-lg transition-colors duration-150"
                                            title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('warehouse.destroy', $warehouse) }}" method="POST"
                                            class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="inline-flex items-center justify-center w-8 h-8 text-red-600 hover:bg-red-50 rounded-lg transition-colors duration-150"
                                                onclick="return confirm('Yakin ingin menghapus warehouse ini?')"
                                                title="Hapus">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <i class="fas fa-warehouse text-gray-300 text-5xl mb-4"></i>
                                        <p class="text-gray-500 font-medium">Tidak ada data warehouse</p>
                                        <p class="text-gray-400 text-sm mt-1">Mulai dengan menambahkan warehouse baru</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Mobile Card View -->
            <div class="lg:hidden space-y-4">
                @forelse($warehouses as $warehouse)
                    <div
                        class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm hover:shadow-md transition-shadow duration-200">
                        <!-- Header with Code and Status -->
                        <div class="flex items-center justify-between mb-3">
                            <span class="text-sm font-bold text-gray-900 bg-gray-100 px-3 py-1 rounded">
                                {{ $warehouse->code }}
                            </span>
                            <span
                                class="px-3 py-1 text-xs font-semibold rounded-full 
                                {{ $warehouse->status == 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $warehouse->status == 'active' ? 'Aktif' : 'Tidak Aktif' }}
                            </span>
                        </div>

                        <!-- Warehouse Name -->
                        <h3 class="text-base font-semibold text-gray-900 mb-3">{{ $warehouse->name }}</h3>

                        <!-- Details -->
                        <div class="space-y-2 mb-4">
                            <div class="flex items-start">
                                <i class="fas fa-map-marker-alt text-gray-400 w-5 mt-0.5"></i>
                                <div class="ml-2 text-sm">
                                    <span class="text-gray-900">{{ $warehouse->city }}</span>
                                    <span class="text-gray-500">, {{ $warehouse->province }}</span>
                                </div>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-user-tie text-gray-400 w-5"></i>
                                <span class="ml-2 text-sm text-gray-900">{{ $warehouse->manager_name ?? '-' }}</span>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="flex items-center gap-2 pt-3 border-t border-gray-100">
                            <a href="{{ route('warehouse.show', $warehouse) }}"
                                class="flex-1 inline-flex items-center justify-center px-4 py-2 bg-blue-50 text-blue-600 text-sm font-medium rounded-lg hover:bg-blue-100 transition-colors duration-150">
                                <i class="fas fa-eye mr-2"></i>
                                Detail
                            </a>
                            <a href="{{ route('warehouse.edit', $warehouse) }}"
                                class="flex-1 inline-flex items-center justify-center px-4 py-2 bg-yellow-50 text-yellow-600 text-sm font-medium rounded-lg hover:bg-yellow-100 transition-colors duration-150">
                                <i class="fas fa-edit mr-2"></i>
                                Edit
                            </a>
                            <form action="{{ route('warehouse.destroy', $warehouse) }}" method="POST" class="flex-1">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="w-full inline-flex items-center justify-center px-4 py-2 bg-red-50 text-red-600 text-sm font-medium rounded-lg hover:bg-red-100 transition-colors duration-150"
                                    onclick="return confirm('Yakin ingin menghapus warehouse ini?')">
                                    <i class="fas fa-trash mr-2"></i>
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-12">
                        <i class="fas fa-warehouse text-gray-300 text-5xl mb-4"></i>
                        <p class="text-gray-500 font-medium">Tidak ada data warehouse</p>
                        <p class="text-gray-400 text-sm mt-1">Mulai dengan menambahkan warehouse baru</p>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if ($warehouses->hasPages())
                <div class="mt-6">
                    {{ $warehouses->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
