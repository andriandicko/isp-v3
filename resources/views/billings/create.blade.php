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
                                    data-name="{{ $customer->user->name }}" 
                                    data-address="{{ $customer->address }}"
                                    data-phone="{{ $customer->user->phone ?? '-' }}"
                                    data-type="{{ $customer->type }}" 
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
                                <span class="text-gray-600">Tipe Layanan:</span>
                                <p class="font-medium uppercase" id="info-type">-</p>
                            </div>
                        </div>
                    </div>

                    <!-- Package -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Paket Internet <span class="text-red-500">*</span>
                        </label>
                        <select name="package_id" id="package_id"
                            class="w-full px-4 py-2 border rounded-lg @error('package_id') border-red-500 @enderror">
                            <option value="">Pilih Paket</option>
                            
                            {{-- OPSI CUSTOM MANUAL --}}
                            <option value="custom" class="font-bold text-blue-600" {{ old('package_id') == 'custom' ? 'selected' : '' }}>
                                + Input Manual / Custom (Harga Bebas)
                            </option>

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

                    <!-- Coverage Area -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Area Coverage
                        </label>
                        <input type="hidden" name="coverage_area_id" id="coverage_area_id"
                            value="{{ old('coverage_area_id') }}">
                        <div class="w-full px-4 py-2 border rounded-lg bg-gray-100 text-gray-700"
                            id="coverage-area-display">
                            Pilih customer terlebih dahulu
                        </div>
                        @error('coverage_area_id')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Tanggal Pemasangan -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Tanggal Tagihan <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="billing_date" value="{{ old('billing_date', date('Y-m-d')) }}" required
                            class="w-full px-4 py-2 border rounded-lg @error('billing_date') border-red-500 @enderror">
                        @error('billing_date')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Amount & Notes -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Harga Tagihan (Rp) <span class="text-red-500">*</span>
                        </label>
                        <input type="number" name="amount" id="amount" value="{{ old('amount') }}" required
                            min="0" readonly
                            class="w-full px-4 py-2 border rounded-lg bg-gray-100 cursor-not-allowed @error('amount') border-red-500 @enderror"
                            placeholder="Pilih paket terlebih dahulu">
                        
                        {{-- Input Tambahan: Catatan Paket Custom --}}
                        <div id="custom-notes-wrapper" class="hidden mt-2">
                            <label class="text-xs text-gray-600 block mb-1">Nama Paket Custom / Catatan <span class="text-red-500">*</span>:</label>
                            <input type="text" name="notes" id="notes" class="w-full px-3 py-1.5 border rounded text-sm" 
                                placeholder="Contoh: Paket Corporate Dedicated 50Mbps"
                                value="{{ old('notes') }}">
                        </div>

                        @error('amount')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                        @error('notes')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
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
        // Logic Paket: Custom vs Otomatis
        document.getElementById('package_id').addEventListener('change', function() {
            const selectedValue = this.value;
            const amountInput = document.getElementById('amount');
            const notesWrapper = document.getElementById('custom-notes-wrapper');
            const notesInput = document.getElementById('notes');

            if (selectedValue === 'custom') {
                // Mode Custom: Buka kunci input harga
                amountInput.readOnly = false;
                amountInput.value = '';
                amountInput.classList.remove('bg-gray-100', 'cursor-not-allowed');
                amountInput.classList.add('bg-white');
                amountInput.placeholder = 'Masukkan harga manual';
                amountInput.focus();
                
                // Tampilkan input catatan & wajib diisi
                notesWrapper.classList.remove('hidden');
                notesInput.required = true;
            } else {
                // Mode Paket Biasa: Kunci input harga & isi otomatis
                const selectedOption = this.options[this.selectedIndex];
                const price = selectedOption.dataset.price;
                
                amountInput.readOnly = true;
                amountInput.classList.add('bg-gray-100', 'cursor-not-allowed');
                amountInput.classList.remove('bg-white');
                notesWrapper.classList.add('hidden');
                notesInput.required = false;

                if (price) {
                    amountInput.value = price;
                }
            }
        });

        // Logic Customer Info & Coverage
        document.getElementById('customer_id').addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const customerInfo = document.getElementById('customer-info');
            const coverageAreaDisplay = document.getElementById('coverage-area-display');
            const coverageAreaId = document.getElementById('coverage_area_id');

            if (this.value) {
                // Isi Info Customer
                document.getElementById('info-name').textContent = selectedOption.dataset.name || '-';
                document.getElementById('info-address').textContent = selectedOption.dataset.address || '-';
                document.getElementById('info-type').textContent = selectedOption.dataset.type || '-';
                customerInfo.classList.remove('hidden');

                // Logic Coverage Area
                const coverageId = selectedOption.dataset.coverageAreaId;
                const coverageName = selectedOption.dataset.coverageAreaName;
                const type = selectedOption.dataset.type; 

                if (coverageId && coverageName && coverageName !== '-') {
                    coverageAreaId.value = coverageId;
                    coverageAreaDisplay.textContent = coverageName;
                    coverageAreaDisplay.className = 'w-full px-4 py-2 border rounded-lg bg-gray-100 text-gray-700';
                } else {
                    if (type === 'business') {
                        coverageAreaId.value = '';
                        coverageAreaDisplay.textContent = '🏢 Business Account (Non-Coverage Area)';
                        coverageAreaDisplay.className = 'w-full px-4 py-2 border rounded-lg bg-blue-50 text-blue-700 border-blue-200';
                    } else {
                        coverageAreaId.value = '';
                        coverageAreaDisplay.textContent = '⚠️ Error: Customer Residential wajib punya area coverage!';
                        coverageAreaDisplay.className = 'w-full px-4 py-2 border rounded-lg bg-red-50 text-red-700 border-red-200';
                    }
                }
            } else {
                customerInfo.classList.add('hidden');
                coverageAreaId.value = '';
                coverageAreaDisplay.textContent = 'Pilih customer terlebih dahulu';
                coverageAreaDisplay.className = 'w-full px-4 py-2 border rounded-lg bg-gray-100 text-gray-700';
            }
        });
    </script>
@endsection