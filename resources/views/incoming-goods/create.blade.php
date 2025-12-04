@extends('layouts.app')

@section('title', 'Tambah Barang Masuk')

@section('content')
    <div class="bg-white rounded-lg shadow-md">
        <div class="p-6 border-b border-gray-200">
            <h2 class="text-2xl font-bold text-gray-800">Tambah Barang Masuk</h2>
        </div>

        <form action="{{ route('incoming-goods.store') }}" method="POST" class="p-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Kode Transaksi *</label>
                    <input type="text" name="transaction_code" value="{{ $transactionCode }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-50" readonly>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal *</label>
                    <input type="date" name="transaction_date" value="{{ old('transaction_date', date('Y-m-d')) }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 @error('transaction_date') border-red-500 @enderror"
                        required>
                    @error('transaction_date')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Warehouse *</label>
                    <select name="warehouse_id"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 @error('warehouse_id') border-red-500 @enderror"
                        required>
                        <option value="">Pilih Warehouse</option>
                        @foreach ($warehouses as $warehouse)
                            <option value="{{ $warehouse->id }}"
                                {{ old('warehouse_id') == $warehouse->id ? 'selected' : '' }}>
                                {{ $warehouse->code }} - {{ $warehouse->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('warehouse_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Supplier *</label>
                    <select name="supplier_id"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 @error('supplier_id') border-red-500 @enderror"
                        required>
                        <option value="">Pilih Supplier</option>
                        @foreach ($suppliers as $supplier)
                            <option value="{{ $supplier->id }}"
                                {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                {{ $supplier->code }} - {{ $supplier->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('supplier_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">No. Invoice</label>
                    <input type="text" name="invoice_number" value="{{ old('invoice_number') }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 @error('invoice_number') border-red-500 @enderror">
                    @error('invoice_number')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Catatan</label>
                    <input type="text" name="notes" value="{{ old('notes') }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 @error('notes') border-red-500 @enderror">
                    @error('notes')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="border-t pt-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold">Detail Item</h3>
                    <button type="button" onclick="addItem()"
                        class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                        <i class="fas fa-plus mr-2"></i>Tambah Item
                    </button>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200" id="itemsTable">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Item</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Qty</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Harga</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Subtotal</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Catatan</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200" id="itemsBody">
                            <!-- Items will be added here dynamically -->
                        </tbody>
                        <tfoot class="bg-gray-50">
                            <tr>
                                <td colspan="3" class="px-4 py-3 text-right font-bold">Total:</td>
                                <td class="px-4 py-3 font-bold" id="grandTotal">Rp 0</td>
                                <td colspan="2"></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <div class="flex justify-end space-x-3 mt-6">
                <a href="{{ route('incoming-goods.index') }}"
                    class="px-6 py-2 border border-gray-300 rounded-lg hover:bg-gray-50">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    Simpan
                </button>
            </div>
        </form>
    </div>

    @push('scripts')
        <script>
            let itemIndex = 0;
            const items = @json($items);

            function addItem() {
                const tbody = document.getElementById('itemsBody');
                const row = document.createElement('tr');
                row.id = `item-row-${itemIndex}`;

                row.innerHTML = `
        <td class="px-4 py-3">
            <select name="items[${itemIndex}][item_id]" class="w-full px-3 py-2 border border-gray-300 rounded" required onchange="updateItemInfo(${itemIndex})">
                <option value="">Pilih Item</option>
                ${items.map(item => `<option value="${item.id}" data-price="${item.price}">${item.code} - ${item.name} (${item.unit})</option>`).join('')}
            </select>
        </td>
        <td class="px-4 py-3">
            <input type="number" name="items[${itemIndex}][quantity]" step="0.01" min="0.01" 
                class="w-24 px-3 py-2 border border-gray-300 rounded" required onchange="calculateSubtotal(${itemIndex})">
        </td>
        <td class="px-4 py-3">
            <input type="number" name="items[${itemIndex}][price]" step="0.01" min="0" 
                class="w-32 px-3 py-2 border border-gray-300 rounded" required onchange="calculateSubtotal(${itemIndex})">
        </td>
        <td class="px-4 py-3">
            <span id="subtotal-${itemIndex}" class="font-semibold">Rp 0</span>
        </td>
        <td class="px-4 py-3">
            <input type="text" name="items[${itemIndex}][notes]" class="w-full px-3 py-2 border border-gray-300 rounded">
        </td>
        <td class="px-4 py-3">
            <button type="button" onclick="removeItem(${itemIndex})" class="text-red-600 hover:text-red-900">
                <i class="fas fa-trash"></i>
            </button>
        </td>
    `;

                tbody.appendChild(row);
                itemIndex++;
            }

            function updateItemInfo(index) {
                const select = document.querySelector(`select[name="items[${index}][item_id]"]`);
                const priceInput = document.querySelector(`input[name="items[${index}][price]"]`);

                if (select.selectedIndex > 0) {
                    const price = select.options[select.selectedIndex].dataset.price;
                    priceInput.value = price;
                    calculateSubtotal(index);
                }
            }

            function calculateSubtotal(index) {
                const qtyInput = document.querySelector(`input[name="items[${index}][quantity]"]`);
                const priceInput = document.querySelector(`input[name="items[${index}][price]"]`);
                const subtotalSpan = document.getElementById(`subtotal-${index}`);

                const qty = parseFloat(qtyInput.value) || 0;
                const price = parseFloat(priceInput.value) || 0;
                const subtotal = qty * price;

                subtotalSpan.textContent = formatCurrency(subtotal);
                calculateGrandTotal();
            }

            // function calculateGrandTotal() {
            //     let total = 0;
            //     const rows = document.querySelectorAll('#itemsBody tr');

            //     rows.forEach(row => {
            //         const subtotalText = row.querySelector('[id^="subtotal-"]').textContent;
            //         const subtotal = parseFloat(subtotalText.replace(/[^0-9.-]+/g, "")) || 0;
            //         total += subtotal;
            //     });

            //     document.getElementById('grandTotal').textContent = formatCurrency(total);
            // }

            function calculateGrandTotal() {
                let total = 0;

                // Loop semua input quantity yang ada
                document.querySelectorAll('input[name^="items"][name$="[quantity]"]').forEach(qtyInput => {
                    // Ambil index dari name attribute
                    const match = qtyInput.name.match(/items\[(\d+)\]\[quantity\]/);
                    if (match) {
                        const index = match[1];
                        const priceInput = document.querySelector(`input[name="items[${index}][price]"]`);

                        if (priceInput) {
                            const qty = parseFloat(qtyInput.value) || 0;
                            const price = parseFloat(priceInput.value) || 0;
                            total += (qty * price);
                        }
                    }
                });

                document.getElementById('grandTotal').textContent = formatCurrency(total);
            }

            function removeItem(index) {
                const row = document.getElementById(`item-row-${index}`);
                row.remove();
                calculateGrandTotal();
            }

            function formatCurrency(amount) {
                return 'Rp ' + new Intl.NumberFormat('id-ID').format(amount);
            }

            // Add first row on load
            window.onload = function() {
                addItem();
            };
        </script>
    @endpush
@endsection
