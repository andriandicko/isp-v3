@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8" x-data="customerForm()">

        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Registrasi Pelanggan Baru</h2>
                <p class="text-sm text-gray-500 mt-1">Ikuti langkah-langkah di bawah untuk mendaftarkan pelanggan.</p>
            </div>
            <a href="{{ route('customers.index') }}"
                class="text-gray-500 hover:text-gray-700 font-medium flex items-center transition">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Batal
            </a>
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
                        <h3 class="text-sm font-medium text-red-800">Mohon periksa kesalahan berikut:</h3>
                        <ul class="mt-1 text-sm text-red-700 list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        <form id="createCustomerForm" action="{{ route('customers.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="space-y-8">

                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex items-center">
                        <span
                            class="bg-indigo-600 text-white w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold mr-3">1</span>
                        <h3 class="text-lg font-semibold text-gray-900">Tipe Layanan</h3>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                            <div @click="type = 'residential'"
                                class="relative flex flex-col p-4 bg-white border rounded-lg shadow-sm cursor-pointer transition duration-200 ease-in-out"
                                :class="type === 'residential' ? 'border-indigo-600 ring-1 ring-indigo-600 bg-indigo-50' :
                                    'border-gray-200 hover:border-indigo-300'">

                                <input type="radio" name="type" value="residential" x-model="type" class="sr-only">

                                <div class="flex items-center mb-2">
                                    <span class="p-2 rounded-md mr-3 transition-colors"
                                        :class="type === 'residential' ? 'bg-indigo-600 text-white' :
                                            'bg-blue-100 text-blue-600'">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                        </svg>
                                    </span>
                                    <span class="text-sm font-bold text-gray-900">Residential (Rumahan)</span>
                                </div>
                                <span class="text-xs text-gray-500 ml-12">Wajib tercover jaringan untuk mendaftar.</span>

                                <div x-show="type === 'residential'" class="absolute top-4 right-4 text-indigo-600">
                                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </div>

                            <div @click="type = 'business'"
                                class="relative flex flex-col p-4 bg-white border rounded-lg shadow-sm cursor-pointer transition duration-200 ease-in-out"
                                :class="type === 'business' ? 'border-indigo-600 ring-1 ring-indigo-600 bg-indigo-50' :
                                    'border-gray-200 hover:border-indigo-300'">

                                <input type="radio" name="type" value="business" x-model="type" class="sr-only">

                                <div class="flex items-center mb-2">
                                    <span class="p-2 rounded-md mr-3 transition-colors"
                                        :class="type === 'business' ? 'bg-indigo-600 text-white' :
                                            'bg-purple-100 text-purple-600'">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                        </svg>
                                    </span>
                                    <span class="text-sm font-bold text-gray-900">Business (Corporate)</span>
                                </div>
                                <span class="text-xs text-gray-500 ml-12">Bisa survey lokasi meskipun belum tercover.</span>

                                <div x-show="type === 'business'" class="absolute top-4 right-4 text-indigo-600">
                                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <div x-show="type !== ''" x-transition.opacity.duration.500ms
                    class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex items-center justify-between">
                        <div class="flex items-center">
                            <span
                                class="bg-indigo-600 text-white w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold mr-3">2</span>
                            <h3 class="text-lg font-semibold text-gray-900">Pilih Lokasi Pemasangan</h3>
                        </div>
                        <button type="button" @click="getUserLocation()"
                            class="text-xs bg-green-600 hover:bg-green-700 text-white px-3 py-1.5 rounded-md font-medium transition flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                </path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            Lokasi Saya
                        </button>
                    </div>

                    <div class="p-6">
                        <div class="relative">
                            <div x-show="checkingCoverage"
                                class="absolute inset-0 z-10 bg-white bg-opacity-75 flex items-center justify-center">
                                <div class="flex items-center text-indigo-600 font-semibold">
                                    <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-indigo-600"
                                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10"
                                            stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor"
                                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                        </path>
                                    </svg>
                                    Mengecek Coverage...
                                </div>
                            </div>

                            <div id="map" class="h-96 w-full rounded-lg border-2 border-gray-300 z-0"></div>
                        </div>

                        <div class="mt-4" x-show="locationSelected">
                            <div x-show="coverageStatus === 'covered'"
                                class="p-3 bg-green-50 border border-green-200 rounded-lg flex items-center text-green-800">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                                <div>
                                    <span class="font-bold">Lokasi Tercover!</span>
                                    <span class="text-sm block text-green-600"
                                        x-text="'Area: ' + coverageAreaName"></span>
                                </div>
                            </div>

                            <div x-show="coverageStatus === 'not_covered' && type === 'business'"
                                class="p-3 bg-yellow-50 border border-yellow-200 rounded-lg flex items-center text-yellow-800">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                                <div>
                                    <span class="font-bold">Diluar Coverage Area</span>
                                    <span class="text-sm block text-yellow-700">Karena tipe akun "Business", Anda tetap
                                        dapat melanjutkan pengisian data untuk pengajuan survey khusus.</span>
                                </div>
                            </div>
                        </div>

                        <input type="hidden" name="lat" x-model="lat">
                        <input type="hidden" name="lng" x-model="lng">
                    </div>
                </div>

                <div x-show="showFormFields" x-transition.opacity.duration.500ms
                    class="grid grid-cols-1 lg:grid-cols-2 gap-8">

                    <div class="space-y-8">
                        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex items-center">
                                <span
                                    class="bg-indigo-600 text-white w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold mr-3">3</span>
                                <h3 class="text-lg font-semibold text-gray-900">Data Customer</h3>
                            </div>
                            <div class="p-6 space-y-5">

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Pilih User Login <span
                                            class="text-red-500">*</span></label>
                                    <select name="user_id" required
                                        class="block w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-gray-900 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                        <option value="">-- Pilih User --</option>
                                        @foreach ($users as $user)
                                            <option value="{{ $user->id }}"
                                                {{ old('user_id') == $user->id ? 'selected' : '' }}>{{ $user->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Status Awal</label>
                                        <select name="status"
                                            class="block w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-gray-900 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                            <option value="active" selected>Active</option>
                                            <option value="pending">Pending</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Coverage Area</label>
                                        <input type="text" readonly x-model="coverageAreaName"
                                            class="block w-full rounded-lg border border-gray-300 bg-gray-100 px-4 py-2.5 text-gray-500 shadow-sm cursor-not-allowed sm:text-sm">
                                        <input type="hidden" name="coverage_area_id" x-model="coverageAreaId">

                                        <div x-show="!isCovered && type === 'business'" class="mt-2">
                                            <p class="text-xs text-red-500 mb-1 font-medium">Pilih manual (Business Only):
                                            </p>
                                            <select name="coverage_area_id" x-model="coverageAreaId"
                                                class="block w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-gray-900 shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500">
                                                <option value="">-- Pilih Area --</option>
                                                @foreach ($coverageAreas as $area)
                                                    <option value="{{ $area->id }}">{{ $area->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div x-show="type === 'business'" x-transition>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Perusahaan <span
                                            class="text-red-500">*</span></label>
                                    <input type="text" name="company_name"
                                        class="block w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-gray-900 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                        placeholder="PT. Contoh Sejahtera">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Contact Person (CP)</label>
                                    <input type="text" name="contact_person"
                                        class="block w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-gray-900 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                        placeholder="Nama Lengkap">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Alamat Lengkap</label>
                                    <textarea name="address" rows="2"
                                        class="block w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-gray-900 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                        placeholder="Jl. Kebon Jeruk No. 12..."></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                            <div class="px-6 py-4 border-b border-gray-100 bg-blue-50 flex items-center">
                                <h3 class="text-lg font-semibold text-blue-900">Data Perangkat</h3>
                            </div>
                            <div class="p-6 space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Korlap</label>
                                    <input type="text" readonly x-model="korlapName"
                                        class="block w-full rounded-lg border border-gray-300 bg-gray-100 px-4 py-2.5 text-gray-500 shadow-sm mb-2 sm:text-sm cursor-not-allowed">
                                    <input type="hidden" name="korlap_id" x-model="korlapId">

                                    <div x-show="!isCovered && type === 'business'">
                                        <select name="korlap_id" x-model="korlapId"
                                            class="block w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-gray-900 shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500">
                                            <option value="">-- Pilih Korlap Manual --</option>
                                            @foreach ($korlaps as $korlap)
                                                <option value="{{ $korlap->id }}">{{ $korlap->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">No. ODP</label>
                                        <input type="text" name="no_odp"
                                            class="block w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-gray-900 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 font-mono uppercase sm:text-sm placeholder-gray-400"
                                            placeholder="ODP-01">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">MAC ONT</label>
                                        <input type="text" name="mac_ont"
                                            class="block w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-gray-900 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 font-mono uppercase sm:text-sm placeholder-gray-400"
                                            placeholder="AA:BB:CC...">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-8">
                        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                            <div class="px-6 py-4 border-b border-gray-100 bg-yellow-50 flex items-center">
                                <h3 class="text-lg font-semibold text-yellow-900">Dokumentasi</h3>
                            </div>
                            <div class="p-6 space-y-6">
                                @foreach (['foto_rumah' => 'Foto Rumah', 'foto_ktp' => 'Foto KTP', 'foto_redaman' => 'Foto Redaman'] as $field => $label)
                                    <div>
                                        <label
                                            class="block text-sm font-medium text-gray-700 mb-2">{{ $label }}</label>
                                        <div
                                            class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:bg-gray-50 transition cursor-pointer relative bg-white">
                                            <input type="file" name="{{ $field }}" accept="image/*"
                                                class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10"
                                                onchange="previewImage(event, 'preview_{{ $field }}')">
                                            <div class="space-y-1 text-center"
                                                id="preview_container_{{ $field }}">
                                                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor"
                                                    fill="none" viewBox="0 0 48 48">
                                                    <path
                                                        d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                                                        stroke-width="2" stroke-linecap="round"
                                                        stroke-linejoin="round" />
                                                </svg>
                                                <div class="flex text-sm text-gray-600 justify-center">
                                                    <span
                                                        class="relative cursor-pointer bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500">Upload
                                                        file</span>
                                                </div>
                                                <p class="text-xs text-gray-500">PNG, JPG up to 2MB</p>
                                            </div>
                                            <img id="preview_{{ $field }}"
                                                class="hidden absolute inset-0 w-full h-full object-cover rounded-lg" />
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div
                            class="fixed bottom-0 left-0 right-0 p-4 bg-white border-t z-50 md:static md:bg-transparent md:border-none md:p-0 shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.1)] md:shadow-none">
                            <button type="submit"
                                class="w-full bg-indigo-600 text-white font-bold py-3.5 rounded-lg shadow-md hover:bg-indigo-700 transition transform hover:-translate-y-0.5 flex justify-center items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                                Simpan Data Pelanggan
                            </button>
                        </div>
                    </div>
                </div>

            </div>
        </form>

        <div x-show="showModal" x-cloak
            class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-60 backdrop-blur-sm"
            x-transition.opacity>
            <div
                class="bg-white rounded-xl shadow-2xl max-w-md w-full mx-4 overflow-hidden transform transition-all scale-100">
                <div class="bg-red-600 p-6 text-center">
                    <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-red-100 mb-4">
                        <svg class="h-8 w-8 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-white">Lokasi Tidak Tercover</h3>
                </div>
                <div class="p-6">
                    <p class="text-gray-600 text-center mb-6">
                        Mohon maaf, untuk layanan <strong>Residential (Rumahan)</strong>, lokasi yang Anda pilih belum
                        terjangkau jaringan kami.
                    </p>
                    <div class="space-y-3">
                        <a href="https://wa.me/6281234567890?text=Halo%20Admin,%20saya%20ingin%20request%20coverage%20di%20area..."
                            target="_blank"
                            class="w-full flex justify-center items-center px-4 py-3 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 transition">
                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.466c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.017-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z" />
                            </svg>
                            Hubungi Admin via WhatsApp
                        </a>
                        <button @click="showModal = false; resetMap()"
                            class="w-full flex justify-center items-center px-4 py-3 border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition">
                            Pilih Lokasi Lain
                        </button>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection

@push('scripts')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <script>
        function previewImage(event, previewId) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const img = document.getElementById(previewId);
                    img.src = e.target.result;
                    img.classList.remove('hidden');
                }
                reader.readAsDataURL(file);
            }
        }

        document.addEventListener('alpine:init', () => {
            Alpine.data('customerForm', () => ({
                type: '',
                lat: -6.200000,
                lng: 106.816666,
                map: null,
                marker: null,

                // Logic States
                coverageStatus: '', // 'covered', 'not_covered'
                isCovered: false,
                checkingCoverage: false,
                locationSelected: false,

                // Show/Hide Form
                showFormFields: false,
                showModal: false,

                // Form Data
                coverageAreaId: '',
                coverageAreaName: '',
                korlapId: '',
                korlapName: '',

                init() {
                    this.$watch('type', (val) => {
                        if (val) {
                            this.$nextTick(() => {
                                if (!this.map) this.initMap();
                                // Reset form if type changes
                                this.resetLogic();
                            });
                        }
                    });
                },

                resetLogic() {
                    this.showFormFields = false;
                    this.showModal = false;
                    this.coverageStatus = '';
                    this.locationSelected = false;
                },

                resetMap() {
                    this.showModal = false;
                    // Optional: Reset marker position? No need, user just picks new one.
                },

                submitForm(e) {
                    // Manual validation for Business + Not Covered scenario
                    if (this.type === 'business' && !this.isCovered) {
                        if (!confirm('Lokasi ini diluar coverage area. Lanjutkan simpan data?')) {
                            return;
                        }
                    }
                    e.target.submit();
                },

                initMap() {
                    this.map = L.map('map').setView([this.lat, this.lng], 13);

                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        maxZoom: 19,
                        attribution: '© OpenStreetMap'
                    }).addTo(this.map);

                    this.marker = L.marker([this.lat, this.lng], {
                        draggable: true
                    }).addTo(this.map);

                    // Event: Drag End
                    this.marker.on('dragend', (e) => {
                        const pos = e.target.getLatLng();
                        this.processLocation(pos.lat, pos.lng);
                    });

                    // Event: Map Click
                    this.map.on('click', (e) => {
                        this.marker.setLatLng(e.latlng);
                        this.processLocation(e.latlng.lat, e.latlng.lng);
                    });

                    setTimeout(() => this.map.invalidateSize(), 200);
                },

                getUserLocation() {
                    if (navigator.geolocation) {
                        navigator.geolocation.getCurrentPosition(pos => {
                            const lat = pos.coords.latitude;
                            const lng = pos.coords.longitude;
                            this.marker.setLatLng([lat, lng]);
                            this.map.setView([lat, lng], 16);
                            this.processLocation(lat, lng);
                        }, () => alert('Gagal mendapatkan lokasi.'));
                    } else {
                        alert('Browser tidak support geolocation.');
                    }
                },

                async processLocation(lat, lng) {
                    this.lat = parseFloat(lat).toFixed(7);
                    this.lng = parseFloat(lng).toFixed(7);
                    this.locationSelected = true;
                    this.checkingCoverage = true;

                    try {
                        const response = await fetch("{{ route('customers.check-coverage') }}", {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                // Pastikan meta csrf-token ada di layout
                                'X-CSRF-TOKEN': document.querySelector(
                                    'meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: JSON.stringify({
                                lat,
                                lng
                            })
                        });

                        // Ambil JSON response
                        const data = await response.json();
                        this.checkingCoverage = false;

                        // Cek jika server mengirim sinyal Error
                        if (!response.ok || data.error) {
                            throw new Error(data.message || 'Terjadi kesalahan pada server.');
                        }

                        if (data.covered) {
                            // LOGIKA TERCOVER (Sama seperti sebelumnya)
                            this.isCovered = true;
                            this.coverageStatus = 'covered';
                            this.showFormFields = true;
                            this.showModal = false;

                            this.coverageAreaId = data.coverage_area.id;
                            this.coverageAreaName = data.coverage_area.name;
                            if (data.korlap) {
                                this.korlapId = data.korlap.id;
                                this.korlapName = data.korlap.name;
                            } else {
                                this.korlapName = 'Belum ada Korlap';
                            }
                        } else {
                            // LOGIKA TIDAK TERCOVER
                            this.isCovered = false;
                            this.coverageStatus = 'not_covered';
                            this.coverageAreaId = '';
                            this.coverageAreaName = '';
                            this.korlapId = '';
                            this.korlapName = '';

                            if (this.type === 'business') {
                                this.showFormFields = true;
                                this.showModal = false;
                            } else {
                                this.showFormFields = false;
                                this.showModal = true;
                            }
                        }

                    } catch (error) {
                        console.error(error);
                        this.checkingCoverage = false;
                        // TAMPILKAN PESAN ERROR ASLI DARI SERVER DI SINI
                        alert('Gagal: ' + error.message);
                    }
                }
            }));
        });
    </script>
@endpush
