@extends('layouts.app')

@section('title', 'Tambah Coverage Area')

@push('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet-draw@1.0.4/dist/leaflet.draw.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder@2.4.0/dist/Control.Geocoder.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/leaflet.locatecontrol@0.79.0/dist/L.Control.Locate.min.css" />
    
    <style>
        /* Custom Leaflet Toolbar */
        .leaflet-draw-toolbar a {
            background-color: white !important;
            color: #4b5563 !important;
            border-radius: 8px !important;
            margin-bottom: 5px !important;
            box-shadow: 0 2px 5px rgba(0,0,0,0.08) !important;
            border: 1px solid #e5e7eb !important;
        }
        .leaflet-draw-toolbar a:hover {
            background-color: #eff6ff !important;
            color: #4f46e5 !important;
        }
        .leaflet-draw-actions a {
            background-color: #4f46e5 !important;
            color: white !important;
            border-radius: 6px !important;
        }
        
        /* Mobile Toolbar */
        .mobile-draw-toolbar {
            position: absolute;
            bottom: 24px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 1000;
            background: white;
            padding: 8px;
            border-radius: 16px;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1);
            display: flex;
            gap: 12px;
            border: 1px solid #f3f4f6;
        }
        .mobile-tool-btn {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            background: #f9fafb;
            border: 1px solid #e5e7eb;
            transition: all 0.2s;
            color: #6b7280;
        }
        .mobile-tool-btn.active {
            background: #eff6ff;
            border-color: #4f46e5;
            color: #4f46e5;
            box-shadow: 0 0 0 2px rgba(79, 70, 229, 0.1);
        }
    </style>
