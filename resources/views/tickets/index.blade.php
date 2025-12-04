@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Tiket & Laporan Gangguan</h2>
                <p class="mt-1 text-sm text-gray-500">Monitor keluhan pelanggan dan status perbaikan.</p>
            </div>

            {{-- TAMPILKAN TOMBOL BUAT SEMUA ROLE YANG BERHAK INPUT (Admin/CS/Korlap) --}}
            {{-- Atau tampilkan untuk semua user (jika customer boleh lapor sendiri juga) --}}
            <a href="{{ route('tickets.create') }}" class="inline-flex items-center px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg shadow-sm transition">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Input Tiket Baru
            </a>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 mb-6">
            <form method="GET" action="{{ route('tickets.index') }}" class="grid grid-cols-1 md:grid-cols-12 gap-4">
                <div class="md:col-span-5 relative">
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Cari No Tiket, Subjek, atau Pelanggan..."
                        class="pl-10 block w-full border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm py-2.5">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                </div>

                <div class="md:col-span-3">
                    <select name="status"
                        class="block w-full border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm py-2.5">
                        <option value="">Semua Status</option>
                        <option value="open" {{ request('status') == 'open' ? 'selected' : '' }}>Open</option>
                        <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>In Progress
                        </option>
                        <option value="resolved" {{ request('status') == 'resolved' ? 'selected' : '' }}>Resolved</option>
                        <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>Closed</option>
                    </select>
                </div>

                <div class="md:col-span-2">
                    <select name="priority"
                        class="block w-full border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm py-2.5">
                        <option value="">Semua Prioritas</option>
                        <option value="critical" {{ request('priority') == 'critical' ? 'selected' : '' }}>Critical</option>
                        <option value="high" {{ request('priority') == 'high' ? 'selected' : '' }}>High</option>
                        <option value="medium" {{ request('priority') == 'medium' ? 'selected' : '' }}>Medium</option>
                        <option value="low" {{ request('priority') == 'low' ? 'selected' : '' }}>Low</option>
                    </select>
                </div>

                <div class="md:col-span-2">
                    <button type="submit"
                        class="w-full bg-gray-800 hover:bg-gray-900 text-white font-medium py-2.5 rounded-lg transition">Filter</button>
                </div>
            </form>
        </div>

        <div class="hidden lg:block bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tiket
                            Info</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pelanggan
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Prioritas
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Teknisi
                        </th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($tickets as $ticket)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4">
                                <div class="flex flex-col">
                                    <span
                                        class="text-sm font-bold text-indigo-600 font-mono">#{{ $ticket->ticket_code }}</span>
                                    <span
                                        class="text-sm font-medium text-gray-900 line-clamp-1">{{ $ticket->subject }}</span>
                                    <span class="text-xs text-gray-500">{{ $ticket->created_at->diffForHumans() }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">
                                    {{ $ticket->customer->user->name ?? 'Unknown' }}</div>
                                <div class="text-xs text-gray-500">{{ $ticket->customer->coverageArea->name ?? '-' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span
                                    class="px-2.5 py-0.5 rounded-full text-xs font-medium border {{ $ticket->getPriorityBadgeClass() }}">
                                    {{ ucfirst($ticket->priority) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span
                                    class="px-2.5 py-0.5 rounded-full text-xs font-medium {{ $ticket->getStatusBadgeClass() }}">
                                    {{ $ticket->getStatusLabel() }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if ($ticket->user)
                                    <div class="flex items-center">
                                        <div
                                            class="h-6 w-6 rounded-full bg-gray-200 flex items-center justify-center text-xs font-bold text-gray-600 mr-2">
                                            {{ substr($ticket->user->name, 0, 1) }}
                                        </div>
                                        <span class="text-sm text-gray-700">{{ $ticket->user->name }}</span>
                                    </div>
                                @else
                                    <span class="text-xs text-gray-400 italic">Belum assign</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="{{ route('tickets.show', $ticket) }}"
                                    class="text-blue-600 hover:text-blue-900 font-bold {{ !auth()->user()->customer ? 'mr-3' : '' }}">Detail</a>

                                {{-- HANYA TAMPIL JIKA USER ADALAH ADMIN/TEKNISI --}}
                                @if (!auth()->user()->customer)
                                    <a href="{{ route('tickets.edit', $ticket) }}"
                                        class="text-yellow-600 hover:text-yellow-900">Update</a>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-10 text-center text-gray-500">Tidak ada data tiket ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="lg:hidden space-y-4">
            @forelse($tickets as $ticket)
                <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-200">
                    <div class="flex justify-between items-start mb-3">
                        <div>
                            <span
                                class="text-xs font-mono font-bold text-indigo-600 block">#{{ $ticket->ticket_code }}</span>
                            <h3 class="text-sm font-bold text-gray-900">{{ $ticket->subject }}</h3>
                        </div>
                        <span class="px-2 py-1 rounded-md text-xs font-bold {{ $ticket->getStatusBadgeClass() }}">
                            {{ ucfirst($ticket->status) }}
                        </span>
                    </div>

                    <div class="flex justify-between items-center text-xs text-gray-500 mb-3">
                        <span>{{ $ticket->customer->user->name ?? 'Unknown' }}</span>
                        <span>{{ $ticket->created_at->format('d M H:i') }}</span>
                    </div>

                    <div class="flex justify-between items-center border-t pt-3">
                        <span
                            class="px-2 py-1 rounded text-xs border {{ $ticket->getPriorityBadgeClass() }}">{{ ucfirst($ticket->priority) }}</span>
                        <div class="flex gap-3">
                            <a href="{{ route('tickets.show', $ticket) }}"
                                class="text-blue-600 text-sm font-bold">Detail</a>

                            {{-- HANYA TAMPIL JIKA USER ADALAH ADMIN/TEKNISI (MOBILE) --}}
                            @if (!auth()->user()->customer)
                                <a href="{{ route('tickets.edit', $ticket) }}"
                                    class="text-yellow-600 text-sm font-medium">Update</a>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-10 text-gray-500">Tidak ada data tiket.</div>
            @endforelse
        </div>

        <div class="mt-6">
            {{ $tickets->withQueryString()->links() }}
        </div>
    </div>
@endsection
