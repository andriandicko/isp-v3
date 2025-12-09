@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-5xl mx-auto">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
                <div class="flex items-center">
                    <a href="{{ route('billings.index') }}" class="text-gray-500 hover:text-gray-700 mr-4 transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                    </a>
                    <div>
                        <div class="flex items-center gap-3">
                            <h1 class="text-2xl font-bold text-gray-800">Detail Tagihan</h1>
                            <span
                                class="font-mono text-lg text-blue-600 bg-blue-50 px-2 py-0.5 rounded border border-blue-100">#{{ $billing->billing_code }}</span>
                        </div>
                        <p class="text-sm text-gray-500 mt-1">Dibuat pada {{ $billing->created_at->format('d F Y, H:i') }}
                        </p>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <span class="px-4 py-2 text-sm font-semibold rounded-full {{ $billing->getStatusBadgeClass() }}">
                        {{ $billing->getStatusLabel() }}
                    </span>

                    <div class="flex gap-2">
                        @if ($billing->shouldShowPayButton())
                            <a href="{{ route('billings.payment', $billing) }}"
                                class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-medium shadow transition">
                                Bayar Sekarang
                            </a>
                        @endif

                        <form action="{{ route('billings.destroy', $billing) }}" method="POST"
                            onsubmit="return confirm('Hapus tagihan ini?')" class="inline">
                            @csrf @method('DELETE')
                            <button type="submit"
                                class="bg-white border border-red-200 text-red-600 hover:bg-red-50 px-3 py-2 rounded-lg text-sm font-medium transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                    </path>
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            @if (session('success'))
                <div class="bg-green-50 border-l-4 border-green-500 text-green-800 px-4 py-3 rounded mb-6 shadow-sm">
                    {{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="bg-red-50 border-l-4 border-red-500 text-red-800 px-4 py-3 rounded mb-6 shadow-sm">
                    {{ session('error') }}</div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                <div class="lg:col-span-2 space-y-6">

                    <!-- RINCIAN KEUANGAN -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                            <h2 class="font-semibold text-gray-800 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-gray-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                    </path>
                                </svg>
                                Rincian Keuangan
                            </h2>
                        </div>
                        <div class="p-6">
                            <div class="flex justify-between items-end mb-6 pb-6 border-b border-gray-100">
                                <div>
                                    <p class="text-sm text-gray-500 mb-1">Total Tagihan</p>
                                    <p class="text-3xl font-bold text-gray-900">Rp
                                        {{ number_format($billing->amount, 0, ',', '.') }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm text-gray-500 mb-1">Paket Internet</p>
                                    
                                    {{-- PERBAIKAN: Tampilkan nama paket ATAU notes jika custom --}}
                                    <p class="font-semibold text-blue-600">
                                        {{ $billing->package ? $billing->package->name : ($billing->notes ?? 'Custom Package') }}
                                    </p>
                                    @if(!$billing->package)
                                        <p class="text-xs text-gray-400 mt-1">(Custom Price)</p>
                                    @endif
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label
                                        class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Periode
                                        Pemakaian</label>
                                    <p class="text-gray-800 font-medium">
                                        {{ $billing->start_date ? $billing->start_date->format('d M Y') : '-' }}
                                        <span class="text-gray-400 mx-1">s/d</span>
                                        {{ $billing->end_date ? $billing->end_date->format('d M Y') : '-' }}
                                    </p>
                                </div>
                                <div>
                                    <label
                                        class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Jatuh
                                        Tempo</label>
                                    <p class="text-gray-800 font-medium {{ $billing->isOverdue() ? 'text-red-600' : '' }}">
                                        {{ $billing->due_date->format('d F Y') }}
                                        @if ($billing->status == 'pending')
                                            <span class="text-xs font-normal ml-1">
                                                ({{ $billing->getDaysUntilDue() }} hari lagi)
                                            </span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>

                        @if ($billing->status == 'paid')
                            <div class="bg-green-50 px-6 py-4 border-t border-green-100">
                                <h3 class="text-sm font-bold text-green-800 mb-3 uppercase">Bukti Pembayaran</h3>
                                <div class="grid grid-cols-2 gap-4 text-sm">
                                    <div>
                                        <span class="text-green-600 block text-xs">Tanggal Bayar</span>
                                        <span
                                            class="font-medium text-gray-800">{{ $billing->paid_at ? $billing->paid_at->format('d F Y H:i') : '-' }}</span>
                                    </div>
                                    <div>
                                        <span class="text-green-600 block text-xs">Metode</span>
                                        <span
                                            class="font-medium text-gray-800 capitalize">{{ str_replace('_', ' ', $billing->payment_method ?? '-') }}</span>
                                    </div>
                                    @if ($billing->notes)
                                        <div class="col-span-2">
                                            <span class="text-green-600 block text-xs">Catatan</span>
                                            <span class="text-gray-800 italic">"{{ $billing->notes }}"</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif
                        
                        {{-- Tampilkan catatan jika paket custom/belum lunas tapi ada notes --}}
                        @if ($billing->notes && $billing->status != 'paid')
                            <div class="bg-yellow-50 px-6 py-4 border-t border-yellow-100">
                                <h3 class="text-sm font-bold text-yellow-800 mb-2 uppercase">Deskripsi Paket / Catatan</h3>
                                <p class="text-sm text-gray-700 italic">"{{ $billing->notes }}"</p>
                            </div>
                        @endif
                    </div>

                    <!-- DATA PELANGGAN -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                            <h2 class="font-semibold text-gray-800 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-gray-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                Data Pelanggan
                            </h2>
                        </div>
                        <div class="p-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-xs text-gray-400 uppercase tracking-wider mb-1">Nama
                                        Pelanggan</label>
                                    <p class="font-semibold text-gray-800">
                                        {{ optional($billing->customer->user)->name ?? 'Deleted User' }}</p>
                                    @if ($billing->customer->company_name)
                                        <p class="text-sm text-gray-500">{{ $billing->customer->company_name }}</p>
                                    @endif
                                </div>
                                <div>
                                    <label class="block text-xs text-gray-400 uppercase tracking-wider mb-1">Kontak</label>
                                    <p class="text-gray-800">{{ optional($billing->customer->user)->phone ?? '-' }}</p>
                                    <p class="text-sm text-gray-500">{{ optional($billing->customer->user)->email ?? '-' }}
                                    </p>
                                </div>
                                <div class="md:col-span-2">
                                    <label class="block text-xs text-gray-400 uppercase tracking-wider mb-1">Alamat
                                        Pemasangan</label>
                                    <p class="text-gray-800">{{ $billing->customer->address }}</p>
                                    @if ($billing->customer->hasLocation())
                                        <a href="{{ $billing->customer->google_maps_link }}" target="_blank"
                                            class="inline-flex items-center text-sm text-blue-600 hover:underline mt-1">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                                </path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            </svg>
                                            Buka di Google Maps
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- DOKUMENTASI (FOTO) - DIKEMBALIKAN -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                            <h2 class="font-semibold text-gray-800 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-gray-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                    </path>
                                </svg>
                                Dokumentasi (Data Pelanggan)
                            </h2>
                        </div>
                        <div class="p-6">
                            <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                                @if ($billing->customer->foto_rumah)
                                    <div class="cursor-pointer group"
                                        onclick="openImageModal('{{ Storage::url($billing->customer->foto_rumah) }}')">
                                        <div class="aspect-video rounded-lg overflow-hidden border bg-gray-100 relative">
                                            <img src="{{ Storage::url($billing->customer->foto_rumah) }}"
                                                class="w-full h-full object-cover transition group-hover:scale-105">
                                            <div
                                                class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-20 transition flex items-center justify-center">
                                                <svg class="w-8 h-8 text-white opacity-0 group-hover:opacity-100"
                                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                                </svg>
                                            </div>
                                        </div>
                                        <p class="text-xs text-center mt-2 text-gray-600">Rumah</p>
                                    </div>
                                @endif

                                @if ($billing->customer->foto_ktp)
                                    <div class="cursor-pointer group"
                                        onclick="openImageModal('{{ Storage::url($billing->customer->foto_ktp) }}')">
                                        <div class="aspect-video rounded-lg overflow-hidden border bg-gray-100 relative">
                                            <img src="{{ Storage::url($billing->customer->foto_ktp) }}"
                                                class="w-full h-full object-cover transition group-hover:scale-105">
                                            <div
                                                class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-20 transition flex items-center justify-center">
                                                <svg class="w-8 h-8 text-white opacity-0 group-hover:opacity-100"
                                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                                </svg>
                                            </div>
                                        </div>
                                        <p class="text-xs text-center mt-2 text-gray-600">KTP</p>
                                    </div>
                                @endif

                                @if (!$billing->customer->foto_rumah && !$billing->customer->foto_ktp)
                                    <div class="col-span-full text-center py-4 text-gray-400 text-sm">
                                        Tidak ada foto dokumentasi di data pelanggan.
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-1 space-y-6">

                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h3 class="font-semibold text-gray-800 mb-4">Status Layanan</h3>

                        <div class="mb-4">
                            <span class="block text-xs text-gray-400 uppercase tracking-wider mb-1">Status Tagihan</span>
                            <span
                                class="px-3 py-1 text-sm font-semibold rounded-full {{ $billing->getStatusBadgeClass() }}">
                                {{ $billing->getStatusLabel() }}
                            </span>
                        </div>

                        <div>
                            <span class="block text-xs text-gray-400 uppercase tracking-wider mb-1">Status Pelanggan</span>
                            @if ($billing->customer->status == 'active')
                                <span
                                    class="px-3 py-1 text-sm font-semibold rounded-full bg-green-100 text-green-800">Aktif</span>
                            @elseif($billing->customer->status == 'isolir')
                                <span
                                    class="px-3 py-1 text-sm font-semibold rounded-full bg-red-100 text-red-800">Terisolir</span>
                            @else
                                <span
                                    class="px-3 py-1 text-sm font-semibold rounded-full bg-gray-100 text-gray-800">Inactive</span>
                            @endif
                        </div>

                        @if ($billing->customer->status == 'isolir')
                            <div class="mt-4 p-3 bg-red-50 border border-red-100 rounded-lg text-xs text-red-700">
                                <span class="font-bold">Perhatian:</span> Layanan internet pelanggan ini sedang dimatikan
                                sistem karena tagihan menunggak.
                            </div>
                        @endif
                    </div>

                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                            <h2 class="font-semibold text-gray-800 text-sm">Data Teknis (Perangkat)</h2>
                        </div>
                        <div class="p-6 space-y-4">
                            <div>
                                <label class="block text-xs text-gray-400 uppercase tracking-wider mb-1">ODP / Port</label>
                                <p class="font-mono text-gray-800 bg-gray-50 p-2 rounded border">
                                    {{ $billing->customer->no_odp ?? '-' }}</p>
                            </div>
                            <div>
                                <label class="block text-xs text-gray-400 uppercase tracking-wider mb-1">MAC Address
                                    ONT</label>
                                <p class="font-mono text-gray-800 bg-gray-50 p-2 rounded border">
                                    {{ $billing->customer->mac_ont ?? '-' }}</p>
                            </div>
                            <div>
                                <label class="block text-xs text-gray-400 uppercase tracking-wider mb-1">Area
                                    Coverage</label>
                                {{-- PERBAIKAN: Handle null coverage area --}}
                                <p class="text-gray-800 font-medium">
                                    {{ $billing->coverageArea->name ?? 'Business (Non-Coverage)' }}
                                </p>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    
    <div id="imageModal"
        class="hidden fixed inset-0 bg-black bg-opacity-90 z-50 flex items-center justify-center p-4 backdrop-blur-sm"
        onclick="closeImageModal()">
        <div class="relative max-w-5xl max-h-full">
            <button class="absolute -top-10 right-0 text-white hover:text-gray-300 text-xl font-bold">&times;
                Tutup</button>
            <img id="modalImage" src="" alt="Preview" class="max-w-full max-h-[90vh] rounded-lg shadow-2xl">
        </div>
    </div>

    <script>
        function openImageModal(src) {
            document.getElementById('modalImage').src = src;
            document.getElementById('imageModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden'; // Prevent scrolling
        }

        function closeImageModal() {
            document.getElementById('imageModal').classList.add('hidden');
            document.body.style.overflow = 'auto'; // Restore scrolling
        }

        // Close on escape key
        document.addEventListener('keydown', function(event) {
            if (event.key === "Escape") {
                closeImageModal();
            }
        });
    </script>
@endsection