@endpush

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    
    <div class="mb-8 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 text-sm text-gray-500 mb-1">
                <a href="{{ route('coverage_areas.index') }}" class="hover:text-indigo-600 transition">Coverage Area</a>
                <span>/</span>
                <span>Buat Baru</span>
            </div>
            <h2 class="text-2xl font-bold text-gray-900">Gambar Area Baru</h2>
        </div>
        <a href="{{ route('coverage_areas.index') }}" class="inline-flex items-center px-4 py-2.5 bg-white border border-gray-300 rounded-xl text-sm font-semibold text-gray-700 hover:bg-gray-50 hover:text-gray-900 shadow-sm transition">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Batal
        </a>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="grid grid-cols-1 lg:grid-cols-3">
            
            <div class="p-6 lg:p-8 lg:border-r border-gray-100 flex flex-col h-full">
                <div class="mb-6">
                    <h3 class="text-lg font-bold text-gray-900">Informasi Area</h3>
                    <p class="text-sm text-gray-500">Isi detail nama dan deskripsi wilayah.</p>
                </div>

                <form action="{{ route('coverage_areas.store') }}" method="POST" id="coverageForm" class="space-y-6 flex-1 flex flex-col">
                    @csrf
                    <input type="hidden" name="boundary" id="boundary">

                    <div class="space-y-5 flex-1">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Area <span class="text-red-500">*</span></label>
                            <input type="text" name="name" required 
                                class="block w-full rounded-xl border-gray-300 bg-white px-4 py-3 text-gray-900 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 placeholder:text-gray-400 transition" 
                                placeholder="Contoh: Cluster Melati Indah">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Keterangan</label>
                            <textarea name="description" rows="4" 
                                class="block w-full rounded-xl border-gray-300 bg-white px-4 py-3 text-gray-900 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 placeholder:text-gray-400 transition resize-none" 
                                placeholder="Tambahkan catatan opsional..."></textarea>
                        </div>

                        <div id="status-indicator" class="p-4 bg-orange-50 text-orange-800 rounded-xl text-sm border border-orange-100 flex items-start gap-3">
                            <svg class="w-5 h-5 text-orange-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                            <div>
                                <span class="font-bold block">Belum ada gambar!</span>
                                <span class="opacity-90">Silakan gambar area pada peta di samping menggunakan tools yang tersedia.</span>
                            </div>
                        </div>

                        <div id="success-indicator" class="hidden p-4 bg-green-50 text-green-800 rounded-xl text-sm border border-green-100 flex items-center gap-3">
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            <span class="font-bold">Area berhasil digambar!</span>
                        </div>
                    </div>

                    <div class="pt-4 mt-auto">
                        <button type="submit" id="submitBtn" disabled 
                            class="w-full flex justify-center items-center py-3.5 px-4 border border-transparent rounded-xl shadow-md text-sm font-bold text-white bg-gray-400 cursor-not-allowed transition-all transform active:scale-95">
                            Simpan Coverage Area
                        </button>
                    </div>
                </form>
            </div>

            <div class="lg:col-span-2 relative h-[600px] lg:h-auto bg-gray-100" x-data="{ showGuide: true }">
                
                <div id="map" class="absolute inset-0 z-0"></div>

                <div x-show="showGuide" 
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 transform scale-95 translate-y-4"
                     x-transition:enter-end="opacity-100 transform scale-100 translate-y-0"
                     x-transition:leave="transition ease-in duration-200"
                     x-transition:leave-start="opacity-100 transform scale-100"
                     x-transition:leave-end="opacity-0 transform scale-95"
                     class="absolute top-4 right-4 z-[400] w-72 bg-white/95 backdrop-blur-sm border border-indigo-100 rounded-2xl shadow-xl p-5">
                    
                    <div class="flex justify-between items-start mb-3 border-b border-gray-100 pb-3">
                        <div class="flex items-center text-indigo-600 font-bold text-sm uppercase tracking-wide">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Panduan
                        </div>
                        <button @click="showGuide = false" class="text-gray-400 hover:text-red-500 hover:bg-red-50 rounded-lg p-1 transition focus:outline-none">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>

                    <ol class="text-sm text-gray-600 list-decimal ml-4 space-y-3 leading-relaxed font-medium">
                        <li>Pilih alat <strong class="text-indigo-600 bg-indigo-50 px-1 rounded">Polygon</strong> (segi lima) di toolbar kiri.</li>
                        <li>Klik titik-titik sudut di peta untuk membentuk area.</li>
                        <li>Klik kembali ke <strong class="text-indigo-600">Titik Awal</strong> untuk menyelesaikan.</li>
                        <li>Klik tombol <strong>Simpan</strong> di kiri.</li>
                    </ol>
                </div>

                <button x-show="!showGuide" 
                        @click="showGuide = true" 
                        x-transition
                        class="absolute top-4 right-4 z-[400] p-3 bg-white rounded-xl shadow-lg border border-gray-200 text-indigo-600 hover:bg-indigo-50 transition focus:outline-none group"
                        title="Tampilkan Panduan">
                    <svg class="w-6 h-6 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </button>

                <div class="lg:hidden mobile-draw-toolbar"></div>

            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet-draw@1.0.4/dist/leaflet.draw.js"></script>
    <script src="https://unpkg.com/leaflet-control-geocoder@2.4.0/dist/Control.Geocoder.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/leaflet.locatecontrol@0.79.0/dist/L.Control.Locate.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // 1. Init Map
            var map = L.map('map').setView([-6.200000, 106.816666], 13);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors'
            }).addTo(map);

            // 2. Controls
            L.control.locate({ position: 'topleft', flyTo: true }).addTo(map);
            L.Control.geocoder({ defaultMarkGeocode: false })
              .on('markgeocode', function(e) {
                var bbox = e.geocode.bbox;
                var poly = L.polygon([bbox.getSouthEast(), bbox.getNorthEast(), bbox.getNorthWest(), bbox.getSouthWest()]);
                map.fitBounds(poly.getBounds());
              })
              .addTo(map);

            // 3. Draw Feature
            var drawnItems = new L.FeatureGroup();
            map.addLayer(drawnItems);

            var drawControl = new L.Control.Draw({
                position: 'topleft',
                draw: {
                    polygon: { allowIntersection: false, showArea: true, shapeOptions: { color: '#4F46E5', weight: 3 } },
                    marker: false, circle: false, circlemarker: false, polyline: false, rectangle: false
                },
                edit: { featureGroup: drawnItems, remove: true }
            });
            map.addControl(drawControl);

            // 4. Logic UI & Validation
            const boundaryInput = document.getElementById('boundary');
            const submitBtn = document.getElementById('submitBtn');
            const statusIndicator = document.getElementById('status-indicator');
            const successIndicator = document.getElementById('success-indicator');

            function updateStatus(hasArea) {
                if (hasArea) {
                    // Enable Button
                    submitBtn.disabled = false;
                    submitBtn.classList.remove('bg-gray-400', 'cursor-not-allowed');
                    submitBtn.classList.add('bg-indigo-600', 'hover:bg-indigo-700', 'shadow-lg', 'shadow-indigo-200');
                    
                    // Swap Indicators
                    statusIndicator.classList.add('hidden');
                    successIndicator.classList.remove('hidden');
                } else {
                    // Disable Button
                    submitBtn.disabled = true;
                    submitBtn.classList.add('bg-gray-400', 'cursor-not-allowed');
                    submitBtn.classList.remove('bg-indigo-600', 'hover:bg-indigo-700', 'shadow-lg', 'shadow-indigo-200');
                    
                    // Swap Indicators
                    statusIndicator.classList.remove('hidden');
                    successIndicator.classList.add('hidden');
                    boundaryInput.value = '';
                }
            }

            // Event Listeners Leaflet Draw
            map.on(L.Draw.Event.CREATED, function (e) {
                drawnItems.clearLayers(); 
                drawnItems.addLayer(e.layer);
                var geoJson = e.layer.toGeoJSON();
                boundaryInput.value = JSON.stringify(geoJson.geometry);
                updateStatus(true);
            });

            map.on(L.Draw.Event.DELETED, function(e) {
                if (drawnItems.getLayers().length === 0) {
                    updateStatus(false);
                }
            });
            
            updateStatus(false);
        });
    </script>
@endpush