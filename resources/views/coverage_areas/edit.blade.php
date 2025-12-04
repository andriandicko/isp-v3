@extends('layouts.app')

@section('title', 'Edit Coverage Area')

{{-- Leaflet CSS - harus di atas/head --}}
@push('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet-draw@1.0.4/dist/leaflet.draw.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder@2.4.0/dist/Control.Geocoder.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/leaflet.locatecontrol@0.79.0/dist/L.Control.Locate.min.css" />
    <style>
        /* Fix warna text di leaflet draw actions agar kontras */
        .leaflet-draw-actions a {
            background-color: white !important;
            color: #1f2937 !important;
            font-weight: 500 !important;
        }

        .leaflet-draw-actions a:hover {
            background-color: #f3f4f6 !important;
        }

        .leaflet-draw-toolbar a {
            background-color: white !important;
        }

        .leaflet-control-geocoder {
            border-radius: 8px !important;
            box-shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1) !important;
        }

        .leaflet-control-geocoder-form input {
            border-radius: 6px !important;
            padding: 8px 12px !important;
            border: 1px solid #d1d5db !important;
        }

        .leaflet-bar {
            border-radius: 8px !important;
            box-shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1) !important;
        }

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

        /* Custom Tooltip untuk Desktop */
        .leaflet-draw-toolbar a[title]:hover::after,
        .leaflet-bar a[title]:hover::after {
            content: attr(title);
            position: absolute;
            left: 100%;
            top: 50%;
            transform: translateY(-50%);
            margin-left: 10px;
            background: rgba(0, 0, 0, 0.9);
            color: white;
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 13px;
            white-space: nowrap;
            z-index: 10000;
            pointer-events: none;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            font-weight: 500;
        }

        .leaflet-draw-toolbar a[title]:hover::before,
        .leaflet-bar a[title]:hover::before {
            content: '';
            position: absolute;
            left: 100%;
            top: 50%;
            transform: translateY(-50%);
            margin-left: 4px;
            border: 6px solid transparent;
            border-right-color: rgba(0, 0, 0, 0.9);
            z-index: 10000;
            pointer-events: none;
        }

        /* Mobile Quick Edit Toolbar - Floating di tengah bawah */
        .mobile-edit-toolbar {
            position: fixed;
            bottom: 80px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 1001;
            background: white;
            border-radius: 12px;
            padding: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            display: flex;
            gap: 8px;
            transition: all 0.3s ease;
        }

        .mobile-edit-toolbar.hidden {
            transform: translateX(-50%) translateY(150%);
            opacity: 0;
        }

        .mobile-tool-btn {
            width: 44px;
            height: 44px;
            border-radius: 8px;
            background: white;
            border: 1px solid #e5e7eb;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .mobile-tool-btn:active {
            transform: scale(0.95);
            background: #f3f4f6;
        }

        .mobile-tool-btn.active {
            background: #dbeafe;
            border-color: #3b82f6;
        }

        /* Help indicator */
        .mobile-help-indicator {
            position: fixed;
            top: 60px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 1000;
            background: rgba(37, 99, 235, 0.95);
            color: white;
            padding: 8px 16px;
            border-radius: 8px;
            font-size: 12px;
            max-width: 80%;
            text-align: center;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.3s ease;
        }

        .mobile-help-indicator.show {
            opacity: 1;
        }

        @media (max-width: 640px) {

            /* Zoom controls di kanan atas */
            .leaflet-control-zoom {
                position: fixed !important;
                top: 60px !important;
                right: 10px !important;
                z-index: 999 !important;
            }

            /* Search collapsed di kiri atas */
            .leaflet-control-geocoder.leaflet-bar {
                position: fixed !important;
                top: 60px !important;
                left: 10px !important;
                z-index: 999 !important;
                max-width: 40px !important;
            }

            .leaflet-control-geocoder.leaflet-bar.leaflet-control-geocoder-expanded {
                max-width: 200px !important;
            }

            /* Locate button */
            .leaflet-control-locate {
                position: fixed !important;
                top: 110px !important;
                left: 10px !important;
                z-index: 999 !important;
            }

            /* Ukuran kontrol */
            .leaflet-bar a {
                width: 32px !important;
                height: 32px !important;
                line-height: 32px !important;
                font-size: 16px !important;
            }

            /* Hide default leaflet draw toolbar di mobile */
            .leaflet-draw {
                display: none !important;
            }
        }

        /* Tablet & Desktop */
        @media (min-width: 641px) {
            .mobile-edit-toolbar {
                display: none !important;
            }

            .mobile-help-indicator {
                display: none !important;
            }

            .leaflet-top.leaflet-left {
                display: flex !important;
                flex-direction: column !important;
                gap: 10px !important;
            }

            .leaflet-control {
                margin: 0 !important;
            }
        }
    </style>
