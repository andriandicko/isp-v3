@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-gray-50 pb-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            {{-- Header --}}
            <div class="mb-6">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Coverage Areas</h1>
                        <p class="mt-1 text-sm text-gray-600">Kelola area jangkauan layanan Anda</p>
                    </div>
                    <a href="{{ route('coverage_areas.create') }}"
                        class="inline-flex items-center justify-center px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg shadow-sm transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Tambah Area
                    </a>
                </div>
            </div>

            {{-- Alert --}}
            @if (session('success'))
                <div
                    class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg flex items-start shadow-sm animate-fade-in">
                    <svg class="w-5 h-5 mr-3 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                            clip-rule="evenodd" />
                    </svg>
                    <span class="flex-1">{{ session('success') }}</span>
                </div>
            @endif

            {{-- Peta --}}
            <div class="mb-6 bg-white rounded-xl shadow-md overflow-hidden">
                <div class="p-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" />
                        </svg>
                        Peta Coverage Area
                    </h2>
                </div>
                <div id="map" class="w-full h-64 sm:h-80 md:h-96 lg:h-[28rem]"></div>
            </div>

            {{-- Tabel --}}
            <div class="bg-white rounded-xl shadow-md overflow-hidden">
                <div class="px-4 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Daftar Area</h2>
                </div>

                {{-- Mobile Card View --}}
                <div class="block sm:hidden divide-y divide-gray-200">
                    @forelse ($areas as $area)
                        <div class="p-4 hover:bg-gray-50 transition-colors duration-150">
                            <div class="flex justify-between items-start mb-2">
                                <h3 class="font-semibold text-gray-900">{{ $area->name }}</h3>
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    Area
                                </span>
                            </div>
                            <p class="text-sm text-gray-600 mb-3">{{ $area->description ?? 'Tidak ada deskripsi' }}</p>
                            <div class="flex gap-2">
                                <a href="{{ route('coverage_areas.edit', $area->id) }}"
                                    class="flex-1 text-center px-3 py-2 bg-blue-50 hover:bg-blue-100 text-blue-700 text-sm font-medium rounded-lg transition-colors duration-150">
                                    Edit
                                </a>
                                <form method="POST" action="{{ route('coverage_areas.destroy', $area->id) }}"
                                    class="flex-1">
                                    @csrf @method('DELETE')
                                    <button type="submit" onclick="return confirm('Yakin ingin menghapus area ini?')"
                                        class="w-full px-3 py-2 bg-red-50 hover:bg-red-100 text-red-700 text-sm font-medium rounded-lg transition-colors duration-150">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <div class="p-8 text-center text-gray-500">
                            <svg class="w-12 h-12 mx-auto mb-3 text-gray-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" />
                            </svg>
                            <p class="text-sm">Belum ada area yang ditambahkan</p>
                        </div>
                    @endforelse
                </div>

                {{-- Desktop Table View --}}
                <div class="hidden sm:block overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Nama Area
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Deskripsi
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Aksi
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($areas as $area)
                                <tr class="hover:bg-gray-50 transition-colors duration-150">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="font-medium text-gray-900">{{ $area->name }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-600">{{ $area->description ?? '-' }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex justify-end gap-3">
                                            <a href="{{ route('coverage_areas.edit', $area->id) }}"
                                                class="text-blue-600 hover:text-blue-800 transition-colors duration-150">
                                                Edit
                                            </a>
                                            <form method="POST" action="{{ route('coverage_areas.destroy', $area->id) }}"
                                                class="inline">
                                                @csrf @method('DELETE')
                                                <button type="submit"
                                                    onclick="return confirm('Yakin ingin menghapus area ini?')"
                                                    class="text-red-600 hover:text-red-800 transition-colors duration-150">
                                                    Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-6 py-12 text-center">
                                        <svg class="w-12 h-12 mx-auto mb-3 text-gray-400" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" />
                                        </svg>
                                        <p class="text-gray-500">Belum ada area yang ditambahkan</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Leaflet CSS/JS --}}
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <script>
        // Inisialisasi peta
        const map = L.map('map').setView([-6.9, 107.6], 11);

        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        // Tambahkan polygon areas
        const polygons = [];
        @foreach ($areas as $area)
            @if ($area->boundary)
                const polygon{{ $area->id }} = L.geoJSON({!! $area->boundary !!}, {
                    style: {
                        color: '#2563eb',
                        fillColor: '#60a5fa',
                        fillOpacity: 0.4,
                        weight: 2
                    }
                }).bindPopup(
                    '<div class="p-2"><strong class="text-gray-900">{{ $area->name }}</strong><br><span class="text-sm text-gray-600">{{ $area->description ?? '' }}</span></div>'
                ).addTo(map);
                polygons.push(polygon{{ $area->id }});
            @endif
        @endforeach

        // Auto-fit bounds jika ada polygon
        if (polygons.length > 0) {
            const group = L.featureGroup(polygons);
            map.fitBounds(group.getBounds().pad(0.1));
        }

        // Responsif map saat resize
        window.addEventListener('resize', function() {
            map.invalidateSize();
        });
    </script>

    <style>
        @keyframes fade-in {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in {
            animation: fade-in 0.3s ease-out;
        }
    </style>
@endsection
