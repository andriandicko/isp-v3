@extends('layouts.app')

@section('content')
    <main class="min-h-screen bg-gray-50 p-4 sm:p-6 lg:p-8">
        <div class="max-w-3xl mx-auto">
            <!-- Header Section -->
            <div class="mb-6 sm:mb-8">
                <a href="{{ route('packages.index') }}"
                    class="inline-flex items-center text-sm text-gray-600 hover:text-gray-900 mb-3 transition-colors duration-150">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    Kembali ke Daftar Paket
                </a>
                <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">
                    {{ isset($package) ? 'Edit Paket Internet' : 'Tambah Paket Baru' }}
                </h1>
                <p class="text-sm text-gray-600 mt-2">
                    {{ isset($package) ? 'Perbarui informasi paket internet' : 'Lengkapi formulir untuk menambahkan paket baru' }}
                </p>
            </div>

            <!-- Form Card -->
            <div class="bg-white shadow-sm rounded-xl border border-gray-200 overflow-hidden">
                <form action="{{ isset($package) ? route('packages.update', $package->id) : route('packages.store') }}"
                    method="POST" class="p-6 sm:p-8">
                    @csrf
                    @if (isset($package))
                        @method('PUT')
                    @endif

                    <div class="space-y-6">
                        <!-- Nama Paket -->
                        <div>
                            <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">
                                Nama Paket
                                <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="name" name="name"
                                value="{{ old('name', $package->name ?? '') }}" required
                                placeholder="Contoh: Paket Fiber 100 Mbps"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-gray-900 placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-150 @error('name') border-red-500 @enderror">
                            @error('name')
                                <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Area Cakupan -->
                        <div>
                            <label for="coverage_area_id" class="block text-sm font-semibold text-gray-700 mb-2">
                                Area Cakupan
                                <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <select id="coverage_area_id" name="coverage_area_id" required
                                    class="w-full appearance-none border border-gray-300 rounded-lg px-4 py-2.5 pr-10 text-gray-900 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-150 @error('coverage_area_id') border-red-500 @enderror">
                                    <option value="">Pilih area cakupan</option>
                                    @foreach ($coverageAreas as $area)
                                        <option value="{{ $area->id }}"
                                            {{ old('coverage_area_id', $package->coverage_area_id ?? '') == $area->id ? 'selected' : '' }}>
                                            {{ $area->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <div
                                    class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-500">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7" />
                                    </svg>
                                </div>
                            </div>
                            @error('coverage_area_id')
                                <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Grid 2 Kolom untuk Tipe dan Kecepatan -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <!-- Tipe Paket -->
                            <div>
                                <label for="type" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Tipe Paket
                                    <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <select id="type" name="type" required
                                        class="w-full appearance-none border border-gray-300 rounded-lg px-4 py-2.5 pr-10 text-gray-900 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-150 @error('type') border-red-500 @enderror">
                                        <option value="">Pilih tipe</option>
                                        <option value="residential"
                                            {{ old('type', $package->type ?? '') == 'residential' ? 'selected' : '' }}>
                                            Residential
                                        </option>
                                        <option value="business"
                                            {{ old('type', $package->type ?? '') == 'business' ? 'selected' : '' }}>
                                            Business
                                        </option>
                                    </select>
                                    <div
                                        class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-500">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </div>
                                </div>
                                @error('type')
                                    <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Kecepatan -->
                            <div>
                                <label for="speed" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Kecepatan
                                    <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <input type="text" id="speed" name="speed"
                                        value="{{ old('speed', $package->speed ?? '') }}" required placeholder="100 Mbps"
                                        class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-gray-900 placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-150 @error('speed') border-red-500 @enderror">
                                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M13 10V3L4 14h7v7l9-11h-7z" />
                                        </svg>
                                    </div>
                                </div>
                                @error('speed')
                                    <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Harga -->
                        <div>
                            <label for="price" class="block text-sm font-semibold text-gray-700 mb-2">
                                Harga per Bulan
                                <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">
                                    <span class="text-gray-500 font-medium">Rp</span>
                                </div>
                                <input type="number" id="price" name="price"
                                    value="{{ old('price', $package->price ?? '') }}" required min="0"
                                    step="1000" placeholder="300000"
                                    class="w-full border border-gray-300 rounded-lg pl-12 pr-4 py-2.5 text-gray-900 placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-150 @error('price') border-red-500 @enderror">
                            </div>
                            @error('price')
                                <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Deskripsi -->
                        <div>
                            <label for="description" class="block text-sm font-semibold text-gray-700 mb-2">
                                Deskripsi Paket
                            </label>
                            <textarea id="description" name="description" rows="4"
                                placeholder="Deskripsikan fitur dan keunggulan paket ini..."
                                class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-gray-900 placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-150 resize-none @error('description') border-red-500 @enderror">{{ old('description', $package->description ?? '') }}</textarea>
                            @error('description')
                                <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1.5 text-xs text-gray-500">Opsional. Jelaskan detail paket untuk membantu
                                pelanggan memahami penawaran.</p>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="mt-8 pt-6 border-t border-gray-200 flex flex-col-reverse sm:flex-row sm:justify-end gap-3">
                        <a href="{{ route('packages.index') }}"
                            class="w-full sm:w-auto px-6 py-2.5 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition-colors duration-150 text-center">
                            Batal
                        </a>
                        <button type="submit"
                            class="w-full sm:w-auto px-6 py-2.5 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 focus:ring-4 focus:ring-blue-200 transition-all duration-150 shadow-sm">
                            <span class="inline-flex items-center justify-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                                {{ isset($package) ? 'Perbarui Paket' : 'Simpan Paket' }}
                            </span>
                        </button>
                    </div>
                </form>
            </div>

            <!-- Info Box -->
            <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
                <div class="flex items-start">
                    <svg class="w-5 h-5 text-blue-600 mr-3 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                            clip-rule="evenodd" />
                    </svg>
                    <div>
                        <h3 class="text-sm font-semibold text-blue-900 mb-1">Tips Pengisian Form</h3>
                        <ul class="text-sm text-blue-800 space-y-1">
                            <li>• Pastikan nama paket jelas dan mudah dipahami</li>
                            <li>• Gunakan format standar untuk kecepatan (contoh: 50 Mbps, 1 Gbps)</li>
                            <li>• Harga dalam Rupiah tanpa desimal</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
