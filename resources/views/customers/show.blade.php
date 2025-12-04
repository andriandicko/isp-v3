@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
            <div>
                <div class="flex items-center gap-3">
                    <h2 class="text-2xl font-bold text-gray-900">Detail Customer</h2>
                    @if ($customer->status === 'active')
                        <span
                            class="px-3 py-1 rounded-full text-xs font-bold bg-green-100 text-green-800 border border-green-200">
                            ACTIVE
                        </span>
                    @elseif ($customer->status === 'isolir')
                        <span class="px-3 py-1 rounded-full text-xs font-bold bg-red-100 text-red-800 border border-red-200">
                            ISOLIR
                        </span>
                    @else
                        <span
                            class="px-3 py-1 rounded-full text-xs font-bold bg-gray-100 text-gray-800 border border-gray-200">
                            INACTIVE
                        </span>
                    @endif
                </div>
                <p class="mt-1 text-sm text-gray-500">Dibuat pada {{ $customer->created_at->format('d F Y') }}</p>
            </div>

            <div class="flex space-x-3">
                <a href="{{ route('customers.edit', $customer) }}"
                    class="inline-flex items-center px-4 py-2 bg-yellow-500 hover:bg-yellow-600 text-white font-medium rounded-lg shadow-sm transition duration-150">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Edit Data
                </a>
                <a href="{{ route('customers.index') }}"
                    class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 font-medium rounded-lg transition duration-150">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Kembali
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-800">Informasi Pelanggan</h3>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Nama
                                    Akun</label>
                                <p class="text-gray-900 font-medium text-lg">{{ $customer->user->name ?? '-' }}</p>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Tipe
                                    Layanan</label>
                                @if ($customer->type === 'residential')
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">
                                        Rumahan (Residential)
                                    </span>
                                @else
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-medium bg-purple-100 text-purple-800">
                                        Bisnis (Corporate)
                                    </span>
                                @endif
                            </div>

                            @if ($customer->company_name)
                                <div>
                                    <label
                                        class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Perusahaan</label>
                                    <p class="text-gray-900">{{ $customer->company_name }}</p>
                                </div>
                            @endif

                            <div>
                                <label
                                    class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Kontak /
                                    WA</label>
                                <p class="text-gray-900 font-mono">{{ $customer->user->phone ?? '-' }}</p>
                                <p class="text-xs text-gray-500">{{ $customer->contact_person }}</p>
                            </div>

                            <div class="md:col-span-2">
                                <label
                                    class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Alamat
                                    Pemasangan</label>
                                <p class="text-gray-900">{{ $customer->address ?? '-' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-800">Dokumentasi Teknis</h3>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                            @if ($customer->foto_rumah)
                                <div class="group cursor-pointer"
                                    onclick="openImageModal('{{ Storage::url($customer->foto_rumah) }}')">
                                    <div class="aspect-video bg-gray-100 rounded-lg overflow-hidden border relative">
                                        <img src="{{ Storage::url($customer->foto_rumah) }}"
                                            class="w-full h-full object-cover transition group-hover:scale-105">
                                        <div
                                            class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-20 transition flex items-center justify-center">
                                            <svg class="w-8 h-8 text-white opacity-0 group-hover:opacity-100" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                            </svg>
                                        </div>
                                    </div>
                                    <p class="text-xs text-center mt-2 text-gray-600 font-medium">Rumah</p>
                                </div>
                            @endif
                            @if ($customer->foto_ktp)
                                <div class="group cursor-pointer"
                                    onclick="openImageModal('{{ Storage::url($customer->foto_ktp) }}')">
                                    <div class="aspect-video bg-gray-100 rounded-lg overflow-hidden border relative">
                                        <img src="{{ Storage::url($customer->foto_ktp) }}"
                                            class="w-full h-full object-cover transition group-hover:scale-105">
                                        <div
                                            class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-20 transition flex items-center justify-center">
                                            <svg class="w-8 h-8 text-white opacity-0 group-hover:opacity-100" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                            </svg>
                                        </div>
                                    </div>
                                    <p class="text-xs text-center mt-2 text-gray-600 font-medium">KTP</p>
                                </div>
                            @endif
                            @if ($customer->foto_redaman)
                                <div class="group cursor-pointer"
                                    onclick="openImageModal('{{ Storage::url($customer->foto_redaman) }}')">
                                    <div class="aspect-video bg-gray-100 rounded-lg overflow-hidden border relative">
                                        <img src="{{ Storage::url($customer->foto_redaman) }}"
                                            class="w-full h-full object-cover transition group-hover:scale-105">
                                        <div
                                            class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-20 transition flex items-center justify-center">
                                            <svg class="w-8 h-8 text-white opacity-0 group-hover:opacity-100" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                            </svg>
                                        </div>
                                    </div>
                                    <p class="text-xs text-center mt-2 text-gray-600 font-medium">Redaman</p>
                                </div>
                            @endif

                            @if (!$customer->foto_rumah && !$customer->foto_ktp && !$customer->foto_redaman)
                                <div class="col-span-full text-center py-8 text-gray-400">
                                    <svg class="w-12 h-12 mx-auto mb-2 text-gray-300" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                        </path>
                                    </svg>
                                    <p>Belum ada dokumentasi foto.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-1 space-y-6">

                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 bg-blue-600 text-white">
                        <h3 class="text-lg font-semibold flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z">
                                </path>
                            </svg>
                            Data Teknis
                        </h3>
                    </div>
                    <div class="p-6 space-y-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Area
                                Coverage</label>
                            <div class="flex items-center">
                                <span class="font-medium text-gray-900">{{ $customer->coverageArea->name ?? '-' }}</span>
                            </div>
                        </div>
                        <div>
                            <label
                                class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Koordinator
                                (Korlap)</label>
                            <div class="flex items-center">
                                <span class="text-sm text-gray-700">{{ $customer->korlap->name ?? '-' }}</span>
                            </div>
                        </div>
                        <hr class="border-gray-100">
                        <div>
                            <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">No. ODP
                                / Port</label>
                            <div class="bg-gray-50 p-2 rounded border border-gray-200 font-mono text-sm text-gray-800">
                                {{ $customer->no_odp ?? 'Belum diisi' }}
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">MAC
                                Address ONT</label>
                            <div class="bg-gray-50 p-2 rounded border border-gray-200 font-mono text-sm text-gray-800">
                                {{ $customer->mac_ont ?? 'Belum diisi' }}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="p-4 border-b border-gray-200 flex justify-between items-center bg-gray-50">
                        <h3 class="font-semibold text-gray-800">Lokasi Peta</h3>
                        <button onclick="copyCoordinates()"
                            class="text-xs bg-white border border-gray-300 hover:bg-gray-100 px-2 py-1 rounded transition">
                            Copy Koordinat
                        </button>
                    </div>
                    <div class="relative">
                        <div id="map" class="h-64 z-0"></div>
                        <a href="https://www.google.com/maps?q={{ $customer->lat }},{{ $customer->lng }}"
                            target="_blank"
                            class="absolute bottom-2 right-2 z-10 bg-white text-blue-600 text-xs font-bold px-3 py-1.5 rounded shadow hover:bg-blue-50 flex items-center">
                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                            </svg>
                            Google Maps
                        </a>
                    </div>
                </div>

            </div>
        </div>

        <div class="mt-8 bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center bg-gray-50">
                <h3 class="text-lg font-semibold text-gray-800">Riwayat Tagihan</h3>
                <span class="text-xs text-gray-500">{{ $customer->billings->count() }} Transaksi</span>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kode
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Tanggal Tagihan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Periode</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Paket</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Jumlah</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($customer->billings as $billing)
                            <tr class="hover:bg-gray-50 transition duration-150">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-mono text-blue-600 font-bold">
                                    {{ $billing->billing_code }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $billing->billing_date->format('d M Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-xs text-gray-500">
                                    {{ $billing->start_date ? $billing->start_date->format('d/m') : '-' }} -
                                    {{ $billing->end_date ? $billing->end_date->format('d/m/Y') : '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ optional($billing->package)->name ?? '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <span
                                        class="px-2 py-1 rounded-full text-xs font-semibold {{ $billing->getStatusBadgeClass() }}">
                                        {{ $billing->getStatusLabel() }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900 text-right">
                                    Rp {{ number_format($billing->amount, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <a href="{{ route('billings.show', $billing) }}"
                                        class="text-blue-600 hover:text-blue-900 text-xs font-medium hover:underline">Detail</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-8 text-center text-sm text-gray-500">
                                    Belum ada riwayat tagihan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
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
@endsection

@push('scripts')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <script>
        // Image Modal Logic
        function openImageModal(src) {
            document.getElementById('modalImage').src = src;
            document.getElementById('imageModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeImageModal() {
            document.getElementById('imageModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        // Close on Escape
        document.addEventListener('keydown', function(event) {
            if (event.key === "Escape") closeImageModal();
        });

        // Map Logic
        document.addEventListener('DOMContentLoaded', function() {
            const lat = {{ $customer->lat }};
            const lng = {{ $customer->lng }};

            const map = L.map('map').setView([lat, lng], 15);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '© OpenStreetMap'
            }).addTo(map);

            const marker = L.marker([lat, lng]).addTo(map);

            marker.bindPopup(`
                <div class="text-center p-1">
                    <strong class="block text-sm text-indigo-600">{{ $customer->user->name }}</strong>
                    <span class="text-xs text-gray-500">{{ $customer->address }}</span>
                </div>
            `).openPopup();
        });

        // Copy Coordinates
        function copyCoordinates() {
            const coords = '{{ $customer->lat }}, {{ $customer->lng }}';
            navigator.clipboard.writeText(coords).then(() => {
                alert('Koordinat disalin: ' + coords);
            }).catch(err => {
                console.error('Gagal menyalin', err);
            });
        }
    </script>
@endpush
