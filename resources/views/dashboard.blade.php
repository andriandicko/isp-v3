@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        {{-- Header & Date --}}
        <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Dashboard Overview</h1>
                <p class="text-sm text-gray-500 mt-1">
                    Halo, <span class="font-semibold text-blue-600">{{ auth()->user()->name }}</span>! 
                    Berikut ringkasan operasional Anda.
                </p>
            </div>
            <div class="flex items-center">
                <span class="bg-white border border-gray-200 px-4 py-2 rounded-lg text-sm font-medium text-gray-600 shadow-sm flex items-center">
                    <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    {{ \Carbon\Carbon::now()->isoFormat('dddd, D MMMM Y') }}
                </span>
            </div>
        </div>

        {{-- GRID WIDGETS UTAMA --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-6 mb-8">

            {{-- 1. WIDGET PELANGGAN --}}
            @can('customers.index')
            <div class="bg-white rounded-xl p-5 border border-gray-200 shadow-sm hover:shadow-md transition flex flex-col justify-between h-full relative overflow-hidden group">
                <div class="absolute top-0 right-0 w-16 h-16 bg-purple-50 rounded-bl-full -mr-3 -mt-3 z-0 group-hover:bg-purple-100 transition"></div>
                <div class="relative z-10">
                    <div class="flex justify-between items-center mb-4">
                        <p class="text-xs font-bold text-gray-500 uppercase tracking-wider flex items-center">
                            <svg class="w-4 h-4 mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                            Pelanggan
                        </p>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="border-r border-gray-100">
                            <p class="text-[10px] font-bold text-purple-600 uppercase mb-1">Total Aktif</p>
                            <h3 class="text-3xl font-bold text-gray-900">{{ $stats['customers'] ?? 0 }}</h3>
                        </div>
                        <div class="pl-2">
                            <p class="text-[10px] font-bold text-green-600 uppercase mb-1">✨ Baru (Bln Ini)</p>
                            <h3 class="text-3xl font-bold text-gray-900">{{ $stats['new_customers'] ?? 0 }}</h3>
                        </div>
                    </div>
                </div>
                
                @php
                    $growth = $stats['growth_percentage'] ?? 0;
                    $growthColor = $growth > 0 ? 'text-green-600' : ($growth < 0 ? 'text-red-600' : 'text-gray-500');
                    $growthSign = $growth > 0 ? '+' : ''; 
                @endphp
                <div class="mt-4 pt-3 border-t border-gray-100 flex justify-between items-center">
                    <span class="text-xs font-medium text-gray-500">
                        Pertumbuhan: 
                        <span class="{{ $growthColor }} font-bold">{{ $growthSign }}{{ $growth }}%</span>
                    </span>
                    <a href="{{ route('customers.index') }}" class="text-xs font-bold text-indigo-600 hover:underline">Data Pelanggan →</a>
                </div>
            </div>
            @endcan

            {{-- 2. WIDGET TIKET SUPPORT --}}
            @can('tickets.index')
            <div class="bg-white rounded-xl p-5 border border-gray-200 shadow-sm hover:shadow-md transition flex flex-col justify-between h-full relative overflow-hidden group">
                <div class="absolute top-0 right-0 w-16 h-16 bg-red-50 rounded-bl-full -mr-3 -mt-3 z-0 group-hover:bg-red-100 transition"></div>
                <div class="relative z-10">
                    <div class="flex justify-between items-center mb-4">
                        <p class="text-xs font-bold text-gray-500 uppercase tracking-wider flex items-center">
                            <svg class="w-4 h-4 mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636a9 9 0 010 12.728m0 0l-2.829-2.829m2.829 2.829L21 21M15.536 8.464a5 5 0 010 7.072m0 0l-2.829-2.829m-4.243 2.829a4.978 4.978 0 01-1.414-2.83m-1.414 5.658a9 9 0 01-2.167-9.238m7.824 2.167a1 1 0 111.414 1.414m-1.414-1.414L3 3m8.293 8.293l1.414 1.414"></path></svg>
                            Support
                        </p>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="border-r border-gray-100">
                            <p class="text-[10px] font-bold text-red-500 uppercase mb-1">🔥 Open</p>
                            <h3 class="text-3xl font-bold text-gray-900">{{ $ticketStats['open'] }}</h3>
                        </div>
                        <div class="pl-2">
                            <p class="text-[10px] font-bold text-blue-600 uppercase mb-1">🛠️ Proses</p>
                            <h3 class="text-3xl font-bold text-gray-900">{{ $ticketStats['process'] }}</h3>
                        </div>
                    </div>
                </div>
                <div class="mt-4 pt-3 border-t border-gray-100 flex justify-between items-center">
                    <span class="text-xs font-medium text-gray-500">+{{ $ticketStats['today'] }} Baru Hari Ini</span>
                    <a href="{{ route('tickets.index') }}" class="text-xs font-bold text-indigo-600 hover:underline">Helpdesk →</a>
                </div>
            </div>
            @endcan

            {{-- 3. WIDGET BILLING --}}
            @can('billings.index')
            <div class="bg-white rounded-xl p-5 border border-gray-200 shadow-sm hover:shadow-md transition flex flex-col justify-between h-full relative overflow-hidden group">
                <div class="absolute top-0 right-0 w-16 h-16 bg-green-50 rounded-bl-full -mr-3 -mt-3 z-0 group-hover:bg-green-100 transition"></div>
                <div class="relative z-10">
                    <div class="flex justify-between items-center mb-4">
                        <p class="text-xs font-bold text-gray-500 uppercase tracking-wider flex items-center">
                            <svg class="w-4 h-4 mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Billing Status
                        </p>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="border-r border-gray-100">
                            <p class="text-[10px] font-bold text-orange-500 uppercase mb-1">⏳ Pending</p>
                            <h3 class="text-3xl font-bold text-gray-900">{{ $stats['pending_bills'] }}</h3>
                        </div>
                        <div class="pl-2">
                            <p class="text-[10px] font-bold text-red-600 uppercase mb-1">⚠️ Overdue</p>
                            <h3 class="text-3xl font-bold text-gray-900">{{ $stats['overdue_bills'] }}</h3>
                        </div>
                    </div>
                </div>
                <div class="mt-4 pt-3 border-t border-gray-100 flex justify-between items-center">
                    <span class="text-xs font-medium text-gray-500">Cek tagihan macet</span>
                    <a href="{{ route('billings.index') }}" class="text-xs font-bold text-indigo-600 hover:underline">Keuangan →</a>
                </div>
            </div>
            @endcan

            {{-- 4. WIDGET LOGISTIK --}}
            @can('stock-report.index')
            <div class="bg-white rounded-xl p-5 border border-gray-200 shadow-sm hover:shadow-md transition flex flex-col justify-between h-full relative overflow-hidden group">
                <div class="absolute top-0 right-0 w-16 h-16 bg-orange-50 rounded-bl-full -mr-3 -mt-3 z-0 group-hover:bg-orange-100 transition"></div>
                <div class="relative z-10">
                    <div class="flex justify-between items-center mb-4">
                        <p class="text-xs font-bold text-gray-500 uppercase tracking-wider flex items-center">
                            <svg class="w-4 h-4 mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                            Logistik
                        </p>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="border-r border-gray-100">
                            <p class="text-[10px] font-bold text-orange-600 uppercase mb-1">📦 Stok Tipis</p>
                            <h3 class="text-3xl font-bold text-gray-900">{{ $logisticStats['low_stock_count'] }}</h3>
                        </div>
                        
                        <div class="pl-2 flex flex-col justify-center space-y-1">
                            <div class="flex justify-between items-center">
                                <p class="text-[9px] font-bold text-blue-600 uppercase">📥 Masuk</p>
                                <span class="text-sm font-bold text-gray-900">{{ $logisticStats['pending_incoming'] }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <p class="text-[9px] font-bold text-orange-600 uppercase">📤 Keluar</p>
                                <span class="text-sm font-bold text-gray-900">{{ $logisticStats['pending_outgoing'] }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mt-4 pt-3 border-t border-gray-100 flex justify-between items-center">
                    <span class="text-xs font-medium text-gray-500">
                        @if ($logisticStats['pending_incoming'] + $logisticStats['pending_outgoing'] > 0)
                            Menunggu ACC
                        @else
                            Semua Aman
                        @endif
                    </span>
                    <a href="{{ route('stock-report.minimum-stock') }}" class="text-xs font-bold text-indigo-600 hover:underline">Gudang →</a>
                </div>
            </div>
            @endcan

            {{-- 5. WIDGET KEHADIRAN --}}
            {{-- REVISI FINAL: Hapus pengecekan @unlessrole agar widget ini TAMPIL untuk semua user yang punya izin absensi --}}
            @can('attendance.index')
            <div class="bg-white rounded-xl p-5 border border-gray-200 shadow-sm hover:shadow-md transition flex flex-col justify-between h-full relative overflow-hidden group">
                <div class="absolute top-0 right-0 w-16 h-16 bg-blue-50 rounded-bl-full -mr-3 -mt-3 z-0 group-hover:bg-blue-100 transition"></div>
                <div class="relative z-10">
                    <div class="flex justify-between items-center mb-4">
                        <p class="text-xs font-bold text-gray-500 uppercase tracking-wider flex items-center">
                            <svg class="w-4 h-4 mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Kehadiran Hari Ini
                        </p>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="border-r border-gray-100">
                            <p class="text-[10px] font-bold text-green-600 uppercase mb-1">✅ Hadir</p>
                            <h3 class="text-3xl font-bold text-gray-900">{{ $attendanceStats['present'] }}</h3>
                        </div>
                        <div class="pl-2">
                            <p class="text-[10px] font-bold text-yellow-600 uppercase mb-1">⚠️ Telat/Izin</p>
                            <h3 class="text-3xl font-bold text-gray-900">
                                {{ $attendanceStats['late'] + $attendanceStats['leave'] }}
                            </h3>
                        </div>
                    </div>
                </div>
                <div class="mt-4 pt-3 border-t border-gray-100 flex justify-between items-center">
                    <span class="text-xs font-medium text-gray-500">Total Tim: {{ $totalStaff }} Org</span>
                    <a href="{{ route('attendance.index') }}" class="text-xs font-bold text-indigo-600 hover:underline">Absen →</a>
                </div>
            </div>
            @endcan

        </div>

        {{-- GRID DETAIL & CHART --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">

            {{-- CHART KEUANGAN --}}
            @can('payments.index')
            <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-6 gap-4">
                    <div>
                        <h3 class="text-lg font-bold text-gray-800">Cash Flow (Bulan Ini)</h3>
                        <div class="flex items-center gap-3 mt-1">
                            <span class="text-sm text-gray-500">Net:</span>
                            <span class="text-xl font-bold {{ $finance['profit'] >= 0 ? 'text-indigo-600' : 'text-red-600' }}">
                                Rp {{ number_format($finance['profit'], 0, ',', '.') }}
                            </span>
                        </div>
                    </div>
                    <div class="flex gap-3 text-sm">
                        <div class="px-3 py-1 bg-green-50 text-green-700 rounded-lg border border-green-100">
                            <span class="block text-xs text-green-500">Masuk</span>
                            <span class="font-bold">Rp {{ number_format($finance['income'] / 1000000, 1, ',', '.') }} Jt</span>
                        </div>
                        <div class="px-3 py-1 bg-red-50 text-red-700 rounded-lg border border-red-100">
                            <span class="block text-xs text-red-500">Keluar</span>
                            <span class="font-bold">Rp {{ number_format($finance['expense'] / 1000000, 1, ',', '.') }} Jt</span>
                        </div>
                    </div>
                </div>
                <div class="relative h-72">
                    <canvas id="incomeChart"></canvas>
                </div>
            </div>
            @endcan

            {{-- TABEL STOK KRITIS --}}
            @can('stock-report.index')
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden flex flex-col">
                <div class="px-6 py-4 border-b border-gray-100 bg-red-50 flex justify-between items-center">
                    <h3 class="text-sm font-bold text-red-800 uppercase tracking-wider">Stok Kritis</h3>
                    <a href="{{ route('stock-report.minimum-stock') }}" class="text-xs text-red-600 hover:underline font-medium">Lihat Semua</a>
                </div>
                <div class="divide-y divide-gray-100 flex-1 overflow-y-auto max-h-[300px]">
                    @forelse($lowStockItems as $stock)
                        <div class="px-6 py-3 hover:bg-gray-50 transition">
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="text-sm font-medium text-gray-900 line-clamp-1">{{ $stock->item->name }}</p>
                                    <p class="text-xs text-gray-500">{{ $stock->warehouse->name }}</p>
                                </div>
                                <div class="text-right ml-2">
                                    <span class="block text-sm font-bold text-red-600">
                                        {{ $stock->quantity + 0 }} <span class="text-xs font-normal text-gray-400">{{ $stock->item->unit }}</span>
                                    </span>
                                    <span class="text-[10px] text-gray-400 block">Min: {{ $stock->item->minimum_stock + 0 }}</span>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="p-8 text-center flex flex-col items-center justify-center h-full">
                            <div class="bg-green-100 p-3 rounded-full mb-3">
                                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            </div>
                            <p class="text-sm text-gray-500">Semua stok aman!</p>
                        </div>
                    @endforelse
                </div>
            </div>
            @endcan
            
            {{-- TABEL TIKET TERBARU --}}
            @can('tickets.index')
            <div class="{{ auth()->user()->can('payments.index') && !auth()->user()->can('stock-report.index') ? 'lg:col-span-1' : 'lg:col-span-3' }} bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                    <h3 class="text-lg font-bold text-gray-800">Tiket Gangguan Terbaru</h3>
                    <a href="{{ route('tickets.index') }}" class="text-sm font-medium text-indigo-600 hover:underline">Buka Helpdesk</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subjek</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($ticketStats['latest'] as $ticket)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-3 whitespace-nowrap text-xs font-mono font-bold text-indigo-600">
                                        #{{ $ticket->ticket_code }}
                                    </td>
                                    <td class="px-6 py-3 text-sm text-gray-900 font-medium">
                                        {{ Str::limit($ticket->subject, 30) }}
                                        <div class="text-xs text-gray-500">{{ $ticket->customer->user->name ?? 'Unknown' }}</div>
                                    </td>
                                    <td class="px-6 py-3 whitespace-nowrap">
                                        <span class="px-2 py-1 rounded-full text-[10px] font-bold {{ $ticket->getStatusBadgeClass() }}">
                                            {{ ucfirst($ticket->status) }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-6 py-8 text-center text-gray-500 text-sm">Tidak ada tiket baru.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @endcan

        </div>

    </div>

    {{-- CHART SCRIPT --}}
    @can('payments.index')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('incomeChart').getContext('2d');
        const labels = {!! json_encode($chart['labels']) !!};
        const dataValues = {!! json_encode($chart['data']) !!};

        new Chart(ctx, {
            type: 'line', 
            data: {
                labels: labels,
                datasets: [{
                    label: 'Pemasukan',
                    data: dataValues,
                    borderColor: '#4F46E5',
                    backgroundColor: 'rgba(79, 70, 229, 0.1)',
                    borderWidth: 2,
                    tension: 0.4, 
                    fill: true,
                    pointBackgroundColor: '#ffffff',
                    pointBorderColor: '#4F46E5',
                    pointRadius: 4,
                    pointHoverRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return ' Rp ' + new Intl.NumberFormat('id-ID').format(context.parsed.y);
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        suggestedMax: 1000000, 
                        grid: { borderDash: [2, 4], color: '#f3f4f6' },
                        ticks: {
                            callback: function(value) {
                                if (value >= 1000000) return (value / 1000000) + ' Jt';
                                if (value >= 1000) return (value / 1000) + ' Rb';
                                return value;
                            },
                            font: { size: 10 },
                            color: '#9ca3af'
                        },
                        border: { display: false }
                    },
                    x: {
                        grid: { display: false },
                        ticks: { font: { size: 11 }, color: '#6b7280' }
                    }
                }
            }
        });
    </script>
    @endcan
@endsection