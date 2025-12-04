@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-4xl mx-auto">
            <div class="flex items-center mb-6">
                <a href="{{ route('billings.index') }}" class="text-blue-600 hover:text-blue-800 mr-4">
                    ← Kembali
                </a>
                <h1 class="text-3xl font-bold text-gray-800">Tambah Billing Baru</h1>
            </div>

            @if (session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    {{ session('error') }}
                </div>
            @endif

            <form action="{{ route('billings.store') }}" method="POST" enctype="multipart/form-data"
                class="bg-white rounded-lg shadow-md p-6">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Customer -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Customer <span class="text-red-500">*</span>
                        </label>
                        <select name="customer_id" id="customer_id" required
                            class="w-full px-4 py-2 border rounded-lg @error('customer_id') border-red-500 @enderror">
                            <option value="">Pilih Customer</option>
                            @foreach ($customers as $customer)
                                <option value="{{ $customer->id }}"
                                    {{ old('customer_id') == $customer->id ? 'selected' : '' }}
                                    data-name="{{ $customer->user->name }}" data-address="{{ $customer->address }}"
                                    data-phone="{{ $customer->user->phone ?? '-' }}"
                                    data-coverage-area-id="{{ $customer->coverage_area_id }}"
                                    data-coverage-area-name="{{ $customer->coverageArea->name ?? '-' }}">
                                    {{ $customer->user->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('customer_id')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Display Customer Info -->
                    <div id="customer-info" class="md:col-span-2 hidden bg-gray-50 p-4 rounded-lg">
                        <h3 class="font-semibold mb-2">Informasi Customer</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                            <div>
                                <span class="text-gray-600">Nama:</span>
                                <p class="font-medium" id="info-name">-</p>
                            </div>
                            <div>
                                <span class="text-gray-600">Alamat:</span>
                                <p class="font-medium" id="info-address">-</p>
                            </div>
                            <div>
                                <span class="text-gray-600">Telepon:</span>
                                <p class="font-medium" id="info-phone">-</p>
                            </div>
                        </div>
                    </div>

                    <!-- Package -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Paket Internet <span class="text-red-500">*</span>
                        </label>
                        <select name="package_id" id="package_id" required
                            class="w-full px-4 py-2 border rounded-lg @error('package_id') border-red-500 @enderror">
                            <option value="">Pilih Paket</option>
                            @foreach ($packages as $package)
                                <option value="{{ $package->id }}"
                                    {{ old('package_id') == $package->id ? 'selected' : '' }}
                                    data-price="{{ $package->price }}">
                                    {{ $package->name }} - Rp {{ number_format($package->price, 0, ',', '.') }}/bulan
                                </option>
                            @endforeach
                        </select>
                        @error('package_id')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Coverage Area - HIDDEN INPUT & DISPLAY ONLY -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Area Coverage <span class="text-red-500">*</span>
                        </label>
                        <input type="hidden" name="coverage_area_id" id="coverage_area_id"
                            value="{{ old('coverage_area_id') }}">
                        <div class="w-full px-4 py-2 border rounded-lg bg-gray-100 text-gray-700"
                            id="coverage-area-display">
                            Pilih customer terlebih dahulu
                        </div>
                        <p class="text-xs text-gray-500 mt-1">Area coverage otomatis dari data customer</p>
                        @error('coverage_area_id')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Tanggal Pemasangan -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Tanggal Pemasangan <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="billing_date" value="{{ old('billing_date', date('Y-m-d')) }}" required
                            class="w-full px-4 py-2 border rounded-lg @error('billing_date') border-red-500 @enderror">
                        @error('billing_date')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Amount - READONLY -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Harga (Rp) <span class="text-red-500">*</span>
                        </label>
                        <input type="number" name="amount" id="amount" value="{{ old('amount') }}" required
                            min="0" readonly
                            class="w-full px-4 py-2 border rounded-lg bg-gray-100 cursor-not-allowed @error('amount') border-red-500 @enderror"
                            placeholder="Pilih paket untuk melihat harga">
                        <p class="text-xs text-gray-500 mt-1">Harga otomatis dari paket yang dipilih</p>
                        @error('amount')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- No ODP -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            No ODP
                        </label>
                        <input type="text" name="no_odp" value="{{ old('no_odp') }}"
                            class="w-full px-4 py-2 border rounded-lg @error('no_odp') border-red-500 @enderror"
                            placeholder="Masukkan No ODP">
                        @error('no_odp')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- MAC ONT -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            MAC ONT
                        </label>
                        <input type="text" name="mac_ont" value="{{ old('mac_ont') }}"
                            class="w-full px-4 py-2 border rounded-lg @error('mac_ont') border-red-500 @enderror"
                            placeholder="Masukkan MAC ONT">
                        @error('mac_ont')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Foto KTP -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Foto KTP
                        </label>
                        <input type="file" name="foto_ktp" accept="image/*"
                            class="w-full px-4 py-2 border rounded-lg @error('foto_ktp') border-red-500 @enderror"
                            onchange="previewImage(this, 'preview-ktp')">
                        <p class="text-xs text-gray-500 mt-1">Format: JPEG, PNG, JPG (Max: 2MB)</p>
                        @error('foto_ktp')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                        <img id="preview-ktp" class="mt-2 max-w-xs rounded hidden">
                    </div>

                    <!-- Foto Rumah -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Foto Rumah
                        </label>
                        <input type="file" name="foto_rumah" accept="image/*"
                            class="w-full px-4 py-2 border rounded-lg @error('foto_rumah') border-red-500 @enderror"
                            onchange="previewImage(this, 'preview-rumah')">
                        <p class="text-xs text-gray-500 mt-1">Format: JPEG, PNG, JPG (Max: 2MB)</p>
                        @error('foto_rumah')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                        <img id="preview-rumah" class="mt-2 max-w-xs rounded hidden">
                    </div>

                    <!-- Foto Redaman -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Foto Redaman
                        </label>
                        <input type="file" name="foto_redaman" accept="image/*"
                            class="w-full px-4 py-2 border rounded-lg @error('foto_redaman') border-red-500 @enderror"
                            onchange="previewImage(this, 'preview-redaman')">
                        <p class="text-xs text-gray-500 mt-1">Format: JPEG, PNG, JPG (Max: 2MB)</p>
                        @error('foto_redaman')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                        <img id="preview-redaman" class="mt-2 max-w-xs rounded hidden">
                    </div>
                </div>

                <div class="mt-6 flex gap-4">
                    <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-lg">
                        Simpan Billing
                    </button>
                    <a href="{{ route('billings.index') }}"
                        class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-6 py-2 rounded-lg">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Auto-fill amount from package
        document.getElementById('package_id').addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const price = selectedOption.dataset.price;
            if (price) {
                document.getElementById('amount').value = price;
            }
        });

        // Show customer info & auto-fill coverage area
        document.getElementById('customer_id').addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const customerInfo = document.getElementById('customer-info');
            const coverageAreaDisplay = document.getElementById('coverage-area-display');
            const coverageAreaId = document.getElementById('coverage_area_id');

            if (this.value) {
                // Show customer info
                document.getElementById('info-name').textContent = selectedOption.dataset.name || '-';
                document.getElementById('info-address').textContent = selectedOption.dataset.address || '-';
                document.getElementById('info-phone').textContent = selectedOption.dataset.phone || '-';
                customerInfo.classList.remove('hidden');

                // Auto-fill coverage area
                const coverageId = selectedOption.dataset.coverageAreaId;
                const coverageName = selectedOption.dataset.coverageAreaName;

                if (coverageId && coverageName && coverageName !== '-') {
                    coverageAreaId.value = coverageId;
                    coverageAreaDisplay.textContent = coverageName;
                    coverageAreaDisplay.classList.remove('text-red-500');
                    coverageAreaDisplay.classList.add('text-gray-700');
                } else {
                    coverageAreaId.value = '';
                    coverageAreaDisplay.textContent = '⚠️ Customer tidak memiliki area coverage';
                    coverageAreaDisplay.classList.add('text-red-500');
                    coverageAreaDisplay.classList.remove('text-gray-700');
                }
            } else {
                customerInfo.classList.add('hidden');
                coverageAreaId.value = '';
                coverageAreaDisplay.textContent = 'Pilih customer terlebih dahulu';
                coverageAreaDisplay.classList.remove('text-red-500');
                coverageAreaDisplay.classList.add('text-gray-700');
            }
        });

        // Preview image
        function previewImage(input, previewId) {
            const preview = document.getElementById(previewId);
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.classList.remove('hidden');
                };
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
@endsection
