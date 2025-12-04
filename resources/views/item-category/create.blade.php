@extends('layouts.app')
@section('title', isset($itemCategory) ? 'Edit Kategori' : 'Tambah Kategori')
@section('content')
    <div class="container mx-auto px-4 py-6">
        <div class="max-w-3xl mx-auto">
            <!-- Breadcrumb -->
            <div class="mb-4">
                <nav class="flex items-center space-x-2 text-sm text-gray-600">
                    <a href="{{ route('item-category.index') }}" class="hover:text-blue-600 transition-colors">
                        <i class="fas fa-home"></i>
                    </a>
                    <span class="text-gray-400">/</span>
                    <a href="{{ route('item-category.index') }}" class="hover:text-blue-600 transition-colors">
                        Kategori Item
                    </a>
                    <span class="text-gray-400">/</span>
                    <span class="text-gray-900 font-medium">{{ isset($itemCategory) ? 'Edit' : 'Tambah' }}</span>
                </nav>
            </div>

            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <!-- Header -->
                <div class="p-4 sm:p-6 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-indigo-50">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-blue-600 rounded-lg flex items-center justify-center mr-4">
                            <i class="fas {{ isset($itemCategory) ? 'fa-edit' : 'fa-plus-circle' }} text-white text-xl"></i>
                        </div>
                        <div>
                            <h2 class="text-xl sm:text-2xl font-bold text-gray-800">
                                {{ isset($itemCategory) ? 'Edit Kategori' : 'Tambah Kategori' }}
                            </h2>
                            <p class="text-sm text-gray-600 mt-1">
                                {{ isset($itemCategory) ? 'Perbarui informasi kategori' : 'Isi form untuk menambahkan kategori baru' }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Form -->
                <form
                    action="{{ isset($itemCategory) ? route('item-category.update', $itemCategory) : route('item-category.store') }}"
                    method="POST" class="p-4 sm:p-6 lg:p-8">
                    @csrf
                    @if (isset($itemCategory))
                        @method('PUT')
                    @endif

                    <div class="space-y-6">
                        <!-- Nama Kategori -->
                        <div>
                            <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">
                                Nama Kategori <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-tag text-gray-400"></i>
                                </div>
                                <input type="text" id="name" name="name"
                                    value="{{ old('name', $itemCategory->name ?? '') }}"
                                    class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 @error('name') border-red-500 ring-2 ring-red-200 @enderror"
                                    placeholder="Contoh: Elektronik, Makanan, dll" required>
                            </div>
                            @error('name')
                                <div class="flex items-center mt-2 text-red-600">
                                    <i class="fas fa-exclamation-circle mr-2 text-sm"></i>
                                    <p class="text-sm">{{ $message }}</p>
                                </div>
                            @enderror
                        </div>

                        <!-- Deskripsi -->
                        <div>
                            <label for="description" class="block text-sm font-semibold text-gray-700 mb-2">
                                Deskripsi
                                <span class="text-gray-400 font-normal">(Opsional)</span>
                            </label>
                            <div class="relative">
                                <div class="absolute top-3 left-3 pointer-events-none">
                                    <i class="fas fa-align-left text-gray-400"></i>
                                </div>
                                <textarea id="description" name="description" rows="4"
                                    class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 resize-none @error('description') border-red-500 ring-2 ring-red-200 @enderror"
                                    placeholder="Jelaskan kategori ini...">{{ old('description', $itemCategory->description ?? '') }}</textarea>
                            </div>
                            @error('description')
                                <div class="flex items-center mt-2 text-red-600">
                                    <i class="fas fa-exclamation-circle mr-2 text-sm"></i>
                                    <p class="text-sm">{{ $message }}</p>
                                </div>
                            @enderror
                            <p class="text-xs text-gray-500 mt-1">
                                <i class="fas fa-info-circle mr-1"></i>
                                Deskripsi singkat tentang kategori ini
                            </p>
                        </div>

                        <!-- Info Box -->
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-lightbulb text-blue-600 text-lg"></i>
                                </div>
                                <div class="ml-3">
                                    <h4 class="text-sm font-semibold text-blue-800 mb-1">Tips</h4>
                                    <p class="text-xs text-blue-700">
                                        Gunakan nama kategori yang jelas dan mudah dipahami. Kategori yang baik akan
                                        membantu dalam mengorganisir produk dengan lebih efektif.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex flex-col sm:flex-row justify-end gap-3 mt-8 pt-6 border-t border-gray-200">
                        <a href="{{ route('item-category.index') }}"
                            class="inline-flex items-center justify-center px-6 py-3 border-2 border-gray-300 rounded-lg text-gray-700 font-medium hover:bg-gray-50 hover:border-gray-400 transition-all duration-200 order-2 sm:order-1">
                            <i class="fas fa-times mr-2"></i>
                            <span>Batal</span>
                        </a>
                        <button type="submit"
                            class="inline-flex items-center justify-center px-6 py-3 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 shadow-md hover:shadow-lg transition-all duration-200 order-1 sm:order-2">
                            <i class="fas {{ isset($itemCategory) ? 'fa-check' : 'fa-save' }} mr-2"></i>
                            <span>{{ isset($itemCategory) ? 'Update Kategori' : 'Simpan Kategori' }}</span>
                        </button>
                    </div>
                </form>
            </div>

            <!-- Helper Card -->
            <div class="mt-6 bg-gray-50 border border-gray-200 rounded-lg p-4 sm:p-6">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <i class="fas fa-question-circle text-gray-400 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <h4 class="text-sm font-semibold text-gray-800 mb-2">Butuh bantuan?</h4>
                        <p class="text-sm text-gray-600">
                            Kategori digunakan untuk mengelompokkan item. Pastikan nama kategori unik dan mudah dikenali.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
