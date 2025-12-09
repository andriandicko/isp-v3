@extends('layouts.app')

@section('title', 'Tambah Barang Masuk')

@section('content')
    {{-- DEFINISI LOGIKA DI ATAS AGAR AMAN (Global Function) --}}
    <script>
        function incomingGoodsForm() {
            return {
                items: [
                    { item_id: '', quantity: 1, price: 0, subtotal: 0, notes: '' }
                ],

                // Fungsi Tambah Baris
                addItem() {
                    this.items.push({ item_id: '', quantity: 1, price: 0, subtotal: 0, notes: '' });
                },

                // Fungsi Hapus Baris
                removeItem(index) {
                    if (this.items.length > 1) {
                        this.items.splice(index, 1);
                    }
                },

                // Hitung Subtotal per Baris
                calculateSubtotal(index) {
                    let item = this.items[index];
                    item.subtotal = (parseFloat(item.quantity) || 0) * (parseFloat(item.price) || 0);
                },

                // Hitung Total Keseluruhan (Computed Property ala Alpine)
                get grandTotal() {
                    return this.items.reduce((sum, item) => sum + (parseFloat(item.subtotal) || 0), 0);
                },

                // Format Rupiah
                formatRupiah(number) {
                    return new Intl.NumberFormat('id-ID').format(number);
                }
            };
        }
    </script>

    <div class="container mx-auto px-4 py-8" x-data="incomingGoodsForm()">
        <div class="max-w-5xl mx-auto">
            <div class="flex items-center mb-6">
                <a href="{{ route('incoming-goods.index') }}" class="text-blue-600 hover:text-blue-800 mr-4">
                    ← Kembali
                </a>
                <h1 class="text-3xl font-bold text-gray-800">Input Barang Masuk</h1>
            </div>

            @if ($errors->any())
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded shadow-sm">
                    <p class="font-bold">Terjadi Kesalahan:</p>
                    <ul class="list-disc list-inside text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('incoming-goods.store') }}" method="POST" class="space-y-6">
                @csrf

                <!-- CARD 1: INFORMASI UMUM -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                        <h2 class="font-semibold text-gray-800">Informasi Transaksi</h2>
                    </div>
                    <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Kode Transaksi (Readonly) -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Kode Transaksi</label>
                            <input type="text" name="transaction_code" value="{{ $transactionCode }}" readonly
                                class="w-full px-4 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-600 cursor-not-allowed font-mono">
                        </div>

                        <!-- Tanggal -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Masuk <span class="text-red-500">*</span></label>
                            <input type="date" name="transaction_date" value="{{ old('transaction_date', date('Y-m-d')) }}" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <!-- Warehouse -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Gudang Tujuan <span class="text-red-500">*</span></label>
                            <select name="warehouse_id" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">-- Pilih Gudang --</option>
                                @foreach ($warehouses as $warehouse)
                                    <option value="{{ $warehouse->id }}" {{ old('warehouse_id') == $warehouse->id ? 'selected' : '' }}>
                                        {{ $warehouse->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Supplier -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Supplier <span class="text-red-500">*</span></label>
                            <select name="supplier_id" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">-- Pilih Supplier --</option>
                                @foreach ($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}" {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                        {{ $supplier->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- No Invoice -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">No. Invoice / SJ (Opsional)</label>
                            <input type="text" name="invoice_number" value="{{ old('invoice_number') }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                                placeholder="Contoh: INV-001/2025">
                        </div>

                        <!-- Notes -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Catatan</label>
                            <textarea name="notes" rows="2"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                                placeholder="Catatan tambahan...">{{ old('notes') }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- CARD 2: DAFTAR BARANG -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="bg-gray-50 px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                        <h2 class="font-semibold text-gray-800">Detail Barang</h2>
                        
                        <!-- TOMBOL TAMBAH ITEM -->
                        <button type="button" @click="addItem()"
                            class="text-sm bg-blue-100 text-blue-700 px-3 py-1.5 rounded-lg hover:bg-blue-200 transition font-medium flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                            Tambah Item
                        </button>
                    </div>
                    
                    <div class="p-6">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead>
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/3">Produk</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-24">Qty</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Harga Satuan</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subtotal</th>
                                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-16">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 bg-white">
                                    <template x-for="(item, index) in items" :key="index">
                                        <tr>
                                            <td class="px-4 py-3 align-top">
                                                <select :name="`items[${index}][item_id]`" x-model="item.item_id" required
                                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 text-sm">
                                                    <option value="">Pilih Produk</option>
                                                    @foreach ($items as $product)
                                                        <option value="{{ $product->id }}">
                                                            {{ $product->name }} ({{ $product->code }})
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <input type="text" :name="`items[${index}][notes]`" x-model="item.notes" 
                                                    placeholder="Catatan item (opsional)"
                                                    class="mt-1 w-full text-xs border-0 border-b border-gray-200 focus:ring-0 focus:border-blue-500 px-0 py-1 text-gray-500">
                                            </td>
                                            <td class="px-4 py-3 align-top">
                                                <input type="number" :name="`items[${index}][quantity]`" x-model="item.quantity" min="1" required
                                                    @input="calculateSubtotal(index)"
                                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 text-sm text-center">
                                            </td>
                                            <td class="px-4 py-3 align-top">
                                                <div class="relative">
                                                    <span class="absolute left-3 top-2 text-gray-500 text-sm">Rp</span>
                                                    <input type="number" :name="`items[${index}][price]`" x-model="item.price" min="0" required
                                                        @input="calculateSubtotal(index)"
                                                        class="w-full pl-8 pr-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 text-sm text-right">
                                                </div>
                                            </td>
                                            <td class="px-4 py-3 align-top">
                                                <div class="py-2 text-right font-medium text-gray-700 text-sm">
                                                    Rp <span x-text="formatRupiah(item.subtotal)"></span>
                                                </div>
                                            </td>
                                            <td class="px-4 py-3 align-top text-center">
                                                <button type="button" @click="removeItem(index)" 
                                                    class="text-red-500 hover:text-red-700 bg-red-50 hover:bg-red-100 p-2 rounded-lg transition"
                                                    :disabled="items.length === 1"
                                                    :class="items.length === 1 ? 'opacity-50 cursor-not-allowed' : ''">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                </button>
                                            </td>
                                        </tr>
                                    </template>
                                </tbody>
                                <tfoot>
                                    <tr class="bg-gray-50">
                                        <td colspan="3" class="px-4 py-3 text-right font-bold text-gray-700">Total Keseluruhan:</td>
                                        <td class="px-4 py-3 text-right font-bold text-blue-600 text-lg">
                                            Rp <span x-text="formatRupiah(grandTotal)"></span>
                                        </td>
                                        <td></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                        <!-- Pesan jika kosong (Safety check) -->
                        <div x-show="items.length === 0" class="text-center py-8 text-gray-500">
                            Belum ada item ditambahkan. Klik tombol "Tambah Item".
                        </div>
                    </div>
                </div>

                <!-- TOMBOL SIMPAN -->
                <div class="flex justify-end gap-4">
                    <a href="{{ route('incoming-goods.index') }}" 
                        class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-6 py-3 rounded-lg font-medium transition">
                        Batal
                    </a>
                    <button type="submit" 
                        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium shadow-md transition transform hover:-translate-y-0.5">
                        Simpan Transaksi
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection