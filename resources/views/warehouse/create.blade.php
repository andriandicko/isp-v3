@extends('layouts.app')

@section('content')
    <div class="bg-white rounded-lg shadow-md">
        <!-- Header Section -->
        <div class="p-4 sm:p-6 border-b border-gray-200">
            <div class="flex items-center gap-3">
                <a href="{{ route('warehouse.index') }}"
                    class="inline-flex items-center justify-center w-10 h-10 rounded-lg hover:bg-gray-100 transition-colors duration-150">
                    <i class="fas fa-arrow-left text-gray-600"></i>
                </a>
                <div>
                    <h2 class="text-xl sm:text-2xl font-bold text-gray-800">
                        {{ isset($warehouse) ? 'Edit Warehouse' : 'Tambah Warehouse' }}
                    </h2>
                    <p class="text-sm text-gray-600 mt-0.5">
                        {{ isset($warehouse) ? 'Perbarui informasi warehouse' : 'Isi form untuk menambahkan warehouse baru' }}
                    </p>
                </div>
            </div>
        </div>

        <form action="{{ isset($warehouse) ? route('warehouse.update', $warehouse) : route('warehouse.store') }}"
            method="POST" class="p-4 sm:p-6">
            @csrf
            @if (isset($warehouse))
                @method('PUT')
            @endif

            <!-- Form Fields -->
            <div class="space-y-6">
                <!-- Informasi Dasar Section -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-info-circle text-blue-600 mr-2"></i>
                        Informasi Dasar
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
                        <!-- Kode Warehouse -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Kode Warehouse <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-barcode text-gray-400 text-sm"></i>
                                </div>
                                <input type="text" name="code" value="{{ old('code', $warehouse->code ?? '') }}"
                                    placeholder="Contoh: WH001"
                                    class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-150 @error('code') border-red-500 @enderror"
                                    required>
                            </div>
                            @error('code')
                                <p class="text-red-500 text-xs mt-1.5 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-1"></i>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Nama Warehouse -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Nama Warehouse <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-warehouse text-gray-400 text-sm"></i>
                                </div>
                                <input type="text" name="name" value="{{ old('name', $warehouse->name ?? '') }}"
                                    placeholder="Contoh: Warehouse Jakarta Pusat"
                                    class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-150 @error('name') border-red-500 @enderror"
                                    required>
                            </div>
                            @error('name')
                                <p class="text-red-500 text-xs mt-1.5 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-1"></i>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Status -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Status <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-toggle-on text-gray-400 text-sm"></i>
                                </div>
                                <select name="status"
                                    class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-150 appearance-none bg-white @error('status') border-red-500 @enderror"
                                    required>
                                    <option value="active"
                                        {{ old('status', $warehouse->status ?? '') == 'active' ? 'selected' : '' }}>
                                        Aktif
                                    </option>
                                    <option value="inactive"
                                        {{ old('status', $warehouse->status ?? '') == 'inactive' ? 'selected' : '' }}>
                                        Tidak Aktif
                                    </option>
                                </select>
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <i class="fas fa-chevron-down text-gray-400 text-sm"></i>
                                </div>
                            </div>
                            @error('status')
                                <p class="text-red-500 text-xs mt-1.5 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-1"></i>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Lokasi Section -->
                <div class="pt-6 border-t border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-map-marker-alt text-blue-600 mr-2"></i>
                        Lokasi
                    </h3>

                    <div class="grid grid-cols-1 gap-4 sm:gap-6">
                        <!-- Alamat -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Alamat Lengkap <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <textarea name="address" rows="3" placeholder="Contoh: Jl. Sudirman No. 123, Blok A"
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-150 resize-none @error('address') border-red-500 @enderror"
                                    required>{{ old('address', $warehouse->address ?? '') }}</textarea>
                                <div class="absolute top-3 right-3">
                                    <i class="fas fa-map text-gray-400 text-sm"></i>
                                </div>
                            </div>
                            @error('address')
                                <p class="text-red-500 text-xs mt-1.5 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-1"></i>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
                            <!-- Kota -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Kota <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-city text-gray-400 text-sm"></i>
                                    </div>
                                    <input type="text" name="city" value="{{ old('city', $warehouse->city ?? '') }}"
                                        placeholder="Contoh: Jakarta"
                                        class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-150 @error('city') border-red-500 @enderror"
                                        required>
                                </div>
                                @error('city')
                                    <p class="text-red-500 text-xs mt-1.5 flex items-center">
                                        <i class="fas fa-exclamation-circle mr-1"></i>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <!-- Provinsi -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Provinsi <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-globe-asia text-gray-400 text-sm"></i>
                                    </div>
                                    <input type="text" name="province"
                                        value="{{ old('province', $warehouse->province ?? '') }}"
                                        placeholder="Contoh: DKI Jakarta"
                                        class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-150 @error('province') border-red-500 @enderror"
                                        required>
                                </div>
                                @error('province')
                                    <p class="text-red-500 text-xs mt-1.5 flex items-center">
                                        <i class="fas fa-exclamation-circle mr-1"></i>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Kontak & Manager Section -->
                <div class="pt-6 border-t border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-address-book text-blue-600 mr-2"></i>
                        Kontak & Manager
                    </h3>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
                        <!-- Telepon -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Telepon
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-phone text-gray-400 text-sm"></i>
                                </div>
                                <input type="text" name="phone" value="{{ old('phone', $warehouse->phone ?? '') }}"
                                    placeholder="Contoh: 021-1234567"
                                    class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-150 @error('phone') border-red-500 @enderror">
                            </div>
                            @error('phone')
                                <p class="text-red-500 text-xs mt-1.5 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-1"></i>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Nama Manager -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Nama Manager
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-user-tie text-gray-400 text-sm"></i>
                                </div>
                                <input type="text" name="manager_name"
                                    value="{{ old('manager_name', $warehouse->manager_name ?? '') }}"
                                    placeholder="Contoh: John Doe"
                                    class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-150 @error('manager_name') border-red-500 @enderror">
                            </div>
                            @error('manager_name')
                                <p class="text-red-500 text-xs mt-1.5 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-1"></i>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-3 mt-8 pt-6 border-t border-gray-200">
                <a href="{{ route('warehouse.index') }}"
                    class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-2.5 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition-colors duration-150">
                    <i class="fas fa-times mr-2"></i>
                    Batal
                </a>
                <button type="submit"
                    class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-2.5 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors duration-150 shadow-sm">
                    <i class="fas fa-save mr-2"></i>
                    {{ isset($warehouse) ? 'Update' : 'Simpan' }}
                </button>
            </div>
        </form>
    </div>
@endsection
