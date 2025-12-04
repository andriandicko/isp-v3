@extends('layouts.app')

@section('title', 'Detail Coverage Area')

@section('content')
    <div class="max-w-5xl mx-auto px-4 py-6">
        {{-- Header --}}
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-gray-800">
                Detail Coverage Area
            </h1>
            <a href="{{ route('coverage_areas.index') }}"
                class="inline-flex items-center gap-2 bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg transition">
                ← Kembali
            </a>
        </div>

        {{-- Card --}}
        <div class="bg-white shadow-md rounded-xl p-6 mb-6">
            <div class="mb-4">
                <h2 class="text-xl font-semibold text-gray-800">{{ $coverageArea->name }}</h2>
                <p class="text-gray-500 text-sm mt-1">
                    Diperbarui {{ $coverageArea->updated_at->diffForHumans() }}
                </p>
            </div>

            @if ($coverageArea->description)
                <div class="mb-6 text-gray-700 leading-relaxed">
                    {{ $coverageArea->description }}
                </div>
            @endif

            {{-- Map --}}
            <div id="map" class="w-full h-[450px] rounded-lg shadow-sm border"></div>
        </div>

        {{-- Metadata --}}
        <div class="grid md:grid-cols-2 gap-6">
            <div class="bg-white p-5 rounded-lg shadow-sm border">
                <h3 class="font-semibold mb-2">Informasi Teknis</h3>
                <ul class="text-sm text-gray-700">
                    <li><strong>ID:</strong> {{ $coverageArea->id }}</li>
                    <li><strong>Nama:</strong> {{ $coverageArea->name }}</li>
                    <li><strong>Dibuat:</strong> {{ $coverageArea->created_at->format('d M Y, H:i') }}</li>
                    <li><strong>Diperbarui:</strong> {{ $coverageArea->updated_at->format('d M Y, H:i') }}</li>
                </ul>
            </div>

            <div class="bg-white p-5 rounded-lg shadow-sm border">
                <h3 class="font-semibold mb-2">Data Geometry</h3>
                @if ($coverageArea->boundary)
                    <textarea readonly class="w-full text-xs bg-gray-50 border rounded-lg px-3 py-2 text-gray-600" rows="5">{{ $coverageArea->boundary }}</textarea>
                @else
                    <p class="text-sm text-gray-500 italic">Belum ada data boundary</p>
                @endif
            </div>
        </div>
    </div>

    {{-- Leaflet --}}
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const map = L.map('map').setView([-6.9, 107.6], 11);
            L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; OpenStreetMap contributors'
            }).addTo(map);

            @if ($coverageArea->boundary)
                try {
                    const geojson = {!! $coverageArea->boundary !!};
                    const polygon = L.geoJSON(geojson, {
                            style: {
                                color: '#2563eb',
                                fillColor: '#93c5fd',
                                fillOpacity: 0.5
                            }
                        }).bindPopup(`<b>{{ $coverageArea->name }}</b><br>{{ $coverageArea->description ?? '' }}`)
                        .addTo(map);
                    map.fitBounds(polygon.getBounds());
                } catch (error) {
                    console.error('Gagal menampilkan boundary:', error);
                }
            @endif
        });
    </script>
@endsection
