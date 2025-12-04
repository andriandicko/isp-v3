@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 py-4 sm:py-8">
        <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8">
            
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6 sm:mb-8">
                <div>
                    <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-gray-900 mb-2">Rekap Absensi</h1>
                    <p class="text-sm sm:text-base text-gray-600">Laporan kehadiran karyawan secara lengkap</p>
                </div>
                
                <div class="flex gap-2">
                    <a href="{{ route('attendance.index') }}" class="inline-flex items-center px-4 py-2.5 border-2 border-gray-300 text-sm font-semibold rounded-xl text-gray-700 bg-white hover:bg-gray-50 hover:border-gray-400 transition-all shadow-sm hover:shadow-md print:hidden">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                        Kembali
                    </a>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-md hover:shadow-lg transition-shadow duration-300 p-4 sm:p-6 mb-6 print:hidden">
                <div class="flex items-center mb-4">
                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" /></svg>
                    </div>
                    <h3 class="text-lg sm:text-xl font-semibold text-gray-900">Filter Periode</h3>
                </div>

                <form method="GET" action="{{ route('attendance.recap') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    @if(isset($users) && count($users) > 0)
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Karyawan</label>
                            <select name="user_id" class="w-full rounded-lg border-2 border-gray-300 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all py-2.5 px-3">
                                <option value="">Semua Karyawan</option>
                                @foreach($users as $u)
                                    <option value="{{ $u->id }}" {{ request('user_id') == $u->id ? 'selected' : '' }}>{{ $u->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Bulan</label>
                        <select name="month" class="w-full rounded-lg border-2 border-gray-300 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all py-2.5 px-3">
                            @for ($m = 1; $m <= 12; $m++)
                                <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>
                                    {{ \Carbon\Carbon::create()->month($m)->isoFormat('MMMM') }}
                                </option>
                            @endfor
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Tahun</label>
                        <select name="year" class="w-full rounded-lg border-2 border-gray-300 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all py-2.5 px-3">
                            @for ($y = date('Y'); $y >= date('Y') - 2; $y--)
                                <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                            @endfor
                        </select>
                    </div>

                    <div class="flex items-end">
                        <button type="submit" class="w-full inline-flex justify-center items-center px-6 py-2.5 border border-transparent text-sm font-semibold rounded-lg text-white bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-300 shadow-md hover:shadow-lg transform hover:-translate-y-0.5 transition-all">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                            Tampilkan Data
                        </button>
                    </div>
                </form>
            </div>

            <div class="mb-6">
                <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 mb-4">
                    <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg p-4 sm:p-6 text-white transform hover:scale-105 transition-transform duration-200">
                        <h3 class="text-3xl sm:text-4xl font-bold mb-1">{{ $stats['total_days'] }}</h3>
                        <p class="text-xs sm:text-sm font-medium opacity-90">Total Kehadiran</p>
                    </div>
                    <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-lg p-4 sm:p-6 text-white transform hover:scale-105 transition-transform duration-200">
                        <h3 class="text-3xl sm:text-4xl font-bold mb-1">{{ $stats['present'] }}</h3>
                        <p class="text-xs sm:text-sm font-medium opacity-90">Tepat Waktu</p>
                    </div>
                    <div class="bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-xl shadow-lg p-4 sm:p-6 text-white transform hover:scale-105 transition-transform duration-200">
                        <h3 class="text-3xl sm:text-4xl font-bold mb-1">{{ $stats['late'] }}</h3>
                        <p class="text-xs sm:text-sm font-medium opacity-90">Terlambat</p>
                    </div>
                    <div class="bg-gradient-to-br from-red-500 to-red-600 rounded-xl shadow-lg p-4 sm:p-6 text-white transform hover:scale-105 transition-transform duration-200">
                        <h3 class="text-3xl sm:text-4xl font-bold mb-1">{{ $stats['absent'] }}</h3>
                        <p class="text-xs sm:text-sm font-medium opacity-90">Tanpa Keterangan</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-md hover:shadow-lg transition-shadow duration-300 overflow-hidden">
                <div class="px-4 sm:px-6 py-4 bg-gradient-to-r from-purple-600 to-pink-600 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
                    <h3 class="text-lg sm:text-xl font-semibold text-white flex items-center">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                        Detail Absensi - {{ \Carbon\Carbon::create()->month($month)->isoFormat('MMMM') }} {{ $year }}
                    </h3>
                    <button onclick="window.print()" class="inline-flex items-center px-4 py-2 border-2 border-white text-sm font-semibold rounded-lg text-white hover:bg-white hover:text-purple-600 transition-all print:hidden">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" /></svg>
                        Cetak
                    </button>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">No</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Karyawan</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Tanggal</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Waktu</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Durasi</th>
                                <th class="px-6 py-3 text-center text-xs font-bold text-gray-600 uppercase tracking-wider print:hidden">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($attendances as $index => $attendance)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap text-xs">{{ $index + 1 }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $attendance->user->name ?? '-' }}</div>
                                        <div class="text-xs text-gray-500">{{ $attendance->shift->name }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $attendance->date->isoFormat('ddd, D MMM') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-xs">
                                        <div class="flex flex-col">
                                            <span class="text-green-600 font-semibold">In: {{ $attendance->check_in_time ? \Carbon\Carbon::parse($attendance->check_in_time)->format('H:i') : '-' }}</span>
                                            <span class="text-red-600 font-semibold">Out: {{ $attendance->check_out_time ? \Carbon\Carbon::parse($attendance->check_out_time)->format('H:i') : '-' }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if ($attendance->status === 'present')
                                            <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Hadir</span>
                                        @elseif($attendance->status === 'late')
                                            <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">Terlambat</span>
                                        @else
                                            <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">{{ ucfirst($attendance->status) }}</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-xs text-gray-600">
                                        @if ($attendance->check_in_time && $attendance->check_out_time)
                                            {{ floor($attendance->getWorkDurationInMinutes() / 60) }}j {{ $attendance->getWorkDurationInMinutes() % 60 }}m
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center print:hidden">
                                        <button onclick='showDetail(@json($attendance), "{{ asset("storage/") }}")' 
                                            class="inline-flex items-center px-3 py-1.5 border border-blue-200 text-xs font-medium rounded-lg text-blue-600 bg-blue-50 hover:bg-blue-100 transition-colors">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                            Detail
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-12 text-center text-sm text-gray-500">Tidak ada data absensi</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <div class="px-4 py-3 bg-gray-50 border-t border-gray-200 print:hidden">
                    {{ $attendances->withQueryString()->links() }}
                </div>
            </div>
        </div>
    </div>
    
    {{-- PERUBAHAN PENTING: Gunakan PUSH ke 'modals' --}}
    @push('modals')
    <div id="detailModal" class="fixed inset-0 z-[9999] overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            
            <div class="fixed inset-0 transition-opacity bg-gray-900 bg-opacity-75 backdrop-blur-sm" onclick="closeDetailModal()"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">​</span>
            
            <div class="inline-block w-full overflow-hidden text-left align-bottom transition-all transform bg-white shadow-2xl rounded-2xl sm:my-8 sm:align-middle sm:max-w-4xl animate-fade-in-up">
                
                <div class="flex items-center justify-between px-4 py-3 border-b border-gray-200 bg-gray-50 sm:px-6">
                    <h3 class="text-lg font-bold leading-6 text-gray-900" id="modal-title">
                        Detail Kehadiran
                    </h3>
                    <button onclick="closeDetailModal()" class="text-gray-400 transition-colors hover:text-gray-500 focus:outline-none">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>

                <div class="px-4 pt-5 pb-4 bg-white sm:p-6">
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                        
                        {{-- Kolom Check IN --}}
                        <div class="p-4 border border-green-100 shadow-sm bg-green-50 rounded-xl">
                            <div class="flex items-center pb-2 mb-4 border-b border-green-200">
                                <div class="p-2 mr-3 bg-green-100 rounded-lg">
                                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" /></svg>
                                </div>
                                <div>
                                    <h4 class="text-lg font-bold text-green-900">Check In</h4>
                                    <p class="text-xs text-green-700" id="modalInTime">-</p>
                                </div>
                            </div>
                            
                            <div class="relative mb-4 overflow-hidden bg-gray-200 rounded-lg aspect-video group">
                                <img id="modalInPhoto" src="" alt="Foto Check In" class="object-cover w-full h-full">
                                <div class="absolute inset-0 flex items-center justify-center transition-all bg-black bg-opacity-0 group-hover:bg-opacity-30">
                                    <a id="modalInPhotoLink" href="#" target="_blank" class="px-3 py-1 text-xs font-bold text-gray-800 transition-all transform scale-90 bg-white rounded-full opacity-0 shadow-md group-hover:opacity-100 group-hover:scale-100">Lihat Full</a>
                                </div>
                            </div>

                            <div class="p-3 bg-white border border-green-200 rounded-lg">
                                <div class="mb-1 text-xs text-gray-500">Lokasi & Jarak:</div>
                                <div class="flex items-center justify-between">
                                    <span class="text-sm font-bold text-gray-800"><span id="modalInDist">0</span> meter</span>
                                    <a id="modalInMap" href="#" target="_blank" class="inline-flex items-center text-xs font-semibold text-blue-600 hover:underline">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                        Buka Maps
                                    </a>
                                </div>
                            </div>
                        </div>

                        {{-- Kolom Check OUT --}}
                        <div class="p-4 border border-red-100 shadow-sm bg-red-50 rounded-xl">
                            <div class="flex items-center pb-2 mb-4 border-b border-red-200">
                                <div class="p-2 mr-3 bg-red-100 rounded-lg">
                                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>
                                </div>
                                <div>
                                    <h4 class="text-lg font-bold text-red-900">Check Out</h4>
                                    <p class="text-xs text-red-700" id="modalOutTime">-</p>
                                </div>
                            </div>

                            <div id="modalOutContent">
                                <div class="relative mb-4 overflow-hidden bg-gray-200 rounded-lg aspect-video group">
                                    <img id="modalOutPhoto" src="" alt="Foto Check Out" class="object-cover w-full h-full">
                                    <div class="absolute inset-0 flex items-center justify-center transition-all bg-black bg-opacity-0 group-hover:bg-opacity-30">
                                        <a id="modalOutPhotoLink" href="#" target="_blank" class="px-3 py-1 text-xs font-bold text-gray-800 transition-all transform scale-90 bg-white rounded-full opacity-0 shadow-md group-hover:opacity-100 group-hover:scale-100">Lihat Full</a>
                                    </div>
                                </div>

                                <div class="p-3 bg-white border border-red-200 rounded-lg">
                                    <div class="mb-1 text-xs text-gray-500">Lokasi & Jarak:</div>
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm font-bold text-gray-800"><span id="modalOutDist">0</span> meter</span>
                                        <a id="modalOutMap" href="#" target="_blank" class="inline-flex items-center text-xs font-semibold text-blue-600 hover:underline">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                            Buka Maps
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <div id="modalOutEmpty" class="flex flex-col items-center justify-center hidden h-48 text-gray-400">
                                <svg class="w-12 h-12 mb-2 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                <span class="text-sm italic">Belum melakukan check out</span>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="flex flex-row-reverse px-4 py-3 bg-gray-50 sm:px-6">
                    <button type="button" onclick="closeDetailModal()" class="inline-flex justify-center w-full px-4 py-2 text-base font-medium text-gray-700 bg-white border border-gray-300 rounded-lg shadow-sm hover:bg-gray-50 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endpush

    @push('styles')
        <style>
            @media print {
                .print\:hidden { display: none !important; }
                .print\:block { display: block !important; }
                body { font-size: 11px; background: white !important; }
                table { width: 100%; border-collapse: collapse; }
                th, td { border: 1px solid #ddd; padding: 8px; }
            }
            .animate-fade-in-up { animation: fadeInUp 0.3s ease-out; }
            @keyframes fadeInUp { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
        </style>
    @endpush

    @push('scripts')
        <script>
            function showDetail(data, storageUrl) {
                const modal = document.getElementById('detailModal');
                modal.classList.remove('hidden');

                storageUrl = storageUrl.replace(/\/$/, ""); 

                // --- SETUP CHECK IN ---
                document.getElementById('modalInTime').textContent = data.check_in_time || '-';
                document.getElementById('modalInDist').textContent = data.check_in_distance || '0';
                
                const inPhotoUrl = data.check_in_photo 
                    ? storageUrl + '/' + data.check_in_photo 
                    : 'https://via.placeholder.com/400x300?text=No+Photo';
                
                document.getElementById('modalInPhoto').src = inPhotoUrl;
                document.getElementById('modalInPhotoLink').href = inPhotoUrl;

                // Maps Check In
                if(data.check_in_latitude && data.check_in_longitude) {
                    document.getElementById('modalInMap').href = `https://www.google.com/maps/search/?api=1&query=${data.check_in_latitude},${data.check_in_longitude}`;
                } else {
                    document.getElementById('modalInMap').href = '#';
                }

                // --- SETUP CHECK OUT ---
                const outContent = document.getElementById('modalOutContent');
                const outEmpty = document.getElementById('modalOutEmpty');

                if(data.check_out_time) {
                    outContent.classList.remove('hidden');
                    outEmpty.classList.add('hidden');

                    document.getElementById('modalOutTime').textContent = data.check_out_time;
                    document.getElementById('modalOutDist').textContent = data.check_out_distance;

                    const outPhotoUrl = data.check_out_photo 
                        ? storageUrl + '/' + data.check_out_photo 
                        : 'https://via.placeholder.com/400x300?text=No+Photo';
                        
                    document.getElementById('modalOutPhoto').src = outPhotoUrl;
                    document.getElementById('modalOutPhotoLink').href = outPhotoUrl;

                    // Maps Check Out
                    if(data.check_out_latitude && data.check_out_longitude) {
                        document.getElementById('modalOutMap').href = `https://www.google.com/maps/search/?api=1&query=${data.check_out_latitude},${data.check_out_longitude}`;
                    } else {
                        document.getElementById('modalOutMap').href = '#';
                    }
                } else {
                    outContent.classList.add('hidden');
                    outEmpty.classList.remove('hidden');
                    document.getElementById('modalOutTime').textContent = '-';
                }
            }

            function closeDetailModal() {
                document.getElementById('detailModal').classList.add('hidden');
            }
        </script>
    @endpush
@endsection