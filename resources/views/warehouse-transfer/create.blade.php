{{-- resources/views/warehouse-transfer/create.blade.php --}}
@extends('layouts.app')

@section('title', 'Tambah Transfer Barang')

@section('content')
    <div class="bg-white rounded-lg shadow-md">
        <div class="p-6 border-b border-gray-200">
            <h2 class="text-2xl font-bold text-gray-800">Tambah Transfer Barang</h2>
        </div>

        <form action="{{ route('warehouse-transfer.store') }}" method="POST" class="p-6">
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
                    <label class="block text-sm font-medium text-gray-700 mb-2">Dari Warehouse *</label>
                    <select name="from_warehouse_id" id="fromWarehouseSelect"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 @error('from_warehouse_id') border-red-500 @enderror"
                        required>
                        <option value="">Pilih Warehouse Asal</option>
                        @foreach ($warehouses as $warehouse)
                            <option value="{{ $warehouse->id }}"
                                {{ old('from_warehouse_id') == $warehouse->id ? 'selected' : '' }}>
                                {{ $warehouse->code }} - {{ $warehouse->name }} ({{ $warehouse->city }})
                            </option>
                        @endforeach
                    </select>
                    @error('from_warehouse_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Ke Warehouse *</label>
                    <select name="to_warehouse_id" id="toWarehouseSelect"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 @error('to_warehouse_id') border-red-500 @enderror"
                        required>
                        <option value="">Pilih Warehouse Tujuan</option>
                        @foreach ($warehouses as $warehouse)
                            <option value="{{ $warehouse->id }}"
                                {{ old('to_warehouse_id') == $warehouse->id ? 'selected' : '' }}>
                                {{ $warehouse->code }} - {{ $warehouse->name }} ({{ $warehouse->city }})
                            </option>
                        @endforeach
                    </select>
                    @error('to_warehouse_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Catatan</label>
                    <textarea name="notes" rows="2"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 @error('notes') border-red-500 @enderror">{{ old('notes') }}</textarea>
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
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Stok Tersedia
                                </th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Qty Transfer
                                </th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Catatan</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200" id="itemsBody">
                            <!-- Items will be added here dynamically -->
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="flex justify-end space-x-3 mt-6">
                <a href="{{ route('warehouse-transfer.index') }}"
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
                const fromWarehouseId = document.getElementById('fromWarehouseSelect').value;

                if (!fromWarehouseId) {
                    alert('Pilih warehouse asal terlebih dahulu!');
                    return;
                }

                const tbody = document.getElementById('itemsBody');
                const row = document.createElement('tr');
                row.id = `item-row-${itemIndex}`;

                row.innerHTML = `
        <td class="px-4 py-3">
            <select name="items[${itemIndex}][item_id]" class="w-full px-3 py-2 border border-gray-300 rounded" required onchange="checkStock(${itemIndex}, ${fromWarehouseId})">
                <option value="">Pilih Item</option>
                ${items.map(item => `<option value="${item.id}">${item.code} - ${item.name} (${item.unit})</option>`).join('')}
            </select>
        </td>
        <td class="px-4 py-3">
            <span id="stock-${itemIndex}" class="font-semibold text-blue-600">-</span>
        </td>
        <td class="px-4 py-3">
            <input type="number" name="items[${itemIndex}][quantity]" step="0.01" min="0.01" 
                class="w-24 px-3 py-2 border border-gray-300 rounded" required>
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

            async function checkStock(index, warehouseId) {
                const select = document.querySelector(`select[name="items[${index}][item_id]"]`);
                const stockSpan = document.getElementById(`stock-${index}`);
                const itemId = select.value;

                if (!itemId) {
                    stockSpan.textContent = '-';
                    return;
                }

                try {
                    const response = await fetch(
                        `{{ route('warehouse-transfer.stock.check') }}?warehouse_id=${warehouseId}&item_id=${itemId}`);
                    const data = await response.json();
                    stockSpan.textContent = data.quantity;

                    if (data.quantity <= 0) {
                        stockSpan.classList.add('text-red-600');
                        stockSpan.classList.remove('text-blue-600');
                        alert('Peringatan: Stok tidak tersedia di warehouse ini!');
                    } else {
                        stockSpan.classList.add('text-blue-600');
                        stockSpan.classList.remove('text-red-600');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    stockSpan.textContent = 'Error';
                }
            }

            function removeItem(index) {
                const row = document.getElementById(`item-row-${index}`);
                row.remove();
            }

            // Validation: from and to warehouse must be different
            document.getElementById('fromWarehouseSelect').addEventListener('change', validateWarehouses);
            document.getElementById('toWarehouseSelect').addEventListener('change', validateWarehouses);

            function validateWarehouses() {
                const fromWarehouse = document.getElementById('fromWarehouseSelect').value;
                const toWarehouse = document.getElementById('toWarehouseSelect').value;

                if (fromWarehouse && toWarehouse && fromWarehouse === toWarehouse) {
                    alert('Warehouse tujuan harus berbeda dengan warehouse asal!');
                    document.getElementById('toWarehouseSelect').value = '';
                }
            }

            // Add first row on load
            window.onload = function() {
                addItem();
            };
        </script>
    @endpush
@endsection
