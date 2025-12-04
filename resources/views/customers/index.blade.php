@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-6 lg:py-8">
        <!-- Header & Actions -->
        <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4 mb-6">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Data Pelanggan</h2>
                <p class="text-sm text-gray-500 mt-1">Kelola data pelanggan, lokasi pemasangan, dan status layanan.</p>
            </div>
            <a href="{{ route('customers.create') }}"
                class="inline-flex items-center justify-center px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg shadow-sm transition-colors">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Tambah Pelanggan
            </a>
        </div>

        <!-- Alert Messages -->
        @if (session('success'))
            <div
                class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded-r shadow-sm flex justify-between items-center animate-fade-in-down">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    <p class="text-green-700 font-medium">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        @if (session('error'))
            <div
                class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-r shadow-sm flex justify-between items-center animate-fade-in-down">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-red-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <p class="text-red-700 font-medium">{{ session('error') }}</p>
                </div>
            </div>
        @endif

        <!-- Filter & Search Section -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 mb-6">
            <form method="GET" action="{{ route('customers.index') }}">
                <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
                    <!-- Search Input -->
                    <div class="md:col-span-6 relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <input type="text" name="search" value="{{ request('search') }}"
                            class="pl-10 block w-full border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm py-2.5"
                            placeholder="Cari nama, ID, atau perusahaan...">
                    </div>

                    <!-- Status Filter -->
                    <div class="md:col-span-3">
                        <select name="status"
                            class="block w-full border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm py-2.5">
                            <option value="">Semua Status</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active (Normal)
                            </option>
                            <option value="isolir" {{ request('status') == 'isolir' ? 'selected' : '' }}>Isolir (Suspend)
                            </option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive
                                (Putus)</option>
                        </select>
                    </div>

                    <!-- Submit Button -->
                    <div class="md:col-span-3 flex gap-2">
                        <button type="submit"
                            class="w-full bg-gray-800 hover:bg-gray-900 text-white font-medium py-2.5 rounded-lg transition-colors shadow-sm">
                            Filter
                        </button>
                        <a href="{{ route('customers.index') }}"
                            class="w-full bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 font-medium py-2.5 rounded-lg text-center transition-colors">
                            Reset
                        </a>
                    </div>
                </div>
            </form>
        </div>

        <!-- Desktop Table -->
        <div class="hidden lg:block bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                Pelanggan</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                Lokasi & Area</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                Tipe</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                Status</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                Kontak</th>
                            <th class="px-6 py-4 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($customers as $customer)
                            <tr class="hover:bg-gray-50 transition duration-150">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div
                                            class="flex-shrink-0 h-10 w-10 bg-indigo-100 rounded-full flex items-center justify-center text-indigo-600 font-bold">
                                            {{ substr(optional($customer->user)->name ?? 'U', 0, 1) }}
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-bold text-gray-900">
                                                {{ optional($customer->user)->name ?? 'User Deleted' }}
                                            </div>
                                            @if ($customer->company_name)
                                                <div class="text-xs text-gray-500">{{ $customer->company_name }}</div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $customer->coverageArea->name ?? '-' }}</div>
                                    <div class="text-xs text-gray-500 truncate max-w-xs" title="{{ $customer->address }}">
                                        {{ Str::limit($customer->address, 30) }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if ($customer->type === 'residential')
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 border border-blue-200">
                                            Rumahan
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800 border border-purple-200">
                                            Bisnis
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if ($customer->status === 'active')
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 border border-green-200">
                                            <span class="w-1.5 h-1.5 mr-1.5 bg-green-500 rounded-full"></span>
                                            Aktif
                                        </span>
                                    @elseif ($customer->status === 'isolir')
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 border border-red-200">
                                            <span class="w-1.5 h-1.5 mr-1.5 bg-red-500 rounded-full"></span>
                                            Isolir
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 border border-gray-200">
                                            Non-Aktif
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ optional($customer->user)->phone ?? '-' }}</div>
                                    <div class="text-xs text-gray-500">{{ $customer->contact_person ?? '' }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex justify-end space-x-3">
                                        <a href="{{ route('customers.show', $customer) }}"
                                            class="text-blue-600 hover:text-blue-900 transition" title="Detail">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </a>
                                        <a href="{{ route('customers.edit', $customer) }}"
                                            class="text-yellow-600 hover:text-yellow-900 transition" title="Edit">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </a>
                                        <form action="{{ route('customers.destroy', $customer) }}" method="POST"
                                            class="inline"
                                            onsubmit="return confirm('PERINGATAN: Menghapus customer dapat menyebabkan error pada data tagihan. Lanjutkan?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900 transition"
                                                title="Hapus">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center bg-gray-50">
                                    <div class="flex flex-col items-center">
                                        <svg class="h-12 w-12 text-gray-300 mb-3" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                        </svg>
                                        <p class="text-lg font-medium text-gray-500">Tidak ada data pelanggan</p>
                                        <p class="text-sm text-gray-400">Silakan tambah pelanggan baru atau ubah filter
                                            pencarian.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Mobile Card View -->
        <div class="lg:hidden space-y-4">
            @forelse($customers as $customer)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
                    <div class="flex justify-between items-start mb-3 border-b border-gray-100 pb-3">
                        <div>
                            <span
                                class="block text-sm font-bold text-gray-900">{{ optional($customer->user)->name ?? 'User Deleted' }}</span>
                            @if ($customer->company_name)
                                <span class="text-xs text-gray-500">{{ $customer->company_name }}</span>
                            @endif
                        </div>
                        @if ($customer->status === 'active')
                            <span class="px-2 py-1 rounded text-xs font-semibold bg-green-100 text-green-800">Aktif</span>
                        @elseif ($customer->status === 'isolir')
                            <span class="px-2 py-1 rounded text-xs font-semibold bg-red-100 text-red-800">Isolir</span>
                        @else
                            <span
                                class="px-2 py-1 rounded text-xs font-semibold bg-gray-100 text-gray-800">Non-Aktif</span>
                        @endif
                    </div>

                    <div class="space-y-2 mb-4">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">Area:</span>
                            <span class="text-gray-900">{{ $customer->coverageArea->name ?? '-' }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">Tipe:</span>
                            <span class="text-gray-900 capitalize">{{ $customer->type }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">Kontak:</span>
                            <span class="text-gray-900">{{ optional($customer->user)->phone ?? '-' }}</span>
                        </div>
                    </div>

                    <div class="grid grid-cols-3 gap-2">
                        <a href="{{ route('customers.show', $customer) }}"
                            class="bg-gray-100 hover:bg-gray-200 text-gray-700 text-center py-2 rounded-lg text-sm font-medium transition">Detail</a>
                        <a href="{{ route('customers.edit', $customer) }}"
                            class="bg-yellow-50 hover:bg-yellow-100 text-yellow-700 text-center py-2 rounded-lg text-sm font-medium transition">Edit</a>
                        <form action="{{ route('customers.destroy', $customer) }}" method="POST" class="w-full"
                            onsubmit="return confirm('Hapus?')">
                            @csrf @method('DELETE')
                            <button type="submit"
                                class="w-full bg-red-50 hover:bg-red-100 text-red-600 py-2 rounded-lg text-sm font-medium transition">Hapus</button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="text-center py-8 bg-white rounded-lg border border-gray-200">
                    <p class="text-gray-500">Tidak ada data.</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $customers->withQueryString()->links() }}
        </div>
    </div>
@endsection
