@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-6 lg:py-8">
        <div class="flex flex-col xl:flex-row xl:justify-between xl:items-start gap-4 mb-6">
            <div>
                <h1 class="text-2xl lg:text-3xl font-bold text-gray-800">Daftar Tagihan</h1>
                <p class="text-sm text-gray-600 mt-1">Kelola tagihan, pembayaran, dan isolir pelanggan.</p>
            </div>

            <div class="flex flex-wrap gap-2">
                <form action="{{ route('billings.check-overdue') }}" method="POST"
                    onsubmit="return confirm('Sistem akan mengecek tagihan jatuh tempo dan meng-isolir pelanggan terkait. Lanjutkan?')">
                    @csrf
                    <button type="submit"
                        class="inline-flex items-center justify-center bg-red-100 hover:bg-red-200 text-red-700 px-4 py-2.5 rounded-lg font-medium transition-colors text-sm">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                        Cek Isolir Otomatis
                    </button>
                </form>

                {{-- TOMBOL GENERATE MASSAL DIHAPUS --}}

                <a href="{{ route('billings.create') }}"
                    class="inline-flex items-center justify-center bg-blue-600 hover:bg-blue-700 text-white px-4 py-2.5 rounded-lg font-medium shadow-sm transition-colors text-sm">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Buat Manual
                </a>
            </div>
        </div>

        @if (session('success'))
            <div class="bg-green-50 border-l-4 border-green-500 text-green-800 px-4 py-3 rounded-r mb-4 shadow-sm">
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="bg-red-50 border-l-4 border-red-500 text-red-800 px-4 py-3 rounded-r mb-4 shadow-sm">
                {{ session('error') }}
            </div>
        @endif
        @if (session('info'))
            <div class="bg-blue-50 border-l-4 border-blue-500 text-blue-800 px-4 py-3 rounded-r mb-4 shadow-sm">
                {{ session('info') }}
            </div>
        @endif

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 lg:p-6 mb-6">
            <form method="GET" action="{{ route('billings.index') }}" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
                    <div class="md:col-span-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Cari Tagihan</label>
                        <div class="relative">
                            <input type="text" name="search" value="{{ request('search') }}"
                                placeholder="Cari No. Tagihan / Nama Customer..."
                                class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <svg class="w-5 h-5 text-gray-400 absolute left-3 top-3" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                    </div>
                    <div class="md:col-span-3">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Status Pembayaran</label>
                        <select name="status"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">Semua Status</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending (Belum
                                Bayar)</option>
                            <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Lunas</option>
                            <option value="overdue" {{ request('status') == 'overdue' ? 'selected' : '' }}>Terlambat
                                (Isolir)</option>
                        </select>
                    </div>
                    <div class="md:col-span-3 flex items-end gap-2">
                        <button type="submit"
                            class="flex-1 bg-gray-800 hover:bg-gray-900 text-white px-6 py-2.5 rounded-lg font-medium transition-colors shadow-sm">
                            Filter
                        </button>
                        <a href="{{ route('billings.index') }}"
                            class="flex-1 bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 px-6 py-2.5 rounded-lg font-medium text-center transition-colors">
                            Reset
                        </a>
                    </div>
                </div>
            </form>
        </div>

        <div class="hidden lg:block bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Info Tagihan</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Customer</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Paket / Layanan</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Jatuh Tempo</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Total</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Status</th>
                            <th class="px-6 py-4 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($billings as $billing)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex flex-col">
                                        <span class="font-mono text-sm font-bold text-blue-600">{{ $billing->billing_code }}</span>
                                        <span class="text-xs text-gray-500">{{ $billing->created_at->format('d M Y H:i') }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ optional($billing->customer)->company_name ?? (optional($billing->customer->user)->name ?? 'Deleted') }}
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        {{ optional($billing->customer->user)->phone ?? '-' }}
                                    </div>
                                </td>
                                
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $billing->package ? $billing->package->name : Str::limit($billing->notes, 30) }}
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        {{ $billing->start_date ? $billing->start_date->format('d M') : '-' }} -
                                        {{ $billing->end_date ? $billing->end_date->format('d M Y') : '-' }}
                                    </div>
                                    @if(!$billing->package)
                                        <span class="text-xs text-blue-600 italic font-medium">(Custom Package)</span>
                                    @endif
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium {{ $billing->isOverdue() && $billing->status != 'paid' ? 'text-red-600' : 'text-gray-900' }}">
                                        {{ $billing->due_date->format('d M Y') }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-bold text-gray-900">
                                        Rp {{ number_format($billing->amount, 0, ',', '.') }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full {{ $billing->getStatusBadgeClass() }}">
                                        {{ $billing->getStatusLabel() }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex justify-end items-center gap-2">
                                        @if ($billing->shouldShowPayButton())
                                            <a href="{{ route('billings.payment', $billing) }}"
                                                class="text-green-600 hover:text-green-900 bg-green-50 hover:bg-green-100 px-3 py-1 rounded-md transition">
                                                Bayar
                                            </a>
                                        @endif
                                        <a href="{{ route('billings.show', $billing) }}" class="text-gray-400 hover:text-gray-600">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                        </a>
                                        <form action="{{ route('billings.destroy', $billing) }}" method="POST" onsubmit="return confirm('Hapus?')" class="inline">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-gray-400 hover:text-red-600">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center bg-gray-50">
                                    <p class="text-gray-500">Belum ada data tagihan.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Mobile View --}}
        <div class="lg:hidden space-y-4">
            @forelse($billings as $billing)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
                    <div class="flex justify-between items-start mb-3 border-b border-gray-100 pb-3">
                        <div>
                            <span class="block font-mono text-sm font-bold text-gray-900">{{ $billing->billing_code }}</span>
                            <span class="text-xs text-gray-500">{{ $billing->created_at->format('d M Y') }}</span>
                        </div>
                        <span class="px-2.5 py-1 text-xs font-semibold rounded-full {{ $billing->getStatusBadgeClass() }}">
                            {{ $billing->getStatusLabel() }}
                        </span>
                    </div>

                    <div class="space-y-2 mb-4">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">Customer:</span>
                            <span class="font-medium text-gray-900 text-right">{{ optional($billing->customer->user)->name ?? 'N/A' }}</span>
                        </div>
                        
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">Paket:</span>
                            <span class="text-gray-900 text-right">
                                {{ $billing->package ? $billing->package->name : Str::limit($billing->notes, 30) }}
                                @if(!$billing->package) <span class="text-xs text-blue-600">(Custom)</span> @endif
                            </span>
                        </div>

                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">Jatuh Tempo:</span>
                            <span class="{{ $billing->isOverdue() ? 'text-red-600 font-bold' : 'text-gray-900' }} text-right">
                                {{ $billing->due_date->format('d/m/Y') }}
                            </span>
                        </div>
                        <div class="flex justify-between items-center bg-gray-50 p-2 rounded">
                            <span class="text-gray-500 text-sm">Total:</span>
                            <span class="font-bold text-gray-900 text-lg">Rp {{ number_format($billing->amount, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-2">
                        @if ($billing->shouldShowPayButton())
                            <a href="{{ route('billings.payment', $billing) }}"
                                class="col-span-2 bg-green-600 hover:bg-green-700 text-white text-center py-2 rounded-lg text-sm font-medium transition">
                                Bayar Sekarang
                            </a>
                        @endif
                        <a href="{{ route('billings.show', $billing) }}"
                            class="bg-gray-100 hover:bg-gray-200 text-gray-700 text-center py-2 rounded-lg text-sm font-medium transition">
                            Detail
                        </a>
                        <form action="{{ route('billings.destroy', $billing) }}" method="POST" onsubmit="return confirm('Hapus?')" class="w-full">
                            @csrf @method('DELETE')
                            <button type="submit"
                                class="w-full bg-red-50 hover:bg-red-100 text-red-600 py-2 rounded-lg text-sm font-medium transition">
                                Hapus
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="text-center py-8 bg-white rounded-lg border border-gray-200">
                    <p class="text-gray-500">Tidak ada data.</p>
                </div>
            @endforelse
        </div>

        <div class="mt-6">
            {{ $billings->links() }}
        </div>
    </div>
@endsection