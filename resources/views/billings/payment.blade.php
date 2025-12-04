@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-3xl mx-auto">
            <div class="flex items-center justify-between mb-6">
                <a href="{{ route('billings.index') }}" class="text-gray-500 hover:text-gray-700 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Kembali
                </a>
                <h1 class="text-2xl font-bold text-gray-800">Pembayaran Tagihan</h1>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="md:col-span-2 space-y-6">
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="bg-blue-50 px-6 py-4 border-b border-blue-100 flex justify-between items-center">
                            <span class="text-blue-800 font-semibold">Tagihan Saat Ini</span>
                            <span class="font-mono font-bold text-blue-600">{{ $billing->billing_code }}</span>
                        </div>
                        <div class="p-6">
                            <div class="mb-4">
                                <label class="text-xs text-gray-500 uppercase tracking-wide">Pelanggan</label>
                                <div class="font-bold text-lg text-gray-800">{{ optional($billing->customer->user)->name }}
                                </div>
                                <div class="text-sm text-gray-600">{{ optional($billing->customer)->company_name }}</div>
                            </div>
                            <div class="grid grid-cols-2 gap-4 mb-4">
                                <div>
                                    <label class="text-xs text-gray-500 uppercase tracking-wide">Paket</label>
                                    <div class="font-semibold text-gray-800">{{ optional($billing->package)->name }}</div>
                                </div>
                                <div>
                                    <label class="text-xs text-gray-500 uppercase tracking-wide">Harga Paket</label>
                                    <div class="font-semibold text-gray-800">Rp
                                        {{ number_format($billing->amount, 0, ',', '.') }}</div>
                                </div>
                            </div>
                            <div>
                                <label class="text-xs text-gray-500 uppercase tracking-wide">Periode Tagihan Utama</label>
                                <div class="text-gray-800">
                                    {{ $billing->start_date ? $billing->start_date->format('d M Y') : '-' }} s/d
                                    {{ $billing->end_date ? $billing->end_date->format('d M Y') : '-' }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="md:col-span-1">
                    <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6 sticky top-6">
                        <h3 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2">Input Pembayaran</h3>

                        <form action="{{ route('billings.process-payment', $billing) }}" method="POST" id="paymentForm">
                            @csrf

                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Bayar Berapa Bulan?</label>
                                <select name="months" id="months"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 bg-gray-50 font-medium"
                                    onchange="calculateTotal()">
                                    @for ($i = 1; $i <= 12; $i++)
                                        <option value="{{ $i }}">{{ $i }} Bulan</option>
                                    @endfor
                                </select>
                            </div>

                            <div class="bg-blue-50 rounded-lg p-4 mb-4 border border-blue-100">
                                <div class="flex justify-between text-sm text-blue-800 mb-1">
                                    <span>Harga Satuan:</span>
                                    <span>Rp {{ number_format($billing->amount, 0, ',', '.') }}</span>
                                </div>
                                <div class="flex justify-between items-center border-t border-blue-200 pt-2 mt-2">
                                    <span class="font-bold text-blue-900">Total Bayar:</span>
                                    <span class="font-bold text-xl text-blue-600" id="displayTotal">Rp
                                        {{ number_format($billing->amount, 0, ',', '.') }}</span>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Metode</label>
                                <select name="payment_method" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                                    <option value="cash">Tunai (Cash)</option>
                                    <option value="transfer_bca">Transfer BCA</option>
                                    <option value="transfer_bri">Transfer BRI</option>
                                    <option value="transfer_mandiri">Transfer Mandiri</option>
                                    <option value="qris">QRIS</option>
                                </select>
                            </div>

                            <div class="mb-6">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Catatan</label>
                                <textarea name="notes" rows="2" class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm"
                                    placeholder="Opsional..."></textarea>
                            </div>

                            <button type="submit"
                                onclick="return confirm('Yakin proses pembayaran? Total yang harus diterima sesuai yang tertera.')"
                                class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-3 rounded-lg shadow transition transform hover:-translate-y-0.5">
                                PROSES BAYAR
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const amountPerMonth = {{ $billing->amount }};

        function calculateTotal() {
            const months = document.getElementById('months').value;
            const total = amountPerMonth * months;

            // Format Rupiah via JS
            const formatted = new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0
            }).format(total);

            document.getElementById('displayTotal').innerText = formatted;
        }
    </script>
@endsection
