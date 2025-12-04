@extends('layouts.app')

@section('content')
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                <h2 class="text-2xl font-bold text-gray-900">Detail Pengajuan</h2>
                <span
                    class="px-3 py-1 text-sm font-semibold rounded-full
                @if ($leave->status === 'pending') bg-yellow-100 text-yellow-800
                @elseif($leave->status === 'approved') bg-green-100 text-green-800
                @else bg-red-100 text-red-800 @endif">
                    @if ($leave->status === 'pending')
                        Menunggu
                    @elseif($leave->status === 'approved')
                        Disetujui
                    @else
                        Ditolak
                    @endif
                </span>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    <div class="flex border-b border-gray-200 pb-4">
                        <div class="w-1/3 text-sm font-medium text-gray-500">Nama:</div>
                        <div class="w-2/3 text-sm text-gray-900">{{ $leave->user->name }}</div>
                    </div>

                    <div class="flex border-b border-gray-200 pb-4">
                        <div class="w-1/3 text-sm font-medium text-gray-500">Tipe:</div>
                        <div class="w-2/3">
                            @if ($leave->type === 'leave')
                                <span
                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">Izin</span>
                            @elseif($leave->type === 'sick')
                                <span
                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">Sakit</span>
                            @else
                                <span
                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-purple-100 text-purple-800">Dinas
                                    Luar Kota</span>
                            @endif
                        </div>
                    </div>

                    <div class="flex border-b border-gray-200 pb-4">
                        <div class="w-1/3 text-sm font-medium text-gray-500">Tanggal Mulai:</div>
                        <div class="w-2/3 text-sm text-gray-900">{{ $leave->start_date->isoFormat('dddd, D MMMM YYYY') }}
                        </div>
                    </div>

                    <div class="flex border-b border-gray-200 pb-4">
                        <div class="w-1/3 text-sm font-medium text-gray-500">Tanggal Selesai:</div>
                        <div class="w-2/3 text-sm text-gray-900">{{ $leave->end_date->isoFormat('dddd, D MMMM YYYY') }}
                        </div>
                    </div>

                    <div class="flex border-b border-gray-200 pb-4">
                        <div class="w-1/3 text-sm font-medium text-gray-500">Durasi:</div>
                        <div class="w-2/3 text-sm text-gray-900">{{ $leave->getDaysCount() }} hari</div>
                    </div>

                    <div class="flex border-b border-gray-200 pb-4">
                        <div class="w-1/3 text-sm font-medium text-gray-500">Alasan:</div>
                        <div class="w-2/3 text-sm text-gray-900">{{ $leave->reason }}</div>
                    </div>

                    @if ($leave->attachment)
                        <div class="flex border-b border-gray-200 pb-4">
                            <div class="w-1/3 text-sm font-medium text-gray-500">Lampiran:</div>
                            <div class="w-2/3">
                                <a href="{{ Storage::url($leave->attachment) }}" target="_blank"
                                    class="inline-flex items-center px-3 py-1.5 border border-blue-300 text-sm font-medium rounded text-blue-700 bg-blue-50 hover:bg-blue-100">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    Lihat Lampiran
                                </a>
                            </div>
                        </div>
                    @endif

                    <div class="flex border-b border-gray-200 pb-4">
                        <div class="w-1/3 text-sm font-medium text-gray-500">Diajukan Pada:</div>
                        <div class="w-2/3 text-sm text-gray-900">
                            {{ $leave->created_at->isoFormat('dddd, D MMMM YYYY HH:mm') }}</div>
                    </div>

                    @if ($leave->status !== 'pending')
                        <div class="pt-4 border-t-2 border-gray-300">
                            <div class="flex border-b border-gray-200 pb-4">
                                <div class="w-1/3 text-sm font-medium text-gray-500">Diproses Oleh:</div>
                                <div class="w-2/3 text-sm text-gray-900">{{ $leave->approver->name ?? '-' }}</div>
                            </div>

                            <div class="flex border-b border-gray-200 pb-4 mt-4">
                                <div class="w-1/3 text-sm font-medium text-gray-500">Diproses Pada:</div>
                                <div class="w-2/3 text-sm text-gray-900">
                                    {{ $leave->approved_at ? $leave->approved_at->isoFormat('dddd, D MMMM YYYY HH:mm') : '-' }}
                                </div>
                            </div>

                            @if ($leave->approval_notes)
                                <div class="flex mt-4">
                                    <div class="w-1/3 text-sm font-medium text-gray-500">Catatan:</div>
                                    <div class="w-2/3">
                                        <div
                                            class="p-4 rounded-md {{ $leave->status === 'approved' ? 'bg-green-50 border border-green-200' : 'bg-red-50 border border-red-200' }}">
                                            <p
                                                class="text-sm {{ $leave->status === 'approved' ? 'text-green-700' : 'text-red-700' }}">
                                                {{ $leave->approval_notes }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endif

                    @if (auth()->user()->hasRole('admin') && $leave->status === 'pending')
                        <div class="pt-6 border-t-2 border-gray-300">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Proses Pengajuan</h3>

                            <form action="{{ route('leave.approve', $leave) }}" method="POST">
                                @csrf

                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Keputusan</label>
                                    <select name="status"
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                        required>
                                        <option value="">-- Pilih --</option>
                                        <option value="approved">Setujui</option>
                                        <option value="rejected">Tolak</option>
                                    </select>
                                </div>

                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Catatan (Opsional)</label>
                                    <textarea name="approval_notes" rows="3"
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                        placeholder="Tambahkan catatan jika diperlukan..."></textarea>
                                </div>

                                <button type="submit"
                                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Proses
                                </button>
                            </form>
                        </div>
                    @endif
                </div>
            </div>
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                <a href="{{ route('leave.index') }}"
                    class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Kembali
                </a>
            </div>
        </div>
    </div>
@endsection
