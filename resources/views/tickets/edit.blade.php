@extends('layouts.app')

@section('content')
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="mb-6 flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Update Status Tiket</h2>
                <p class="text-sm text-gray-500 mt-1">Perbarui progress dan status perbaikan.</p>
            </div>
            <a href="{{ route('tickets.index') }}"
                class="text-gray-500 hover:text-gray-700 font-medium flex items-center transition text-sm">
                <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m7 7h18" />
                </svg>
                Batal
            </a>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-6">
            <div class="px-6 py-4 bg-gray-50 border-b border-gray-100 flex justify-between items-center">
                <div>
                    <span class="text-xs font-mono font-bold text-indigo-600 block mb-1">#{{ $ticket->ticket_code }}</span>
                    <h3 class="text-lg font-bold text-gray-900">{{ $ticket->subject }}</h3>
                </div>
                <span class="px-3 py-1 rounded-full text-xs font-bold {{ $ticket->getStatusBadgeClass() }}">
                    {{ $ticket->getStatusLabel() }}
                </span>
            </div>
            <div class="px-6 py-3 text-sm text-gray-600">
                <p><span class="font-medium text-gray-800">Pelapor:</span> {{ $ticket->customer->user->name }}</p>
                <p class="mt-1 text-gray-500">{{ $ticket->customer->address }}</p>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-blue-50">
                <h3 class="text-sm font-bold text-blue-800 uppercase tracking-wider">Form Update</h3>
            </div>

            <form action="{{ route('tickets.update', $ticket) }}" method="POST" class="p-6 space-y-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Status Pengerjaan</label>
                        <select name="status"
                            class="block w-full rounded-lg border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 py-2.5">
                            <option value="open" {{ $ticket->status == 'open' ? 'selected' : '' }}>🔴 Open</option>
                            <option value="in_progress" {{ $ticket->status == 'in_progress' ? 'selected' : '' }}>🔵 Sedang
                                Dikerjakan</option>
                            <option value="resolved" {{ $ticket->status == 'resolved' ? 'selected' : '' }}>🟢 Selesai
                                (Resolved)</option>
                            <option value="closed" {{ $ticket->status == 'closed' ? 'selected' : '' }}>⚫ Ditutup (Closed)
                            </option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Prioritas</label>
                        <select name="priority"
                            class="block w-full rounded-lg border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 py-2.5">
                            <option value="low" {{ $ticket->priority == 'low' ? 'selected' : '' }}>Low</option>
                            <option value="medium" {{ $ticket->priority == 'medium' ? 'selected' : '' }}>Medium</option>
                            <option value="high" {{ $ticket->priority == 'high' ? 'selected' : '' }}>High</option>
                            <option value="critical" {{ $ticket->priority == 'critical' ? 'selected' : '' }}>Critical
                            </option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Teknisi Penanggung Jawab</label>
                    <select name="user_id"
                        class="block w-full rounded-lg border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 py-2.5">
                        <option value="">-- Belum Ditentukan --</option>
                        @foreach ($technicians as $tech)
                            {{-- Logic Filter: Tampilkan hanya jika punya role teknisi (opsional) --}}
                            {{-- Jika Anda belum setup role, hapus kondisi if ini --}}
                            {{-- @if ($tech->hasRole('teknisi') || $tech->hasRole('admin')) --}}
                            <option value="{{ $tech->id }}" {{ $ticket->user_id == $tech->id ? 'selected' : '' }}>
                                {{ $tech->name }}
                            </option>
                            {{-- @endif --}}
                        @endforeach
                    </select>
                    <p class="text-xs text-gray-500 mt-1">Pilih teknisi yang akan menangani tiket ini.</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Catatan Progress / Kronologi</label>
                    <textarea name="description" rows="6"
                        class="block w-full rounded-lg border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 p-3">{{ $ticket->description }}</textarea>
                    <p class="text-xs text-gray-500 mt-1">*Update catatan perkembangan kasus di sini agar customer tahu.</p>
                </div>

                <div class="pt-4 border-t border-gray-100 flex justify-end gap-3">
                    <a href="{{ route('tickets.index') }}"
                        class="px-4 py-2.5 bg-white border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition">Batal</a>
                    <button type="submit"
                        class="px-6 py-2.5 bg-indigo-600 text-white font-bold rounded-lg hover:bg-indigo-700 transition shadow-md">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
