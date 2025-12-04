@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
            <div>
                <div class="flex items-center gap-2 text-sm text-gray-500 mb-1">
                    <a href="{{ route('customers.index') }}" class="hover:text-indigo-600 transition">Customers</a>
                    <span>/</span>
                    <span>Edit</span>
                </div>
                <h2 class="text-2xl font-bold text-gray-900">Edit Pelanggan</h2>
                <p class="text-sm text-gray-500">Perbarui data pelanggan: {{ $customer->user->name ?? 'Unknown' }}</p>
            </div>

            <div class="hidden md:flex items-center gap-3">
                <a href="{{ route('customers.index') }}"
                    class="px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 font-medium transition shadow-sm">
                    Batal
                </a>
                <button type="submit" form="editCustomerForm"
                    class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 font-medium transition shadow-sm flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Simpan Perubahan
                </button>
            </div>
        </div>

        @if ($errors->any())
            <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-r-lg shadow-sm animate-fade-in-down">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-red-800">Terdapat kesalahan input:</h3>
                        <ul class="mt-1 text-sm text-red-700 list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        <form id="editCustomerForm" action="{{ route('customers.update', $customer) }}" method="POST"
            enctype="multipart/form-data" x-data="customerForm()">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                <div class="lg:col-span-2 space-y-8">

                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex items-center">
                            <div class="bg-indigo-100 p-2 rounded-lg mr-3">
                                <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900">Informasi Akun</h3>
                        </div>

                        <div class="p-6 space-y-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Pilih User (Akun Login)</label>
                                <div class="relative">
                                    <select name="user_id" required
                                        class="block w-full pl-3 pr-10 py-2.5 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-lg transition">
                                        <option value="">-- Pilih User --</option>
                                        @foreach ($users as $user)
                                            <option value="{{ $user->id }}"
                                                {{ old('user_id', $customer->user_id) == $user->id ? 'selected' : '' }}>
                                                {{ $user->name }} ({{ $user->email }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Tipe Layanan</label>
                                    <select name="type" x-model="type"
                                        class="block w-full py-2.5 border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm transition">
                                        <option value="residential">Residential (Rumahan)</option>
                                        <option value="business">Business (Bisnis)</option>
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Status Layanan</label>
                                    <select name="status"
                                        class="block w-full py-2.5 border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm transition">
                                        <option value="active"
                                            {{ old('status', $customer->status) == 'active' ? 'selected' : '' }}>🟢 Active
                                        </option>
                                        <option value="isolir"
                                            {{ old('status', $customer->status) == 'isolir' ? 'selected' : '' }}>🔴 Isolir
                                        </option>
                                        <option value="inactive"
                                            {{ old('status', $customer->status) == 'inactive' ? 'selected' : '' }}>⚪
                                            Inactive</option>
                                    </select>
                                </div>
                            </div>

                            <div x-show="type === 'business'" x-transition
                                class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Perusahaan</label>
                                <div class="relative rounded-md shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                                            </path>
                                        </svg>
                                    </div>
                                    <input type="text" name="company_name"
                                        value="{{ old('company_name', $customer->company_name) }}"
                                        class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-10 sm:text-sm border-gray-300 rounded-lg py-2.5"
                                        placeholder="PT. Contoh Sejahtera">
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Penanggung Jawab (CP)</label>
                                <div class="relative rounded-md shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2">
                                            </path>
                                        </svg>
                                    </div>
                                    <input type="text" name="contact_person"
                                        value="{{ old('contact_person', $customer->contact_person) }}"
                                        class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-10 sm:text-sm border-gray-300 rounded-lg py-2.5"
                                        placeholder="Nama Lengkap">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex items-center">
                            <div class="bg-green-100 p-2 rounded-lg mr-3">
                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                    </path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900">Lokasi Pemasangan</h3>
                        </div>
                        <div class="p-6 space-y-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Coverage Area</label>
                                <select name="coverage_area_id" required
                                    class="block w-full py-2.5 border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500 sm:text-sm transition">
                                    <option value="">-- Pilih Area --</option>
                                    @foreach ($coverageAreas as $area)
                                        <option value="{{ $area->id }}"
                                            {{ old('coverage_area_id', $customer->coverage_area_id) == $area->id ? 'selected' : '' }}>
                                            {{ $area->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Alamat Lengkap</label>
                                <textarea name="address" rows="3"
                                    class="block w-full rounded-lg border-gray-300 shadow-sm focus:ring-green-500 focus:border-green-500 sm:text-sm p-3"
                                    placeholder="Jl. Contoh No. 123...">{{ old('address', $customer->address) }}</textarea>
                            </div>

                            <div class="border border-gray-200 rounded-xl overflow-hidden bg-gray-50">
                                <div class="p-3 border-b border-gray-200 flex justify-between items-center bg-white">
                                    <label class="text-sm font-medium text-gray-700">Titik Koordinat</label>
                                    <button type="button" @click="getUserLocation()"
                                        class="text-xs bg-green-50 text-green-700 px-3 py-1.5 rounded-md hover:bg-green-100 font-medium transition flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                            </path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg>
                                        Gunakan Lokasi Saya
                                    </button>
                                </div>
                                <div id="map" class="h-72 w-full z-0"></div>
                                <div class="p-3 bg-white border-t border-gray-200 grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="text-xs text-gray-500 block mb-1">Latitude</label>
                                        <input type="number" step="0.0000001" name="lat" x-model="lat" required
                                            class="block w-full text-sm border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500 py-1.5 bg-gray-50">
                                    </div>
                                    <div>
                                        <label class="text-xs text-gray-500 block mb-1">Longitude</label>
                                        <input type="number" step="0.0000001" name="lng" x-model="lng" required
                                            class="block w-full text-sm border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500 py-1.5 bg-gray-50">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="space-y-8">

                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex items-center">
                            <div class="bg-blue-100 p-2 rounded-lg mr-3">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z">
                                    </path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900">Data Teknis</h3>
                        </div>
                        <div class="p-6 space-y-5">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Koordinator (Korlap)</label>
                                <select name="korlap_id"
                                    class="block w-full py-2.5 border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 sm:text-sm transition">
                                    <option value="">-- Pilih Korlap --</option>
                                    @foreach ($korlaps as $korlap)
                                        <option value="{{ $korlap->id }}"
                                            {{ old('korlap_id', $customer->korlap_id) == $korlap->id ? 'selected' : '' }}>
                                            {{ $korlap->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">No. ODP / Port</label>
                                <input type="text" name="no_odp" value="{{ old('no_odp', $customer->no_odp) }}"
                                    class="block w-full border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 sm:text-sm py-2.5 font-mono"
                                    placeholder="ODP-JKT-01/2">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">MAC Address ONT</label>
                                <input type="text" name="mac_ont" value="{{ old('mac_ont', $customer->mac_ont) }}"
                                    class="block w-full border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 sm:text-sm py-2.5 font-mono"
                                    placeholder="AA:BB:CC:DD:EE:FF">
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex items-center">
                            <div class="bg-yellow-100 p-2 rounded-lg mr-3">
                                <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                    </path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900">Dokumentasi</h3>
                        </div>
                        <div class="p-6 space-y-6">
                            @foreach (['foto_rumah' => 'Foto Rumah', 'foto_ktp' => 'Foto KTP', 'foto_redaman' => 'Foto Redaman'] as $field => $label)
                                <div>
                                    <label
                                        class="block text-sm font-medium text-gray-700 mb-2">{{ $label }}</label>
                                    <div class="flex items-center space-x-4">
                                        @if ($customer->$field)
                                            <div
                                                class="relative group w-16 h-16 rounded-lg overflow-hidden border border-gray-200 flex-shrink-0 cursor-pointer">
                                                <img src="{{ Storage::url($customer->$field) }}"
                                                    class="w-full h-full object-cover">
                                                <a href="{{ Storage::url($customer->$field) }}" target="_blank"
                                                    class="absolute inset-0 bg-black bg-opacity-40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition">
                                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                    </svg>
                                                </a>
                                            </div>
                                        @endif
                                        <input type="file" name="{{ $field }}" accept="image/*"
                                            class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-yellow-50 file:text-yellow-700 hover:file:bg-yellow-100 transition cursor-pointer">
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="md:hidden fixed bottom-0 left-0 right-0 p-4 bg-white border-t border-gray-200 z-50">
                        <button type="submit"
                            class="w-full bg-indigo-600 text-white font-bold py-3 rounded-lg shadow-lg hover:bg-indigo-700 active:scale-95 transition">
                            Simpan Perubahan
                        </button>
                    </div>

                </div>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('customerForm', () => ({
                type: '{{ old('type', $customer->type) }}',
                lat: {{ old('lat', $customer->lat) }},
                lng: {{ old('lng', $customer->lng) }},
                map: null,
                marker: null,

                init() {
                    const checkLeaflet = setInterval(() => {
                        if (typeof L !== 'undefined') {
                            clearInterval(checkLeaflet);
                            this.initMap();
                        }
                    }, 100);
                },

                initMap() {
                    this.map = L.map('map').setView([this.lat, this.lng], 15);

                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        maxZoom: 19,
                        attribution: '© OpenStreetMap'
                    }).addTo(this.map);

                    this.marker = L.marker([this.lat, this.lng], {
                        draggable: true
                    }).addTo(this.map);

                    this.marker.on('dragend', (e) => {
                        const pos = e.target.getLatLng();
                        this.updateCoords(pos.lat, pos.lng);
                    });

                    this.map.on('click', (e) => {
                        this.marker.setLatLng(e.latlng);
                        this.updateCoords(e.latlng.lat, e.latlng.lng);
                    });

                    // Fix map sizing
                    setTimeout(() => this.map.invalidateSize(), 200);

                    // Watch manual inputs
                    this.$watch('lat', val => this.updateMarker(val, this.lng));
                    this.$watch('lng', val => this.updateMarker(this.lat, val));
                },

                updateCoords(lat, lng) {
                    this.lat = parseFloat(lat).toFixed(7);
                    this.lng = parseFloat(lng).toFixed(7);
                },

                updateMarker(lat, lng) {
                    if (this.map && this.marker) {
                        const newLatLng = [lat, lng];
                        this.marker.setLatLng(newLatLng);
                        this.map.panTo(newLatLng);
                    }
                },

                getUserLocation() {
                    if (navigator.geolocation) {
                        navigator.geolocation.getCurrentPosition(position => {
                            const lat = position.coords.latitude;
                            const lng = position.coords.longitude;
                            this.updateCoords(lat, lng);
                            this.updateMarker(lat, lng);
                        }, () => {
                            alert('Gagal mendapatkan lokasi. Pastikan GPS aktif.');
                        });
                    } else {
                        alert('Browser tidak mendukung Geolocation.');
                    }
                }
            }));
        });
    </script>
@endpush
