@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-gray-50 py-6 sm:py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-6">
                <a href="{{ route('offices.index') }}"
                    class="inline-flex items-center text-sm text-gray-600 hover:text-gray-900 mb-4 transition-colors">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    Kembali ke Daftar Office
                </a>
                <div class="flex items-center">
                    <div class="flex-shrink-0 h-12 w-12 bg-amber-100 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h2 class="text-2xl sm:text-3xl font-bold text-gray-900">Edit Office</h2>
                        <p class="text-sm text-gray-600 mt-1">Perbarui informasi office: <span
                                class="font-semibold">{{ $office->name }}</span></p>
                    </div>
                </div>
            </div>

            <form action="{{ route('offices.update', $office) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <!-- Basic Information Card -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="px-4 sm:px-6 py-4 border-b border-gray-200 bg-gray-50">
                        <h3 class="text-base font-semibold text-gray-900 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-amber-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Informasi Dasar
                        </h3>
                    </div>
                    <div class="p-4 sm:p-6 space-y-5">
                        <!-- Nama Office -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Nama Office <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="name" value="{{ old('name', $office->name) }}"
                                class="w-full px-4 py-2.5 rounded-lg border-gray-300 shadow-sm focus:border-amber-500 focus:ring-2 focus:ring-amber-500 focus:ring-opacity-20 transition-all @error('name') border-red-500 ring-2 ring-red-200 @enderror"
                                placeholder="Contoh: Kantor Pusat Jakarta" required>
                            @error('name')
                                <p class="mt-2 text-sm text-red-600 flex items-start">
                                    <svg class="w-4 h-4 mr-1 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Alamat -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Alamat Lengkap <span class="text-red-500">*</span>
                            </label>
                            <textarea name="address" rows="3"
                                class="w-full px-4 py-2.5 rounded-lg border-gray-300 shadow-sm focus:border-amber-500 focus:ring-2 focus:ring-amber-500 focus:ring-opacity-20 transition-all @error('address') border-red-500 ring-2 ring-red-200 @enderror"
                                placeholder="Jl. Sudirman No. 123, Jakarta Pusat" required>{{ old('address', $office->address) }}</textarea>
                            @error('address')
                                <p class="mt-2 text-sm text-red-600 flex items-start">
                                    <svg class="w-4 h-4 mr-1 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Location Card -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="px-4 sm:px-6 py-4 border-b border-gray-200 bg-gray-50">
                        <h3 class="text-base font-semibold text-gray-900 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-amber-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            Lokasi Office
                        </h3>
                    </div>
                    <div class="p-4 sm:p-6 space-y-5">
                        <!-- Info Box -->
                        <div class="bg-gradient-to-r from-amber-50 to-orange-50 border border-amber-200 rounded-xl p-4">
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-amber-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3 flex-1">
                                    <p class="text-sm font-semibold text-amber-900 mb-2">Cara Mengubah Lokasi:</p>
                                    <ul class="text-sm text-amber-800 space-y-1.5">
                                        <li class="flex items-start">
                                            <span class="text-amber-600 mr-2">•</span>
                                            <span><strong>Klik pada peta</strong> untuk memindahkan lokasi office</span>
                                        </li>
                                        <li class="flex items-start">
                                            <span class="text-amber-600 mr-2">•</span>
                                            <span><strong>Geser marker</strong> untuk menyesuaikan posisi dengan
                                                tepat</span>
                                        </li>
                                        <li class="flex items-start">
                                            <span class="text-amber-600 mr-2">•</span>
                                            <span><strong>Gunakan pencarian</strong> untuk menemukan lokasi baru</span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <!-- Current Location Info -->
                        <div class="bg-blue-50 rounded-lg p-3 border border-blue-200">
                            <div class="flex items-start">
                                <svg class="w-5 h-5 text-blue-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <div class="ml-3 flex-1">
                                    <p class="text-xs font-semibold text-blue-900 mb-1">Lokasi Saat Ini:</p>
                                    <p class="text-xs text-blue-800 font-mono">
                                        {{ number_format($office->latitude, 6) }},
                                        {{ number_format($office->longitude, 6) }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Map Controls -->
                        <div class="space-y-3">
                            <label class="block text-sm font-semibold text-gray-700">
                                Ubah Lokasi di Peta <span class="text-red-500">*</span>
                            </label>

                            <!-- Search and Detect Buttons -->
                            <div class="flex flex-col sm:flex-row gap-2">
                                <button type="button" onclick="detectMyLocation()"
                                    class="inline-flex items-center justify-center px-4 py-2.5 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500 transition-all shadow-sm">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    Deteksi Lokasi Saya
                                </button>
                                <div class="flex-1 flex gap-2">
                                    <input type="text" id="searchLocation" placeholder="Cari lokasi baru..."
                                        class="flex-1 px-4 py-2.5 rounded-lg border-gray-300 shadow-sm focus:border-amber-500 focus:ring-2 focus:ring-amber-500 focus:ring-opacity-20 text-sm transition-all"
                                        onkeypress="if(event.key === 'Enter') { event.preventDefault(); searchLocation(); }">
                                    <button type="button" onclick="searchLocation()"
                                        class="inline-flex items-center px-4 py-2.5 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500 transition-all shadow-sm">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                        </svg>
                                    </button>
                                </div>
                            </div>

                            <!-- Map -->
                            <div class="relative">
                                <div id="map"
                                    class="w-full h-64 sm:h-96 rounded-xl border-2 border-gray-300 overflow-hidden shadow-inner">
                                </div>
                                <div id="mapLoading"
                                    class="absolute inset-0 bg-white bg-opacity-90 flex items-center justify-center rounded-xl">
                                    <div class="text-center">
                                        <svg class="w-8 h-8 animate-spin text-amber-600 mx-auto" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                        </svg>
                                        <p class="mt-2 text-sm text-gray-600">Memuat peta...</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Coordinates Display -->
                            <div class="bg-gray-50 rounded-lg p-3 border border-gray-200">
                                <div class="flex items-center text-sm">
                                    <svg class="w-4 h-4 text-gray-500 mr-2" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    </svg>
                                    <span class="text-gray-600 font-medium">Koordinat Baru:</span>
                                    <span id="coordinatesDisplay"
                                        class="ml-2 font-mono font-semibold text-gray-900">Loading...</span>
                                </div>
                            </div>
                        </div>

                        <!-- Hidden Inputs -->
                        <input type="hidden" name="latitude" id="latitude"
                            value="{{ old('latitude', $office->latitude) }}" required>
                        <input type="hidden" name="longitude" id="longitude"
                            value="{{ old('longitude', $office->longitude) }}" required>

                        @error('latitude')
                            <p class="text-sm text-red-600 flex items-start">
                                <svg class="w-4 h-4 mr-1 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                        clip-rule="evenodd" />
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                        @error('longitude')
                            <p class="text-sm text-red-600 flex items-start">
                                <svg class="w-4 h-4 mr-1 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                        clip-rule="evenodd" />
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                </div>

                <!-- Settings Card -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="px-4 sm:px-6 py-4 border-b border-gray-200 bg-gray-50">
                        <h3 class="text-base font-semibold text-gray-900 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-amber-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            Pengaturan
                        </h3>
                    </div>
                    <div class="p-4 sm:p-6 space-y-5">
                        <!-- Radius -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Radius Absensi (meter) <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input type="number" name="radius" id="radius"
                                    value="{{ old('radius', $office->radius) }}" min="10" max="5000"
                                    class="w-full px-4 py-2.5 pr-16 rounded-lg border-gray-300 shadow-sm focus:border-amber-500 focus:ring-2 focus:ring-amber-500 focus:ring-opacity-20 transition-all @error('radius') border-red-500 ring-2 ring-red-200 @enderror"
                                    placeholder="200" required onchange="updateRadiusCircle()"
                                    oninput="updateRadiusCircle()">
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                    <span class="text-gray-500 text-sm">meter</span>
                                </div>
                            </div>
                            @error('radius')
                                <p class="mt-2 text-sm text-red-600 flex items-start">
                                    <svg class="w-4 h-4 mr-1 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                            <p class="mt-2 text-xs text-gray-500 flex items-start">
                                <svg class="w-3.5 h-3.5 mr-1 mt-0.5 flex-shrink-0" fill="currentColor"
                                    viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                        clip-rule="evenodd" />
                                </svg>
                                Karyawan harus berada dalam radius ini untuk absen (10-5000 meter)
                            </p>
                        </div>

                        <!-- Status -->
                        <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                            <label class="flex items-start cursor-pointer">
                                <div class="flex items-center h-6">
                                    <input type="checkbox" name="is_active" value="1"
                                        {{ old('is_active', $office->is_active) ? 'checked' : '' }}
                                        class="w-5 h-5 rounded border-gray-300 text-amber-600 focus:ring-amber-500 focus:ring-2 focus:ring-opacity-20 transition-all cursor-pointer">
                                </div>
                                <div class="ml-3">
                                    <span class="text-sm font-semibold text-gray-900">Aktifkan office ini</span>
                                    <p class="text-xs text-gray-600 mt-0.5">Office yang aktif dapat digunakan untuk absensi
                                        karyawan</p>
                                </div>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div
                    class="flex flex-col-reverse sm:flex-row gap-3 sm:justify-between bg-white rounded-xl shadow-sm border border-gray-200 p-4 sm:p-6">
                    <a href="{{ route('offices.index') }}"
                        class="inline-flex items-center justify-center px-5 py-2.5 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-all shadow-sm">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Batal
                    </a>
                    <button type="submit"
                        class="inline-flex items-center justify-center px-5 py-2.5 border border-transparent text-sm font-medium rounded-lg text-white bg-amber-600 hover:bg-amber-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500 transition-all shadow-sm">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Update Office
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
@endpush

@push('scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        let map, marker, circle;

        // Get existing coordinates
        const existingLat = {{ $office->latitude }};
        const existingLng = {{ $office->longitude }};
        const existingRadius = {{ $office->radius }};

        document.addEventListener('DOMContentLoaded', function() {
            // Hide loading overlay after map loads
            setTimeout(() => {
                document.getElementById('mapLoading').style.display = 'none';
            }, 1000);

            // Initialize Leaflet map with existing coordinates
            map = L.map('map').setView([existingLat, existingLng], 17);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors',
                maxZoom: 19
            }).addTo(map);

            // Set initial marker and circle
            setMarker(existingLat, existingLng);

            // Add click event to map
            map.on('click', function(e) {
                setMarker(e.latlng.lat, e.latlng.lng);
            });
        });

        function setMarker(lat, lng) {
            // Remove existing marker if any
            if (marker) {
                map.removeLayer(marker);
            }
            if (circle) {
                map.removeLayer(circle);
            }

            // Add new marker
            marker = L.marker([lat, lng], {
                draggable: true
            }).addTo(map);

            marker.bindPopup(`
                <div class="text-center p-2">
                    <div class="font-semibold text-gray-900 mb-1">📍 Lokasi Office</div>
                    <div class="text-xs text-gray-600">
                        <div>Lat: ${lat.toFixed(6)}</div>
                        <div>Lng: ${lng.toFixed(6)}</div>
                    </div>
                </div>
            `).openPopup();

            // Add drag event to marker
            marker.on('dragend', function(e) {
                const position = e.target.getLatLng();
                setMarker(position.lat, position.lng);
            });

            // Update hidden inputs
            document.getElementById('latitude').value = lat.toFixed(8);
            document.getElementById('longitude').value = lng.toFixed(8);

            // Update display
            document.getElementById('coordinatesDisplay').textContent =
                `${lat.toFixed(6)}, ${lng.toFixed(6)}`;

            // Add radius circle
            updateRadiusCircle();
        }

        function updateRadiusCircle() {
            const lat = document.getElementById('latitude').value;
            const lng = document.getElementById('longitude').value;
            const radius = document.getElementById('radius').value || 200;

            if (lat && lng) {
                // Remove existing circle
                if (circle) {
                    map.removeLayer(circle);
                }

                // Add new circle
                circle = L.circle([lat, lng], {
                    color: '#d97706',
                    fillColor: '#d97706',
                    fillOpacity: 0.15,
                    weight: 2,
                    radius: parseInt(radius)
                }).addTo(map);

                // Fit map to circle bounds
                map.fitBounds(circle.getBounds(), {
                    padding: [50, 50]
                });
            }
        }

        function detectMyLocation() {
            if (!navigator.geolocation) {
                showNotification('Geolocation tidak didukung oleh browser Anda!', 'error');
                return;
            }

            const btn = event.target.closest('button');
            const originalText = btn.innerHTML;
            btn.innerHTML =
                '<svg class="w-4 h-4 animate-spin mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg> Mendeteksi...';
            btn.disabled = true;
            btn.classList.add('opacity-70', 'cursor-not-allowed');

            navigator.geolocation.getCurrentPosition(
                function(position) {
                    const lat = position.coords.latitude;
                    const lng = position.coords.longitude;

                    setMarker(lat, lng);
                    map.setView([lat, lng], 17);

                    btn.innerHTML = originalText;
                    btn.disabled = false;
                    btn.classList.remove('opacity-70', 'cursor-not-allowed');

                    showNotification('Lokasi berhasil terdeteksi dan diperbarui!', 'success');
                },
                function(error) {
                    let errorMsg = 'Gagal mendeteksi lokasi! ';
                    if (error.code === 1) {
                        errorMsg += 'Izinkan akses lokasi pada browser Anda.';
                    } else if (error.code === 2) {
                        errorMsg += 'Lokasi tidak tersedia.';
                    } else {
                        errorMsg += 'Timeout.';
                    }
                    showNotification(errorMsg, 'error');
                    console.error('Geolocation error:', error);
                    btn.innerHTML = originalText;
                    btn.disabled = false;
                    btn.classList.remove('opacity-70', 'cursor-not-allowed');
                }, {
                    enableHighAccuracy: true,
                    timeout: 10000,
                    maximumAge: 0
                }
            );
        }

        function searchLocation() {
            const query = document.getElementById('searchLocation').value;

            if (!query) {
                showNotification('Masukkan nama lokasi yang ingin dicari!', 'warning');
                return;
            }

            // Show loading state
            const searchBtn = event.target.closest('button');
            const searchInput = document.getElementById('searchLocation');
            const originalBtnContent = searchBtn.innerHTML;

            searchBtn.innerHTML =
                '<svg class="w-4 h-4 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>';
            searchBtn.disabled = true;
            searchInput.disabled = true;
            searchBtn.classList.add('opacity-70');

            // Using Nominatim (OpenStreetMap) geocoding
            fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}&limit=1`)
                .then(response => response.json())
                .then(data => {
                    if (data && data.length > 0) {
                        const lat = parseFloat(data[0].lat);
                        const lng = parseFloat(data[0].lon);

                        setMarker(lat, lng);
                        map.setView([lat, lng], 17);
                        showNotification('Lokasi ditemukan dan diperbarui!', 'success');
                    } else {
                        showNotification('Lokasi tidak ditemukan! Coba kata kunci lain.', 'error');
                    }
                })
                .catch(error => {
                    console.error('Search error:', error);
                    showNotification('Gagal mencari lokasi!', 'error');
                })
                .finally(() => {
                    searchBtn.innerHTML = originalBtnContent;
                    searchBtn.disabled = false;
                    searchInput.disabled = false;
                    searchBtn.classList.remove('opacity-70');
                });
        }

        function showNotification(message, type = 'info') {
            // Create notification element
            const notification = document.createElement('div');
            const bgColor = {
                'success': 'bg-green-50 border-green-400 text-green-800',
                'error': 'bg-red-50 border-red-400 text-red-800',
                'warning': 'bg-yellow-50 border-yellow-400 text-yellow-800',
                'info': 'bg-blue-50 border-blue-400 text-blue-800'
            } [type];

            const icon = {
                'success': '<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>',
                'error': '<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>',
                'warning': '<path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>',
                'info': '<path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>'
            } [type];

            notification.className =
                `fixed top-4 right-4 z-50 max-w-sm w-full sm:w-auto ${bgColor} border-l-4 rounded-r-lg shadow-lg p-4 transform transition-all duration-300 ease-in-out animate-slide-in`;
            notification.style.animation = 'slideIn 0.3s ease-out';
            notification.innerHTML = `
                <div class="flex items-start">
                    <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">${icon}</svg>
                    <div class="ml-3 flex-1">
                        <p class="text-sm font-medium">${message}</p>
                    </div>
                    <button onclick="this.parentElement.parentElement.remove()" class="ml-3 flex-shrink-0 hover:opacity-70 transition-opacity">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                        </svg>
                    </button>
                </div>
            `;

            // Add animation keyframes
            const style = document.createElement('style');
            style.textContent = `
                @keyframes slideIn {
                    from {
                        transform: translateX(100%);
                        opacity: 0;
                    }
                    to {
                        transform: translateX(0);
                        opacity: 1;
                    }
                }
                @keyframes slideOut {
                    from {
                        transform: translateX(0);
                        opacity: 1;
                    }
                    to {
                        transform: translateX(100%);
                        opacity: 0;
                    }
                }
            `;
            if (!document.querySelector('#notification-styles')) {
                style.id = 'notification-styles';
                document.head.appendChild(style);
            }

            document.body.appendChild(notification);

            // Auto remove after 5 seconds
            setTimeout(() => {
                notification.style.animation = 'slideOut 0.3s ease-in';
                setTimeout(() => notification.remove(), 300);
            }, 5000);
        }
    </script>
@endpush