@endpush

@section('content')
    <div class="min-h-screen bg-gray-50 pb-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            {{-- Header --}}
            <div class="mb-6">
                <div class="flex items-center mb-2">
                    <a href="{{ route('coverage_areas.index') }}"
                        class="mr-3 p-2 hover:bg-gray-200 rounded-lg transition-colors duration-150">
                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                    </a>
                    <div>
                        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Edit Coverage Area</h1>
                        <p class="mt-1 text-sm text-gray-600">Ubah area jangkauan layanan pada peta</p>
                    </div>
                </div>
            </div>

            {{-- Alert Success --}}
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

            {{-- Form Card --}}
            <div class="bg-white rounded-xl shadow-md overflow-hidden animate-fade-in">
                <form method="POST" action="{{ route('coverage_areas.update', $coverageArea->id) }}" id="coverageForm">
                    @csrf
                    @method('PUT')

                    {{-- Form Fields --}}
                    <div class="p-4 sm:p-6 space-y-5">
                        {{-- Nama Area --}}
                        <div>
                            <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">
                                Nama Area <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="name" name="name"
                                value="{{ old('name', $coverageArea->name) }}" placeholder="Contoh: Area Jakarta Selatan"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-shadow duration-150 @error('name') border-red-500 @enderror"
                                required>
                            @error('name')
                                <p class="mt-1 text-sm text-red-600 flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        {{-- Deskripsi --}}
                        <div>
                            <label for="description" class="block text-sm font-semibold text-gray-700 mb-2">
                                Deskripsi
                            </label>
                            <textarea id="description" name="description" rows="3" placeholder="Deskripsi singkat tentang area ini (opsional)"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-shadow duration-150 @error('description') border-red-500 @enderror">{{ old('description', $coverageArea->description) }}</textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-red-600 flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        {{-- Boundary Info --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Koordinat Boundary <span class="text-red-500">*</span>
                            </label>
                            <textarea name="boundary" id="boundary" rows="3" placeholder="Edit polygon pada peta di bawah untuk mengubah"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-xs font-mono text-gray-700 bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-shadow duration-150 @error('boundary') border-red-500 @enderror"
                                readonly>{{ old('boundary', $coverageArea->boundary) }}</textarea>
                            @error('boundary')
                                <p class="mt-1 text-sm text-red-600 flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                            <p class="mt-2 text-xs text-gray-500 flex items-start">
                                <svg class="w-4 h-4 mr-1 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                        clip-rule="evenodd" />
                                </svg>
                                <span class="sm:hidden">Gunakan toolbar di bawah peta untuk edit area.</span>
                                <span class="hidden sm:inline">Klik ✎ (Edit) untuk mengubah area, atau 🗑️ (Delete) untuk
                                    menghapus dan menggambar ulang.</span>
                            </p>
                        </div>
                    </div>

                    {{-- Map Section --}}
                    <div class="border-t border-gray-200">
                        <div class="p-4 sm:p-6">
                            <div class="flex items-center justify-between mb-3">
                                <label class="block text-sm font-semibold text-gray-700">
                                    Edit Area pada Peta
                                </label>
                                <span id="mapStatus" class="text-xs text-green-600 font-medium">
                                    Area saat ini ✓
                                </span>
                            </div>

                            {{-- Map Container --}}
                            <div class="relative rounded-lg overflow-hidden shadow-md border border-gray-300">
                                <div id="map" class="w-full h-[500px] sm:h-96 md:h-[500px] lg:h-[32rem]"></div>

                                {{-- Edit Instructions Overlay - Hidden on mobile --}}
                                <div id="editInstructions"
                                    class="absolute top-4 right-4 bg-white rounded-lg p-3 shadow-lg max-w-xs hidden sm:block pointer-events-none z-[400]">
                                    <div class="flex items-start">
                                        <svg class="w-5 h-5 text-blue-600 mr-2 flex-shrink-0" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <div class="text-xs text-gray-600">
                                            <p class="font-semibold mb-1">Tips Edit:</p>
                                            <ul class="space-y-0.5">
                                                <li>• Klik ✎ lalu seret titik untuk ubah bentuk</li>
                                                <li>• Klik 🗑️ untuk hapus dan gambar ulang</li>
                                                <li>• Klik Save setelah selesai edit</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Action Buttons --}}
                    <div class="px-4 sm:px-6 py-4 bg-gray-50 border-t border-gray-200">
                        <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-3">
                            <a href="{{ route('coverage_areas.index') }}"
                                class="inline-flex items-center justify-center px-5 py-2.5 border border-gray-300 rounded-lg text-gray-700 bg-white hover:bg-gray-50 font-medium transition-colors duration-150 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                                Batal
                            </a>
                            <button type="submit" id="submitBtn"
                                class="inline-flex items-center justify-center px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg shadow-sm transition-colors duration-150 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                                </svg>
                                Update Coverage Area
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Mobile Edit Toolbar --}}
    <div class="mobile-edit-toolbar sm:hidden" id="mobileEditToolbar">
        <button type="button" class="mobile-tool-btn" id="btnEdit" title="Edit">
            ✎
        </button>
        <button type="button" class="mobile-tool-btn" id="btnSave" title="Save" style="display:none;">
            ✓
        </button>
        <button type="button" class="mobile-tool-btn" id="btnCancel" title="Cancel" style="display:none;">
            ✕
        </button>
        <button type="button" class="mobile-tool-btn" id="btnDelete" title="Delete">
            🗑️
        </button>
    </div>

    {{-- Mobile Help Indicator --}}
    <div class="mobile-help-indicator sm:hidden" id="mobileHelpIndicator"></div>
