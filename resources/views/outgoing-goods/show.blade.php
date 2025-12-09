@extends('layouts.app')

@section('title', 'Detail Barang Keluar')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-5xl mx-auto">
            
            <!-- HEADER -->
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
                <div class="flex items-center">
                    <a href="{{ route('outgoing-goods.index') }}" class="text-gray-500 hover:text-gray-700 mr-4 transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                    </a>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-800">Detail Permintaan Barang</h1>
                        <p class="text-sm text-gray-500 mt-1">Kode: <span class="font-mono font-bold text-blue-600">{{ $outgoingGood->transaction_code }}</span></p>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    @php
                        $statusClass = match ($outgoingGood->status) {
                            'approved' => 'bg-green-100 text-green-800 border-green-200',
                            'rejected' => 'bg-red-100 text-red-800 border-red-200',
                            default => 'bg-yellow-100 text-yellow-800 border-yellow-200',
                        };
                        $statusIcon = match ($outgoingGood->status) {
                            'approved' => 'fa-check-circle',
                            'rejected' => 'fa-times-circle',
                            default => 'fa-clock',
                        };
                    @endphp
                    <span class="px-4 py-2 rounded-full text-sm font-bold border flex items-center gap-2 {{ $statusClass }}">
                        <i class="fas {{ $statusIcon }}"></i>
                        {{ ucfirst($outgoingGood->status) }}
                    </span>
                </div>
            </div>

            <!-- CARD INFO UTAMA -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-6">
                <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                    <h2 class="font-semibold text-gray-800">Informasi Pengiriman</h2>
                </div>
                <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Gudang Asal (Source)</label>
                        <p class="text-gray-800 font-medium text-lg">{{ $outgoingGood->warehouse->name }}</p>
                        <p class="text-gray-500 text-sm">{{ $outgoingGood->warehouse->address ?? '-' }}</p>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Penerima</label>
                        <p class="text-gray-800 font-medium text-lg">{{ $outgoingGood->recipient_name }}</p>
                        <p class="text-gray-500 text-sm">{{ $outgoingGood->department ?? '-' }}</p>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Tanggal Keluar</label>
                        <p class="text-gray-800">{{ $outgoingGood->transaction_date->format('d F Y') }}</p>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Keperluan</label>
                        <p class="text-gray-800">{{ $outgoingGood->purpose ?? '-' }}</p>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Catatan</label>
                        <p class="text-gray-800 italic bg-gray-50 p-3 rounded border border-gray-100">
                            "{{ $outgoingGood->notes ?? 'Tidak ada catatan' }}"
                        </p>
                    </div>
                    <div class="md:col-span-2 border-t pt-4 flex justify-between text-sm text-gray-500">
                        <span>Dibuat oleh: <strong>{{ $outgoingGood->user->name }}</strong></span>
                        @if($outgoingGood->approved_by)
                            <span>
                                {{ $outgoingGood->status == 'approved' ? 'Disetujui' : 'Ditolak' }} oleh: 
                                <strong>{{ $outgoingGood->approver->name }}</strong>
                                pada {{ $outgoingGood->approved_at->format('d M Y H:i') }}
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- CARD DETAIL ITEM -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                    <h2 class="font-semibold text-gray-800">Rincian Barang Keluar</h2>
                </div>
                <div class="p-6">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Barang</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Qty</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Catatan Item</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($outgoingGood->details as $detail)
                                    <tr>
                                        <td class="px-6 py-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $detail->item->name }}</div>
                                            <div class="text-xs text-gray-500">Kode: {{ $detail->item->code }}</div>
                                        </td>
                                        <td class="px-6 py-4 text-center text-sm font-bold text-gray-900">
                                            {{ number_format($detail->quantity, 0, ',', '.') }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-600">
                                            {{ $detail->notes ?? '-' }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- ACTION BUTTONS -->
            @if($outgoingGood->status == 'pending')
                <div class="mt-8 flex justify-end gap-4">
                    {{-- Form Tolak --}}
                    <form action="{{ route('outgoing-goods.reject', $outgoingGood) }}" method="POST" id="form-reject">
                        @csrf
                        <button type="button" onclick="openConfirmationModal('reject', 'form-reject')"
                            class="bg-red-600 hover:bg-red-700 text-white px-6 py-3 rounded-lg font-bold shadow-md transition transform hover:-translate-y-0.5 flex items-center gap-2">
                            <i class="fas fa-times"></i> Tolak
                        </button>
                    </form>

                    {{-- Form Setuju --}}
                    <form action="{{ route('outgoing-goods.approve', $outgoingGood) }}" method="POST" id="form-approve">
                        @csrf
                        <button type="button" onclick="openConfirmationModal('approve', 'form-approve')"
                            class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg font-bold shadow-md transition transform hover:-translate-y-0.5 flex items-center gap-2">
                            <i class="fas fa-check"></i> Setujui & Kurangi Stok
                        </button>
                    </form>
                </div>
            @endif

        </div>
    </div>

    <!-- MODAL KONFIRMASI -->
    <div id="confirmationModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4 backdrop-blur-sm transition-opacity duration-300">
        <div class="bg-white rounded-xl shadow-2xl max-w-md w-full overflow-hidden transform transition-all scale-100">
            <div class="p-6">
                <div class="flex items-center justify-center w-16 h-16 mx-auto mb-5 rounded-full transition-colors duration-300" id="modalIconBg">
                    <svg class="w-8 h-8 transition-colors duration-300" id="modalIcon" fill="none" stroke="currentColor" viewBox="0 0 24 24"></svg>
                </div>
                <h3 class="text-xl font-bold text-center text-gray-900 mb-2" id="modalTitle"></h3>
                <p class="text-sm text-center text-gray-600 mb-8 leading-relaxed" id="modalMessage"></p>
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
        let currentFormId = null;

        function openConfirmationModal(type, formId) {
            currentFormId = formId;
            const modal = document.getElementById('confirmationModal');
            const title = document.getElementById('modalTitle');
            const message = document.getElementById('modalMessage');
            const confirmBtn = document.getElementById('modalConfirmBtn');
            const iconBg = document.getElementById('modalIconBg');
            const icon = document.getElementById('modalIcon');

            confirmBtn.className = 'px-5 py-2.5 text-white rounded-lg font-bold shadow-md transition duration-200 transform hover:-translate-y-0.5 flex items-center';
            iconBg.className = 'flex items-center justify-center w-16 h-16 mx-auto mb-5 rounded-full';
            icon.className = 'w-8 h-8';

            if (type === 'approve') {
                title.textContent = 'Setujui Permintaan?';
                message.textContent = 'Stok barang di gudang akan otomatis BERKURANG sesuai jumlah permintaan.';
                confirmBtn.classList.add('bg-green-600', 'hover:bg-green-700');
                confirmBtn.innerHTML = 'Ya, Setujui';
                iconBg.classList.add('bg-green-100');
                icon.classList.add('text-green-600');
                icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>';
            } else {
                title.textContent = 'Tolak Permintaan?';
                message.textContent = 'Permintaan akan ditolak dan stok gudang TIDAK akan berubah.';
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

        document.getElementById('modalConfirmBtn').addEventListener('click', function() {
            if (currentFormId) {
                document.getElementById(currentFormId).submit();
            }
        });

        document.addEventListener('keydown', function(event) {
            if (event.key === "Escape") {
                closeConfirmationModal();
            }
        });
    </script>
@endsection