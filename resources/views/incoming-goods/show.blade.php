@extends('layouts.app')

@section('title', 'Detail Barang Masuk')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-5xl mx-auto">
            
            <!-- HEADER -->
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
                <div class="flex items-center">
                    <a href="{{ route('incoming-goods.index') }}" class="text-gray-500 hover:text-gray-700 mr-4 transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                    </a>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-800">Detail Transaksi</h1>
                        <p class="text-sm text-gray-500 mt-1">Kode: <span class="font-mono font-bold text-blue-600">{{ $incomingGood->transaction_code }}</span></p>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    @php
                        $statusClass = match ($incomingGood->status) {
                            'approved' => 'bg-green-100 text-green-800 border-green-200',
                            'rejected' => 'bg-red-100 text-red-800 border-red-200',
                            default => 'bg-yellow-100 text-yellow-800 border-yellow-200',
                        };
                        $statusIcon = match ($incomingGood->status) {
                            'approved' => 'fa-check-circle',
                            'rejected' => 'fa-times-circle',
                            default => 'fa-clock',
                        };
                    @endphp
                    <span class="px-4 py-2 rounded-full text-sm font-bold border flex items-center gap-2 {{ $statusClass }}">
                        <i class="fas {{ $statusIcon }}"></i>
                        {{ ucfirst($incomingGood->status) }}
                    </span>
                </div>
            </div>

            <!-- CARD INFO UTAMA -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-6">
                <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                    <h2 class="font-semibold text-gray-800">Informasi Umum</h2>
                </div>
                <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Gudang Tujuan</label>
                        <p class="text-gray-800 font-medium text-lg">{{ $incomingGood->warehouse->name }}</p>
                        <p class="text-gray-500 text-sm">{{ $incomingGood->warehouse->address ?? '-' }}</p>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Supplier</label>
                        <p class="text-gray-800 font-medium text-lg">{{ $incomingGood->supplier->name }}</p>
                        <p class="text-gray-500 text-sm">{{ $incomingGood->supplier->phone ?? '-' }}</p>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Tanggal Transaksi</label>
                        <p class="text-gray-800">{{ $incomingGood->transaction_date->format('d F Y') }}</p>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">No. Invoice / SJ</label>
                        <p class="text-gray-800 font-mono">{{ $incomingGood->invoice_number ?? '-' }}</p>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Catatan</label>
                        <p class="text-gray-800 italic bg-gray-50 p-3 rounded border border-gray-100">
                            "{{ $incomingGood->notes ?? 'Tidak ada catatan' }}"
                        </p>
                    </div>
                    <div class="md:col-span-2 border-t pt-4 flex justify-between text-sm text-gray-500">
                        <span>Dibuat oleh: <strong>{{ $incomingGood->user->name }}</strong></span>
                        @if($incomingGood->approved_by)
                            <span>
                                {{ $incomingGood->status == 'approved' ? 'Disetujui' : 'Ditolak' }} oleh: 
                                <strong>{{ $incomingGood->approver->name }}</strong>
                                pada {{ $incomingGood->approved_at->format('d M Y H:i') }}
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- CARD DETAIL ITEM -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                    <h2 class="font-semibold text-gray-800">Rincian Barang</h2>
                </div>
                <div class="p-6">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Barang</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Qty</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Harga Satuan</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($incomingGood->details as $detail)
                                    <tr>
                                        <td class="px-6 py-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $detail->item->name }}</div>
                                            <div class="text-xs text-gray-500">Kode: {{ $detail->item->code }}</div>
                                            @if($detail->notes)
                                                <div class="text-xs text-gray-400 italic mt-1">Catatan: {{ $detail->notes }}</div>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-center text-sm text-gray-900">
                                            {{ number_format($detail->quantity, 0, ',', '.') }}
                                        </td>
                                        <td class="px-6 py-4 text-right text-sm text-gray-900">
                                            Rp {{ number_format($detail->price, 0, ',', '.') }}
                                        </td>
                                        <td class="px-6 py-4 text-right text-sm font-bold text-gray-900">
                                            Rp {{ number_format($detail->subtotal, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="bg-blue-50">
                                <tr>
                                    <td colspan="3" class="px-6 py-4 text-right font-bold text-gray-700 uppercase">Total Nilai Transaksi</td>
                                    <td class="px-6 py-4 text-right font-bold text-blue-700 text-lg">
                                        Rp {{ number_format($incomingGood->total_amount, 0, ',', '.') }}
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            <!-- ACTION BUTTONS (Only Pending) -->
            @if($incomingGood->status == 'pending')
                <div class="mt-8 flex justify-end gap-4">
                    {{-- Form Tolak --}}
                    <form action="{{ route('incoming-goods.reject', $incomingGood) }}" method="POST" id="form-reject">
                        @csrf
                        {{-- Button type="button" untuk mencegah submit langsung, panggil modal dulu --}}
                        <button type="button" onclick="openConfirmationModal('reject', 'form-reject')"
                            class="bg-red-600 hover:bg-red-700 text-white px-6 py-3 rounded-lg font-bold shadow-md transition transform hover:-translate-y-0.5 flex items-center gap-2">
                            <i class="fas fa-times"></i> Tolak
                        </button>
                    </form>

                    {{-- Form Setuju --}}
                    <form action="{{ route('incoming-goods.approve', $incomingGood) }}" method="POST" id="form-approve">
                        @csrf
                        {{-- Button type="button" untuk mencegah submit langsung, panggil modal dulu --}}
                        <button type="button" onclick="openConfirmationModal('approve', 'form-approve')"
                            class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg font-bold shadow-md transition transform hover:-translate-y-0.5 flex items-center gap-2">
                            <i class="fas fa-check"></i> Setujui & Tambah Stok
                        </button>
                    </form>
                </div>
            @endif

        </div>
    </div>

    <!-- MODAL KONFIRMASI (Baru) -->
    <div id="confirmationModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4 backdrop-blur-sm transition-opacity duration-300">
        <div class="bg-white rounded-xl shadow-2xl max-w-md w-full overflow-hidden transform transition-all scale-100">
            <div class="p-6">
                <!-- Icon -->
                <div class="flex items-center justify-center w-16 h-16 mx-auto mb-5 rounded-full transition-colors duration-300" id="modalIconBg">
                    <svg class="w-8 h-8 transition-colors duration-300" id="modalIcon" fill="none" stroke="currentColor" viewBox="0 0 24 24"></svg>
                </div>
                
                <!-- Content -->
                <h3 class="text-xl font-bold text-center text-gray-900 mb-2" id="modalTitle"></h3>
                <p class="text-sm text-center text-gray-600 mb-8 leading-relaxed" id="modalMessage"></p>
                
                <!-- Buttons -->
                <div class="flex justify-center gap-3">
                    <button type="button" onclick="closeConfirmationModal()" 
                        class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg font-medium transition duration-200">
                        Batal
                    </button>
                    <button type="button" id="modalConfirmBtn"
                        class="px-5 py-2.5 text-white rounded-lg font-bold shadow-md transition duration-200 transform hover:-translate-y-0.5 flex items-center">
                        Ya, Lanjutkan
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Variabel untuk menyimpan ID form yang akan di-submit
        let currentFormId = null;

        function openConfirmationModal(type, formId) {
            currentFormId = formId;
            const modal = document.getElementById('confirmationModal');
            const title = document.getElementById('modalTitle');
            const message = document.getElementById('modalMessage');
            const confirmBtn = document.getElementById('modalConfirmBtn');
            const iconBg = document.getElementById('modalIconBg');
            const icon = document.getElementById('modalIcon');

            // Reset kelas
            confirmBtn.className = 'px-5 py-2.5 text-white rounded-lg font-bold shadow-md transition duration-200 transform hover:-translate-y-0.5 flex items-center';
            iconBg.className = 'flex items-center justify-center w-16 h-16 mx-auto mb-5 rounded-full';
            icon.className = 'w-8 h-8';

            if (type === 'approve') {
                title.textContent = 'Setujui Transaksi?';
                message.textContent = 'Apakah Anda yakin ingin MENYETUJUI barang masuk ini? Stok gudang akan bertambah secara otomatis.';
                
                // Style Hijau
                confirmBtn.classList.add('bg-green-600', 'hover:bg-green-700');
                confirmBtn.innerHTML = 'Ya, Setujui';
                iconBg.classList.add('bg-green-100');
                icon.classList.add('text-green-600');
                icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>';
            } else {
                title.textContent = 'Tolak Transaksi?';
                message.textContent = 'Apakah Anda yakin ingin MENOLAK barang masuk ini? Tindakan ini tidak dapat dibatalkan.';
                
                // Style Merah
                confirmBtn.classList.add('bg-red-600', 'hover:bg-red-700');
                confirmBtn.innerHTML = 'Ya, Tolak';
                iconBg.classList.add('bg-red-100');
                icon.classList.add('text-red-600');
                icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>';
            }

            modal.classList.remove('hidden');
        }

        function closeConfirmationModal() {
            document.getElementById('confirmationModal').classList.add('hidden');
            currentFormId = null;
        }

        // Event Listener untuk tombol konfirmasi di modal
        document.getElementById('modalConfirmBtn').addEventListener('click', function() {
            if (currentFormId) {
                document.getElementById(currentFormId).submit();
            }
        });

        // Close on escape key
        document.addEventListener('keydown', function(event) {
            if (event.key === "Escape") {
                closeConfirmationModal();
            }
        });
    </script>
@endsection