@endsection

{{-- Leaflet JS - harus di bawah/setelah content --}}
@push('scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet-draw@1.0.4/dist/leaflet.draw.js"></script>
    <script src="https://unpkg.com/leaflet-control-geocoder@2.4.0/dist/Control.Geocoder.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/leaflet.locatecontrol@0.79.0/dist/L.Control.Locate.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const map = L.map('map').setView([-6.9, 107.6], 11);

            L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '© OpenStreetMap contributors'
            }).addTo(map);

            L.control.locate({
                position: 'topleft',
                strings: {
                    title: "Tampilkan lokasi saya"
                },
                flyTo: true,
                keepCurrentZoomLevel: false
            }).addTo(map);

            L.Control.geocoder({
                defaultMarkGeocode: false,
                position: 'topleft',
                placeholder: 'Cari...',
                errorMessage: 'Tidak ditemukan',
                collapsed: true,
                geocoder: L.Control.Geocoder.nominatim()
            }).on('markgeocode', function(e) {
                const bbox = e.geocode.bbox;
                const poly = L.polygon([bbox.getSouthEast(), bbox.getNorthEast(), bbox.getNorthWest(), bbox
                    .getSouthWest()
                ]);
                map.fitBounds(poly.getBounds());
            }).addTo(map);

            let drawnItems = new L.FeatureGroup().addTo(map);

            const drawControl = new L.Control.Draw({
                position: 'topleft',
                draw: {
                    polygon: {
                        allowIntersection: false,
                        showArea: true,
                        metric: true,
                        shapeOptions: {
                            color: '#2563eb',
                            fillColor: '#60a5fa',
                            fillOpacity: 0.4,
                            weight: 2
                        }
                    },
                    polyline: {
                        shapeOptions: {
                            color: '#2563eb',
                            weight: 3
                        }
                    },
                    rectangle: {
                        shapeOptions: {
                            color: '#2563eb',
                            fillColor: '#60a5fa',
                            fillOpacity: 0.4,
                            weight: 2
                        }
                    },
                    circle: {
                        shapeOptions: {
                            color: '#2563eb',
                            fillColor: '#60a5fa',
                            fillOpacity: 0.4,
                            weight: 2
                        }
                    },
                    marker: true,
                    circlemarker: false
                },
                edit: {
                    featureGroup: drawnItems,
                    remove: true,
                    edit: true
                }
            });

            map.addControl(drawControl);

            // Setup tooltips for desktop toolbar buttons
            setTimeout(() => {
                document.querySelectorAll('.leaflet-draw-draw-polygon').forEach(el => el.setAttribute(
                    'title', 'Draw a polygon'));
                document.querySelectorAll('.leaflet-draw-draw-polyline').forEach(el => el.setAttribute(
                    'title', 'Draw a polyline'));
                document.querySelectorAll('.leaflet-draw-draw-rectangle').forEach(el => el.setAttribute(
                    'title', 'Draw a rectangle'));
                document.querySelectorAll('.leaflet-draw-draw-circle').forEach(el => el.setAttribute(
                    'title', 'Draw a circle'));
                document.querySelectorAll('.leaflet-draw-draw-marker').forEach(el => el.setAttribute(
                    'title', 'Draw a marker'));
                document.querySelectorAll('.leaflet-draw-edit-edit').forEach(el => el.setAttribute('title',
                    'Edit layers'));
                document.querySelectorAll('.leaflet-draw-edit-remove').forEach(el => el.setAttribute(
                    'title', 'Delete layers'));
            }, 500);

            // Mobile Edit Toolbar Logic
            if (window.innerWidth <= 640) {
                const btnEdit = document.getElementById('btnEdit');
                const btnSave = document.getElementById('btnSave');
                const btnCancel = document.getElementById('btnCancel');
                const btnDelete = document.getElementById('btnDelete');
                const helpIndicator = document.getElementById('mobileHelpIndicator');

                let editHandler = null;
                let deleteHandler = null;

                function showHelp(message, duration = 3000) {
                    helpIndicator.textContent = message;
                    helpIndicator.classList.add('show');
                    setTimeout(() => {
                        helpIndicator.classList.remove('show');
                    }, duration);
                }

                btnEdit.addEventListener('click', function() {
                    if (drawnItems.getLayers().length === 0) {
                        showHelp('Tidak ada area untuk diedit');
                        return;
                    }

                    editHandler = new L.EditToolbar.Edit(map, {
                        featureGroup: drawnItems
                    });
                    editHandler.enable();

                    btnEdit.style.display = 'none';
                    btnDelete.style.display = 'none';
                    btnSave.style.display = 'flex';
                    btnCancel.style.display = 'flex';
                    btnEdit.classList.add('active');

                    showHelp('Seret titik-titik untuk mengubah bentuk area');
                });

                btnSave.addEventListener('click', function() {
                    if (editHandler) {
                        editHandler.save();
                        editHandler.disable();
                        editHandler = null;
                    }

                    btnEdit.style.display = 'flex';
                    btnDelete.style.display = 'flex';
                    btnSave.style.display = 'none';
                    btnCancel.style.display = 'none';
                    btnEdit.classList.remove('active');

                    updateBoundaryFromDrawnItems();
                    showHelp('Perubahan disimpan! ✓');
                });

                btnCancel.addEventListener('click', function() {
                    if (editHandler) {
                        editHandler.revertLayers();
                        editHandler.disable();
                        editHandler = null;
                    }

                    btnEdit.style.display = 'flex';
                    btnDelete.style.display = 'flex';
                    btnSave.style.display = 'none';
                    btnCancel.style.display = 'none';
                    btnEdit.classList.remove('active');

                    showHelp('Edit dibatalkan');
                });

                btnDelete.addEventListener('click', function() {
                    if (drawnItems.getLayers().length === 0) {
                        showHelp('Tidak ada area untuk dihapus');
                        return;
                    }

                    if (confirm('Yakin ingin menghapus area ini?')) {
                        drawnItems.clearLayers();
                        updateMapStatus(false);
                        showHelp('Area dihapus');
                    }
                });
            }

            @if ($coverageArea->boundary)
                try {
                    const existingGeometry = {!! $coverageArea->boundary !!};
                    const geoJsonLayer = L.geoJSON(existingGeometry, {
                        style: {
                            color: '#2563eb',
                            fillColor: '#60a5fa',
                            fillOpacity: 0.4,
                            weight: 2
                        }
                    });
                    geoJsonLayer.eachLayer(layer => drawnItems.addLayer(layer));
                    map.fitBounds(drawnItems.getBounds().pad(0.1));
                } catch (e) {
                    console.error('Error loading existing boundary:', e);
                }
            @endif

            function updateMapStatus(hasPolygon) {
                const statusEl = document.getElementById('mapStatus');
                if (hasPolygon) {
                    statusEl.textContent = 'Area telah diubah ✓';
                    statusEl.classList.remove('text-gray-500', 'text-orange-600');
                    statusEl.classList.add('text-green-600', 'font-medium');
                } else {
                    statusEl.textContent = 'Area dihapus - gambar ulang';
                    statusEl.classList.remove('text-green-600', 'text-gray-500');
                    statusEl.classList.add('text-orange-600', 'font-medium');
                    document.getElementById('boundary').value = '';
                }
            }

            function circleToPolygon(layer) {
                if (layer instanceof L.Circle) {
                    const center = layer.getLatLng();
                    const radius = layer.getRadius();
                    const points = 64;
                    const coordinates = [];
                    for (let i = 0; i <= points; i++) {
                        const angle = (i * 360 / points) * Math.PI / 180;
                        const dx = radius * Math.cos(angle);
                        const dy = radius * Math.sin(angle);
                        const lat = center.lat + (dy / 111320);
                        const lng = center.lng + (dx / (111320 * Math.cos(center.lat * Math.PI / 180)));
                        coordinates.push([lng, lat]);
                    }
                    return {
                        type: "Polygon",
                        coordinates: [coordinates]
                    };
                }
                return layer.toGeoJSON().geometry;
            }

            function updateBoundaryFromDrawnItems() {
                const layers = drawnItems.getLayers();
                if (layers.length > 0) {
                    const layer = layers[0];
                    const geometry = circleToPolygon(layer);
                    document.getElementById('boundary').value = JSON.stringify(geometry);
                    return true;
                }
                return false;
            }

            map.on(L.Draw.Event.CREATED, function(e) {
                drawnItems.clearLayers();
                drawnItems.addLayer(e.layer);
                updateBoundaryFromDrawnItems();
                updateMapStatus(true);
            });

            map.on(L.Draw.Event.EDITED, function(e) {
                updateMapStatus(updateBoundaryFromDrawnItems());
            });

            map.on(L.Draw.Event.DELETED, function(e) {
                const hasPolygon = drawnItems.getLayers().length > 0;
                if (hasPolygon) updateBoundaryFromDrawnItems();
                updateMapStatus(hasPolygon);
            });

            setTimeout(() => map.invalidateSize(), 100);
            window.addEventListener('resize', () => map.invalidateSize());

            document.getElementById('coverageForm').addEventListener('submit', function(e) {
                const boundaryValue = document.getElementById('boundary').value;
                if (!boundaryValue || boundaryValue.trim() === '') {
                    e.preventDefault();
                    alert('Silakan gambar area pada peta terlebih dahulu!');
                    return false;
                }
            });
        });
    </script>
@endpush
