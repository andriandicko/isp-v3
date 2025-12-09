@extends('layouts.app')

@section('title', 'Input Barang Keluar')

@section('content')
    {{-- DEFINISI LOGIKA GLOBAL (Agar Alpine terbaca sempurna) --}}
    <script>
        // Data Produk
        const productsList = @json($items);
        // Data User untuk Multi Select
        const usersList = @json($users);

        function outgoingGoodsForm() {
            return {
                // --- LOGIKA ITEM BARANG ---
                items: [
                    { item_id: '', quantity: 1, stock: '-', unit: '', notes: '' }
                ],
                warehouse_id: '',
                
                // --- LOGIKA MULTI SELECT USER ---
                searchUser: '',
                openUserDropdown: false,
                selectedUserIds: [], // Menyimpan ID user yang dipilih
                userOptions: usersList,

                // --- MODAL ALERT ---
                alertOpen: false,
                alertMessage: '',

                init() {
                    this.$watch('warehouse_id', (val) => {
                        if(val) {
                            this.items.forEach((item, index) => {
                                if(item.item_id) this.checkStock(index);
                            });
                        }
                    });
                },

                // --- Methods Multi Select User ---
                get filteredUsers() {
                    if (this.searchUser === '') {
                        return this.userOptions.filter(u => !this.selectedUserIds.includes(u.value));
                    }
                    return this.userOptions.filter(u => 
                        u.text.toLowerCase().includes(this.searchUser.toLowerCase()) && 
                        !this.selectedUserIds.includes(u.value)
                    );
                },

                getUserName(id) {
                    const user = this.userOptions.find(u => u.value == id);
                    return user ? user.text : id;
                },

                selectUser(id) {
                    if (!this.selectedUserIds.includes(id)) {
                        this.selectedUserIds.push(id);
                    }
                    this.searchUser = '';
                    this.$refs.userSearchInput.focus();
                },

                removeUser(id) {
                    this.selectedUserIds = this.selectedUserIds.filter(uid => uid !== id);
                },

                // --- Methods Item Barang ---
                addItem() {
                    this.items.push({ item_id: '', quantity: 1, stock: '-', unit: '', notes: '' });
                },

                removeItem(index) {
                    if (this.items.length > 1) {
                        this.items.splice(index, 1);
                    }
                },

                showAlert(message) {
                    this.alertMessage = message;
                    this.alertOpen = true;
                },

                closeAlert() {
                    this.alertOpen = false;
                },

                async checkStock(index) {
                    let item = this.items[index];
                    
                    const selectedProduct = productsList.find(p => p.id == item.item_id);
                    if (selectedProduct) {
                        item.unit = selectedProduct.unit;
                    } else {
                        item.unit = '';
                    }

                    if (!this.warehouse_id) {
                        this.showAlert('Silakan pilih Gudang Asal terlebih dahulu sebelum memilih barang!');
                        item.item_id = '';
                        return;
                    }

                    if (!item.item_id) {
                        item.stock = '-';
                        return;
                    }

                    item.stock = 'Memuat...';

                    try {
                        const response = await fetch(`{{ route('outgoing-goods.stock.check') }}?warehouse_id=${this.warehouse_id}&item_id=${item.item_id}`);
                        const data = await response.json();
                        item.stock = Math.floor(data.quantity);
                    } catch (error) {
                        console.error(error);
                        item.stock = 'Error';
                    }
                }
            };
        }
    </script>

    <div class="container mx-auto px-4 py-8" x-data="outgoingGoodsForm()">
        <div class="max-w-5xl mx-auto">
            <div class="flex items-center mb-6">
                <a href="{{ route('outgoing-goods.index') }}" class="text-blue-600 hover:text-blue-800 mr-4">
                    ← Kembali
                </a>
                <h1 class="text-3xl font-bold text-gray-800">Input Barang Keluar</h1>
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

            <form action="{{ route('outgoing-goods.store') }}" method="POST" class="space-y-6">
                @csrf

                <!-- CARD 1: INFORMASI UMUM -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                        <h2 class="font-semibold text-gray-800">Informasi Transaksi</h2>
                    </div>
                    <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Kode Transaksi -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Kode Transaksi (Awal)</label>
                            <input type="text" value="{{ $transactionCode }} (Auto Generate)" readonly
                                class="w-full px-4 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-500 cursor-not-allowed font-mono text-sm">
                            <p class="text-xs text-gray-400 mt-1">*Kode akan dibuat otomatis untuk setiap penerima.</p>
                        </div>

                        <!-- Tanggal -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Keluar <span class="text-red-500">*</span></label>
                            <input type="date" name="transaction_date" value="{{ old('transaction_date', date('Y-m-d')) }}" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <!-- Warehouse (Source) -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Gudang Asal <span class="text-red-500">*</span></label>
                            <select name="warehouse_id" x-model="warehouse_id" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">-- Pilih Gudang --</option>
                                @foreach ($warehouses as $warehouse)
                                    <option value="{{ $warehouse->id }}" {{ old('warehouse_id') == $warehouse->id ? 'selected' : '' }}>
                                        {{ $warehouse->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- MULTI SELECT PENERIMA (PENGGANTI RECIPIENT_NAME) -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Pilih Penerima (Karyawan) <span class="text-red-500">*</span></label>
                            
                            <div class="relative">
                                <!-- Box Input & Tags -->
                                <div class="border border-gray-300 rounded-lg p-2 flex flex-wrap gap-2 bg-white focus-within:ring-2 focus-within:ring-blue-500 focus-within:border-blue-500 min-h-[42px]"
                                     @click="$refs.userSearchInput.focus()">
                                    
                                    <!-- Selected Tags -->
                                    <template x-for="id in selectedUserIds" :key="id">
                                        <div class="bg-blue-100 text-blue-800 text-sm px-3 py-1 rounded-full flex items-center shadow-sm border border-blue-200">
                                            <span x-text="getUserName(id)" class="mr-1 font-medium"></span>
                                            <button type="button" @click.stop="removeUser(id)" class="text-blue-500 hover:text-blue-700 focus:outline-none">
                                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                                            </button>
                                            <!-- Hidden Input -->
                                            <input type="hidden" name="recipient_ids[]" :value="id">
                                        </div>
                                    </template>

                                    <!-- Search Input -->
                                    <input type="text" x-ref="userSearchInput" x-model="searchUser" 
                                        @focus="openUserDropdown = true" @click.away="openUserDropdown = false" 
                                        @keydown.escape="openUserDropdown = false"
                                        class="flex-1 border-none outline-none focus:ring-0 bg-transparent text-sm min-w-[150px] p-1"
                                        placeholder="Cari nama karyawan...">
                                </div>

                                <!-- Dropdown List -->
                                <div x-show="openUserDropdown && filteredUsers.length > 0" 
                                     class="absolute z-10 w-full mt-1 bg-white border border-gray-200 rounded-lg shadow-xl max-h-60 overflow-y-auto"
                                     style="display: none;" x-transition>
                                    <template x-for="option in filteredUsers" :key="option.value">
                                        <div @click="selectUser(option.value)" 
                                             class="px-4 py-2.5 cursor-pointer hover:bg-blue-50 text-sm text-gray-700 border-b border-gray-50 last:border-0 transition-colors">
                                            <span x-text="option.text"></span>
                                        </div>
                                    </template>
                                </div>
                                <div x-show="openUserDropdown && filteredUsers.length === 0" 
                                     class="absolute z-10 w-full mt-1 bg-white border border-gray-200 rounded-lg shadow-xl p-3 text-sm text-gray-500 text-center"
                                     style="display: none;">
                                    Karyawan tidak ditemukan.
                                </div>
                            </div>
                            <p class="text-xs text-gray-500 mt-1">Anda bisa memilih lebih dari satu penerima. Transaksi akan dibuat untuk masing-masing penerima.</p>
                        </div>

                        <!-- Departemen -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Departemen / Divisi</label>
                            <input type="text" name="department" value="{{ old('department') }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                                placeholder="Contoh: Tim Instalasi A">
                        </div>

                        <!-- Keperluan -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Keperluan</label>
                            <input type="text" name="purpose" value="{{ old('purpose') }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                                placeholder="Contoh: Pasang Baru di Blok C">
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
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-32">Stok Gudang</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-32">Qty/Orang</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Catatan Item</th>
                                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-16">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 bg-white">
                                    <template x-for="(item, index) in items" :key="index">
                                        <tr>
                                            <td class="px-4 py-3 align-top">
                                                <select :name="`items[${index}][item_id]`" x-model="item.item_id" @change="checkStock(index)" required
                                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 text-sm">
                                                    <option value="">Pilih Produk</option>
                                                    @foreach ($items as $product)
                                                        <option value="{{ $product->id }}">
                                                            {{ $product->name }} ({{ $product->code }})
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <p class="text-xs text-red-500 mt-1 italic" x-show="!warehouse_id">Pilih gudang asal terlebih dahulu.</p>
                                            </td>
                                            <td class="px-4 py-3 align-top">
                                                <div class="py-2 font-mono text-sm" 
                                                    :class="{'text-red-600 font-bold': item.stock <= 0, 'text-green-600 font-bold': item.stock > 0}">
                                                    <span x-text="item.stock"></span> 
                                                    <span x-text="item.unit"></span>
                                                </div>
                                            </td>
                                            <td class="px-4 py-3 align-top">
                                                <input type="number" :name="`items[${index}][quantity]`" x-model="item.quantity" min="1" step="0.01" required
                                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 text-sm text-center">
                                            </td>
                                            <td class="px-4 py-3 align-top">
                                                <input type="text" :name="`items[${index}][notes]`" x-model="item.notes" 
                                                    placeholder="Keterangan..."
                                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 text-sm">
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
                            </table>
                        </div>

                        <div x-show="items.length === 0" class="text-center py-8 text-gray-500">
                            Belum ada item ditambahkan. Klik tombol "Tambah Item".
                        </div>
                    </div>
                </div>

                <!-- TOMBOL SIMPAN -->
                <div class="flex justify-end gap-4">
                    <a href="{{ route('outgoing-goods.index') }}" 
                        class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-6 py-3 rounded-lg font-medium transition">
                        Batal
                    </a>
                    <button type="submit" 
                        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium shadow-md transition transform hover:-translate-y-0.5">
                        Simpan Permintaan
                    </button>
                </div>
            </form>
        </div>

        {{-- MODAL POPUP ALERT --}}
        <div x-show="alertOpen" 
            x-cloak
            class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 backdrop-blur-sm transition-opacity"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0">
            
            <div class="bg-white rounded-xl shadow-2xl max-w-sm w-full mx-4 overflow-hidden transform transition-all scale-100"
                 @click.away="closeAlert()">
                <div class="p-6 text-center">
                    <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-yellow-100 mb-4">
                        <svg class="h-6 w-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-2">Perhatian</h3>
                    <p class="text-sm text-gray-500 mb-6" x-text="alertMessage"></p>
                    <button type="button" @click="closeAlert()"
                        class="w-full inline-flex justify-center rounded-lg border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none sm:text-sm">
                        OK, Mengerti
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection