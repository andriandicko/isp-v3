@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-2xl mx-auto">
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center">
                    <a href="{{ route('billings.index') }}" class="text-gray-500 hover:text-gray-700 mr-4 transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                    </a>
                    <h1 class="text-2xl font-bold text-gray-800">Edit Tagihan</h1>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="bg-yellow-50 px-6 py-4 border-b border-yellow-100">
                    <div class="flex items-start">
                        <svg class="w-5 h-5 text-yellow-600 mr-2 mt-0.5" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <div>
                            <h3 class="text-sm font-bold text-yellow-800">Mode Koreksi</h3>
                            <p class="text-xs text-yellow-700 mt-1">
                                Anda sedang mengedit tagihan <strong>{{ $billing->billing_code }}</strong>.
                            </p>
                        </div>
                    </div>
                </div>

                <form action="{{ route('billings.update', $billing) }}" method="POST" class="p-6 space-y-6">
                    @csrf
                    @method('PUT')

                    <div class="p-4 bg-gray-50 rounded-lg border border-gray-100">
                        <label
                            class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Pelanggan</label>
                        <p class="font-medium text-gray-800">
                            {{ optional($billing->customer->user)->name ?? 'Customer Terhapus' }}</p>
                        <p class="text-sm text-gray-500">{{ optional($billing->customer)->company_name }}</p>
                    </div>

                    <div class="space-y-4">

                        <div>
                            <label for="package_id" class="block text-sm font-medium text-gray-700 mb-1">Paket
                                Internet</label>
                            <select name="package_id" id="package_id" onchange="updatePrice()"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                                @foreach ($packages as $package)
                                    <option value="{{ $package->id }}" data-price="{{ $package->price }}"
                                        {{ $billing->package_id == $package->id ? 'selected' : '' }}>
                                        {{ $package->name }} - Rp {{ number_format($package->price, 0, ',', '.') }}
                                    </option>
                                @endforeach
                            </select>
                            <p class="text-xs text-blue-500 mt-1">Mengganti paket akan otomatis mengubah nominal tagihan di
                                bawah.</p>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="start_date" class="block text-sm font-medium text-gray-700 mb-1">Mulai
                                    Periode</label>
                                <input type="date" name="start_date" id="start_date"
                                    value="{{ old('start_date', $billing->start_date ? $billing->start_date->format('Y-m-d') : '') }}"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                            </div>
                            <div>
                                <label for="end_date" class="block text-sm font-medium text-gray-700 mb-1">Akhir
                                    Periode</label>
                                <input type="date" name="end_date" id="end_date"
                                    value="{{ old('end_date', $billing->end_date ? $billing->end_date->format('Y-m-d') : '') }}"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                            </div>
                        </div>

                        <div>
                            <label for="amount" class="block text-sm font-medium text-gray-700 mb-1">Total Tagihan
                                (Rp)</label>
                            <input type="number" name="amount" id="amount"
                                value="{{ old('amount', $billing->amount) }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition font-bold text-gray-700"
                                required>
                            <p class="text-xs text-gray-500 mt-1">Anda bisa mengubah manual jika ada diskon atau denda
                                khusus.</p>
                        </div>

                        <div>
                            <label for="due_date" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Jatuh
                                Tempo</label>
                            <input type="date" name="due_date" id="due_date"
                                value="{{ old('due_date', $billing->due_date->format('Y-m-d')) }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                                required>
                        </div>

                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status
                                Pembayaran</label>
                            <select name="status" id="status"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                                <option value="pending" {{ $billing->status == 'pending' ? 'selected' : '' }}>Pending
                                    (Belum Bayar)</option>
                                <option value="paid" {{ $billing->status == 'paid' ? 'selected' : '' }}>Paid (Lunas)
                                </option>
                                <option value="overdue" {{ $billing->status == 'overdue' ? 'selected' : '' }}>Overdue
                                    (Terlambat)</option>
                            </select>
                        </div>

                        <div>
                            <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">Catatan Admin</label>
                            <textarea name="notes" id="notes" rows="3"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                                placeholder="Contoh: Koreksi harga karena kesalahan input...">{{ old('notes', $billing->notes) }}</textarea>
                        </div>
                    </div>

                    <div class="flex gap-4 pt-4 border-t border-gray-100">
                        <button type="submit"
                            class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 rounded-lg shadow transition transform hover:-translate-y-0.5">
                            Simpan Perubahan
                        </button>
                        <a href="{{ route('billings.index') }}"
                            class="flex-1 text-center bg-white border border-gray-300 text-gray-700 font-semibold py-2.5 rounded-lg hover:bg-gray-50 transition">
                            Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function updatePrice() {
            // Ambil elemen dropdown
            const select = document.getElementById('package_id');
            // Ambil opsi yang dipilih
            const selectedOption = select.options[select.selectedIndex];
            // Ambil data-price dari opsi tersebut
            const price = selectedOption.getAttribute('data-price');

            // Masukkan ke input amount
            if (price) {
                document.getElementById('amount').value = price;
            }
        }
    </script>
@endsection
