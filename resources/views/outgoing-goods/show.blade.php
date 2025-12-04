@extends('layouts.app')

@section('title', 'Detail Barang Keluar')

@section('content')
    <div class="bg-white rounded-lg shadow-md">
        <div class="p-6 border-b border-gray-200">
            <div class="flex justify-between items-center">
                <h2 class="text-2xl font-bold text-gray-800">Detail Barang Keluar</h2>
                <div class="flex space-x-2">
                    @if ($outgoingGood->status == 'pending')
                        <form action="{{ route('outgoing-goods.approve', $outgoingGood) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700"
                                onclick="return confirm('Approve transaksi ini?')">
                                <i class="fas fa-check mr-2"></i>Approve
                            </button>
                        </form>

                        <form action="{{ route('outgoing-goods.reject', $outgoingGood) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700"
                                onclick="return confirm('Reject transaksi ini?')">
                                <i class="fas fa-times mr-2"></i>Reject
                            </button>
                        </form>
                    @endif

                    <a href="{{ route('outgoing-goods.index') }}"
                        class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700">
                        <i class="fas fa-arrow-left mr-2"></i>Kembali
                    </a>
                </div>
            </div>
            @if (session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    {{ session('error') }}
                </div>
            @endif

            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif
        </div>

        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h3 class="font-semibold text-gray-700 mb-3">Informasi Transaksi</h3>
                    <dl class="space-y-2">
                        <div class="flex justify-between">
                            <dt class="text-gray-600">Kode Transaksi:</dt>
                            <dd class="font-semibold">{{ $outgoingGood->transaction_code }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-gray-600">Tanggal:</dt>
                            <dd class="font-semibold">{{ $outgoingGood->transaction_date->format('d/m/Y') }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-gray-600">Warehouse:</dt>
                            <dd class="font-semibold">{{ $outgoingGood->warehouse->name }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-gray-600">Status:</dt>
                            <dd>
                                @if ($outgoingGood->status == 'pending')
                                    <span
                                        class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">Pending</span>
                                @elseif($outgoingGood->status == 'approved')
                                    <span
                                        class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Approved</span>
                                @else
                                    <span
                                        class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Rejected</span>
                                @endif
                            </dd>
                        </div>
                    </dl>
                </div>

                <div class="bg-gray-50 p-4 rounded-lg">
                    <h3 class="font-semibold text-gray-700 mb-3">Informasi Penerima</h3>
                    <dl class="space-y-2">
                        <div class="flex justify-between">
                            <dt class="text-gray-600">Nama Penerima:</dt>
                            <dd class="font-semibold">{{ $outgoingGood->recipient_name }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-gray-600">Departemen:</dt>
                            <dd class="font-semibold">{{ $outgoingGood->department ?? '-' }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-gray-600">Keperluan:</dt>
                            <dd class="font-semibold">{{ $outgoingGood->purpose ?? '-' }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-gray-600">Dibuat Oleh:</dt>
                            <dd class="font-semibold">{{ $outgoingGood->user->name }}</dd>
                        </div>
                        @if ($outgoingGood->approved_by)
                            <div class="flex justify-between">
                                <dt class="text-gray-600">Diproses Oleh:</dt>
                                <dd class="font-semibold">{{ $outgoingGood->approver->name }}</dd>
                            </div>
                        @endif
                    </dl>
                </div>
            </div>

            @if ($outgoingGood->notes)
                <div class="bg-blue-50 p-4 rounded-lg mb-6">
                    <h3 class="font-semibold text-gray-700 mb-2">Catatan</h3>
                    <p class="text-gray-700">{{ $outgoingGood->notes }}</p>
                </div>
            @endif

            <div class="border-t pt-6">
                <h3 class="text-lg font-semibold mb-4">Detail Item</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Item</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Quantity</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Catatan</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($outgoingGood->details as $detail)
                                <tr>
                                    <td class="px-6 py-4 text-sm text-gray-900">
                                        {{ $detail->item->code }} - {{ $detail->item->name }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ number_format($detail->quantity, 2) }} {{ $detail->item->unit }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-900">
                                        {{ $detail->notes ?? '-' }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
