@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 py-4 sm:py-8">
        <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8">
            <div class="mb-6 sm:mb-8">
                <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-gray-900 mb-2">Absensi Karyawan</h1>
                <p class="text-sm sm:text-base text-gray-600">Kelola kehadiran Anda dengan mudah dan cepat</p>
            </div>

            @if (session('success'))
                <div class="bg-green-50 border-l-4 border-green-400 p-3 sm:p-4 mb-4 sm:mb-6 rounded-r-lg shadow-sm animate-fade-in">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            @if (session('error'))
                <div class="bg-red-50 border-l-4 border-red-400 p-3 sm:p-4 mb-4 sm:mb-6 rounded-r-lg shadow-sm animate-fade-in">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-6 mb-6 sm:mb-8">
                @if ($userShift)
                    <div class="lg:col-span-2 bg-white rounded-xl shadow-md hover:shadow-lg transition-shadow duration-300 overflow-hidden">
                        <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-4 sm:px-6 py-4">
                            <h3 class="text-lg sm:text-xl font-semibold text-white flex items-center">
                                <svg class="w-5 h-5 sm:w-6 sm:h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Informasi Shift Anda
                            </h3>
                        </div>
                        <div class="p-4 sm:p-6">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
                                <div class="space-y-3 sm:space-y-4">
                                    <div class="flex items-start">
                                        <div class="flex-shrink-0 w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                                            <svg class="w-4 h-4 sm:w-5 sm:h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-xs sm:text-sm text-gray-500 font-medium">Shift</p>
                                            <p class="text-sm sm:text-base font-semibold text-gray-900">{{ $userShift->shift->name }}</p>
                                        </div>
                                    </div>
                                    <div class="flex items-start">
                                        <div class="flex-shrink-0 w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                                            <svg class="w-4 h-4 sm:w-5 sm:h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-xs sm:text-sm text-gray-500 font-medium">Jam Kerja</p>
                                            <p class="text-sm sm:text-base font-semibold text-gray-900">
                                                {{ \Carbon\Carbon::parse($userShift->shift->start_time)->format('H:i') }} -
                                                {{ \Carbon\Carbon::parse($userShift->shift->end_time)->format('H:i') }}
                                            </p>
                                        </div>
                                    </div>
                                    <div class="flex items-start">
                                        <div class="flex-shrink-0 w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center">
                                            <svg class="w-4 h-4 sm:w-5 sm:h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-xs sm:text-sm text-gray-500 font-medium">Hari Kerja</p>
                                            <p class="text-sm sm:text-base font-semibold text-gray-900">
                                                {{ implode(', ', array_map('ucfirst', $userShift->shift->days ?? [])) }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="space-y-3 sm:space-y-4">
                                    {{-- Multi-Office Info --}}
                                    <div class="flex items-start">
                                        <div class="flex-shrink-0 w-8 h-8 bg-red-100 rounded-lg flex items-center justify-center">
                                            <svg class="w-4 h-4 sm:w-5 sm:h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                            </svg>
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-xs sm:text-sm text-gray-500 font-medium">Lokasi Utama</p>
                                            <p class="text-sm sm:text-base font-semibold text-gray-900">{{ $userShift->office->name }}</p>
                                            @if($allowedShifts->count() > 1)
                                                <p class="text-xs text-blue-600 italic">(+{{ $allowedShifts->count() - 1 }} lokasi lainnya)</p>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="flex items-start">
                                        <div class="flex-shrink-0 w-8 h-8 bg-yellow-100 rounded-lg flex items-center justify-center">
                                            <svg class="w-4 h-4 sm:w-5 sm:h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" />
                                            </svg>
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-xs sm:text-sm text-gray-500 font-medium">Alamat Utama</p>
                                            <p class="text-sm sm:text-base font-semibold text-gray-900 line-clamp-2">{{ $userShift->office->address }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="lg:col-span-2 bg-gradient-to-r from-yellow-50 to-orange-50 border-l-4 border-yellow-400 p-4 sm:p-6 rounded-r-xl shadow-md">
                        <div class="flex items-start">
                            <svg class="w-6 h-6 text-yellow-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                            <div class="ml-3">
                                <h3 class="text-sm sm:text-base font-semibold text-yellow-800">Shift Belum Diatur</h3>
                                <p class="text-xs sm:text-sm text-yellow-700 mt-1">Anda belum memiliki shift aktif hari ini. Silakan hubungi admin.</p>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="bg-gradient-to-br from-indigo-600 to-purple-600 rounded-xl shadow-lg text-white p-4 sm:p-6 flex flex-col justify-center items-center">
                    <div class="text-center">
                        <p class="text-xs sm:text-sm font-medium opacity-90 mb-2">Waktu Saat Ini</p>
                        <div id="currentTime" class="text-3xl sm:text-4xl font-bold mb-1"></div>
                        <p class="text-xs sm:text-sm opacity-75">{{ \Carbon\Carbon::now('Asia/Jakarta')->isoFormat('dddd, D MMMM YYYY') }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-md hover:shadow-lg transition-shadow duration-300 mb-6 sm:mb-8 overflow-hidden">
                <div class="bg-gradient-to-r from-green-600 to-teal-600 px-4 sm:px-6 py-4">
                    <h3 class="text-lg sm:text-xl font-semibold text-white flex items-center">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Status Kehadiran Hari Ini
                    </h3>
                </div>

                <div class="p-4 sm:p-6">
                    @if ($leaveToday)
                        <div class="bg-gradient-to-r from-blue-50 to-cyan-50 border-l-4 border-blue-400 p-4 sm:p-6 rounded-r-lg">
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    <svg class="w-8 h-8 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <h4 class="text-base sm:text-lg font-semibold text-blue-900 mb-2">
                                        @if ($leaveToday->type === 'leave') Izin
                                        @elseif($leaveToday->type === 'sick') Sakit
                                        @else Dinas Luar Kota @endif
                                    </h4>
                                    <p class="text-sm sm:text-base text-blue-800"><span class="font-medium">Alasan:</span> {{ $leaveToday->reason }}</p>
                                </div>
                            </div>
                        </div>
                    @elseif($todayAttendance)
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6 mb-6">
                            <div class="bg-gradient-to-br from-green-50 to-emerald-50 border-2 border-green-200 rounded-xl p-4 sm:p-6 hover:shadow-md transition-all duration-300">
                                <div class="flex items-center justify-between mb-3">
                                    <h4 class="text-sm font-semibold text-gray-600 uppercase tracking-wide">Check In</h4>
                                    <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                                        </svg>
                                    </div>
                                </div>
                                @if ($todayAttendance->check_in_time)
                                    <p class="text-3xl sm:text-4xl font-bold text-green-700 mb-2">
                                        {{ \Carbon\Carbon::parse($todayAttendance->check_in_time)->format('H:i') }}
                                    </p>
                                    @if ($todayAttendance->status === 'late')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-800">
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" /></svg>
                                            Terlambat
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg>
                                            Tepat Waktu
                                        </span>
                                    @endif
                                @else
                                    <p class="text-xl text-gray-400 font-medium">Belum Check In</p>
                                @endif
                            </div>

                            <div class="bg-gradient-to-br from-red-50 to-rose-50 border-2 border-red-200 rounded-xl p-4 sm:p-6 hover:shadow-md transition-all duration-300">
                                <div class="flex items-center justify-between mb-3">
                                    <h4 class="text-sm font-semibold text-gray-600 uppercase tracking-wide">Check Out</h4>
                                    <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center">
                                        <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                        </svg>
                                    </div>
                                </div>
                                @if ($todayAttendance->check_out_time)
                                    <p class="text-3xl sm:text-4xl font-bold text-red-700 mb-2">
                                        {{ \Carbon\Carbon::parse($todayAttendance->check_out_time)->format('H:i') }}
                                    </p>
                                    <p class="text-xs sm:text-sm text-gray-600 font-medium">
                                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                        Durasi: {{ floor($todayAttendance->getWorkDurationInMinutes() / 60) }} jam {{ $todayAttendance->getWorkDurationInMinutes() % 60 }} menit
                                    </p>
                                @else
                                    <p class="text-xl text-gray-400 font-medium">Belum Check Out</p>
                                @endif
                            </div>
                        </div>

                        <div class="text-center">
                            @if (!$todayAttendance->check_in_time)
                                <button type="button" onclick="openCamera('checkin')" class="inline-flex items-center px-6 sm:px-8 py-3 sm:py-4 border border-transparent text-base sm:text-lg font-semibold rounded-xl text-white bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-300 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200">
                                    <svg class="w-5 h-5 sm:w-6 sm:h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    Check In Sekarang
                                </button>
                            @elseif(!$todayAttendance->check_out_time)
                                <button type="button" onclick="openCamera('checkout')" class="inline-flex items-center px-6 sm:px-8 py-3 sm:py-4 border border-transparent text-base sm:text-lg font-semibold rounded-xl text-white bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 focus:outline-none focus:ring-4 focus:ring-red-300 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200">
                                    <svg class="w-5 h-5 sm:w-6 sm:h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    Check Out Sekarang
                                </button>
                            @else
                                <div class="inline-flex items-center px-6 py-4 text-green-700 bg-green-50 rounded-xl border-2 border-green-200">
                                    <svg class="w-6 h-6 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                    </svg>
                                    <span class="font-semibold text-sm sm:text-base">Anda sudah menyelesaikan absensi hari ini</span>
                                </div>
                            @endif
                        </div>
                    @else
                        <div class="text-center py-8 sm:py-12">
                            <div class="mb-6">
                                <div class="w-16 h-16 sm:w-20 sm:h-20 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <svg class="w-8 h-8 sm:w-10 sm:h-10 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <h4 class="text-lg sm:text-xl font-semibold text-gray-900 mb-2">Belum Ada Absensi</h4>
                                <p class="text-sm sm:text-base text-gray-600">Mulai hari Anda dengan melakukan check in</p>
                            </div>
                            @if($userShift)
                                <button type="button" onclick="openCamera('checkin')" class="inline-flex items-center px-6 sm:px-8 py-3 sm:py-4 border border-transparent text-base sm:text-lg font-semibold rounded-xl text-white bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-300 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200">
                                    <svg class="w-5 h-5 sm:w-6 sm:h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    Check In Sekarang
                                </button>
                            @else
                                <p class="text-yellow-600 bg-yellow-50 px-4 py-2 rounded-lg inline-block">Tidak ada jadwal shift hari ini</p>
                            @endif
                        </div>
                    @endif
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-md hover:shadow-lg transition-shadow duration-300 overflow-hidden">
                <div class="px-4 sm:px-6 py-4 bg-gradient-to-r from-purple-600 to-pink-600 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2">
                    <h3 class="text-lg sm:text-xl font-semibold text-white flex items-center">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Riwayat Absensi Bulan Ini
                    </h3>
                    @if(Route::has('attendance.recap'))
                        <a href="{{ route('attendance.recap') }}" class="inline-flex items-center text-xs sm:text-sm font-semibold text-white hover:text-purple-100 transition-colors bg-white/20 hover:bg-white/30 px-3 py-1.5 rounded-lg">
                            Lihat Rekap Lengkap
                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                        </a>
                    @endif
                </div>

                <div class="hidden sm:block overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Tanggal</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Shift</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Check In</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Check Out</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Durasi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($attendances as $attendance)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $attendance->date->isoFormat('ddd, D MMM Y') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $attendance->shift->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">
                                        {{ $attendance->check_in_time ? \Carbon\Carbon::parse($attendance->check_in_time)->format('H:i') : '-' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">
                                        {{ $attendance->check_out_time ? \Carbon\Carbon::parse($attendance->check_out_time)->format('H:i') : '-' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if ($attendance->status === 'present')
                                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Hadir</span>
                                        @elseif($attendance->status === 'late')
                                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Terlambat</span>
                                        @elseif($attendance->status === 'leave')
                                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">Izin</span>
                                        @elseif($attendance->status === 'sick')
                                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">Sakit</span>
                                        @else
                                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Tidak Hadir</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                        @if ($attendance->check_in_time && $attendance->check_out_time)
                                            {{ floor($attendance->getWorkDurationInMinutes() / 60) }}j {{ $attendance->getWorkDurationInMinutes() % 60 }}m
                                        @else
                                            -
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center text-sm text-gray-500">Belum ada data absensi</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="block sm:hidden">
                    @forelse($attendances as $attendance)
                        <div class="border-b border-gray-200 p-4 hover:bg-gray-50">
                            <div class="flex justify-between items-start mb-2">
                                <div>
                                    <p class="text-sm font-bold text-gray-900">{{ $attendance->date->isoFormat('ddd, D MMM Y') }}</p>
                                    <p class="text-xs text-gray-500">{{ $attendance->shift->name }}</p>
                                </div>
                                <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                    @if($attendance->status == 'present') bg-green-100 text-green-800
                                    @elseif($attendance->status == 'late') bg-yellow-100 text-yellow-800
                                    @else bg-gray-100 text-gray-800 @endif">
                                    {{ ucfirst($attendance->status) }}
                                </span>
                            </div>
                            <div class="flex justify-between text-xs mt-2">
                                <div><span class="text-gray-500">In:</span> {{ $attendance->check_in_time ? \Carbon\Carbon::parse($attendance->check_in_time)->format('H:i') : '-' }}</div>
                                <div><span class="text-gray-500">Out:</span> {{ $attendance->check_out_time ? \Carbon\Carbon::parse($attendance->check_out_time)->format('H:i') : '-' }}</div>
                            </div>
                        </div>
                    @empty
                        <div class="p-4 text-center text-sm text-gray-500">Belum ada data.</div>
                    @endforelse
                </div>
                
                <div class="px-4 py-3 bg-gray-50 border-t border-gray-200">
                    {{ $attendances->links() }}
                </div>
            </div>
        </div>
    </div>

    <div id="cameraModal" class="fixed z-50 inset-0 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen pt-4 px-3 sm:px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity backdrop-blur-sm" aria-hidden="true"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            
            <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl w-full mx-3 sm:mx-0">
                <div class="bg-gradient-to-r from-blue-600 to-purple-600 px-4 sm:px-6 py-4 flex items-center justify-between">
                    <h3 class="text-lg sm:text-xl font-bold text-white flex items-center" id="modal-title">Ambil Foto</h3>
                    <button onclick="closeCamera()" class="text-white hover:text-gray-200 transition-colors">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>

                <div class="bg-white px-4 sm:px-6 py-4">
                    <div id="locationStatus" class="mb-4 p-3 sm:p-4 bg-blue-50 border-l-4 border-blue-400 text-blue-700 text-xs sm:text-sm rounded-r-lg">
                        Mendeteksi lokasi...
                    </div>

                    <div id="cameraContainer" class="hidden">
                        <div class="relative rounded-xl overflow-hidden bg-gray-900">
                            <video id="video" class="w-full rounded-xl" autoplay playsinline></video>
                        </div>
                        <canvas id="canvas" class="hidden"></canvas>
                        <div id="preview" class="hidden">
                            <div class="relative rounded-xl overflow-hidden">
                                <img id="photo" class="w-full rounded-xl" />
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-gray-50 px-4 sm:px-6 py-3 sm:py-4 flex flex-col-reverse sm:flex-row sm:justify-end gap-2">
                    <button type="button" onclick="closeCamera()" class="w-full sm:w-auto inline-flex justify-center items-center rounded-lg border-2 border-gray-300 shadow-sm px-4 py-2.5 bg-white text-sm font-semibold text-gray-700 hover:bg-gray-50">Batal</button>
                    <button type="button" id="captureBtn" onclick="capturePhoto()" class="hidden w-full sm:w-auto inline-flex justify-center items-center rounded-lg border border-transparent shadow-sm px-4 py-2.5 bg-blue-600 text-sm font-semibold text-white hover:bg-blue-700">Ambil Foto</button>
                    <button type="button" id="retakeBtn" onclick="retakePhoto()" class="hidden w-full sm:w-auto inline-flex justify-center items-center rounded-lg border border-transparent shadow-sm px-4 py-2.5 bg-yellow-600 text-sm font-semibold text-white hover:bg-yellow-700">Foto Ulang</button>
                    <button type="button" id="submitBtn" onclick="submitAttendance()" class="hidden w-full sm:w-auto inline-flex justify-center items-center rounded-lg border border-transparent shadow-sm px-4 py-2.5 bg-green-600 text-sm font-semibold text-white hover:bg-green-700">Kirim</button>
                </div>
            </div>
        </div>
    </div>
    
    <div id="successModal" class="fixed z-50 inset-0 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity backdrop-blur-sm" aria-hidden="true"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-sm w-full animate-fade-in">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-green-100 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-green-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                Berhasil!
                            </h3>
                            <div class="mt-2">
                                <p id="successMessage" class="text-sm text-gray-500">
                                    </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button" onclick="closeSuccessModal()" class="w-full inline-flex justify-center rounded-xl border border-transparent shadow-sm px-4 py-2 bg-green-600 text-base font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:ml-3 sm:w-auto sm:text-sm transition-all transform hover:scale-105">
                        OK, Siap!
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    {{-- LOGIC PHP DIPINDAHKAN KE SINI AGAR TIDAK ERROR DI @json --}}
    @php
        $officesData = $allowedShifts->map(function($shift) {
            return [
                'name' => $shift->office->name,
                'lat' => $shift->office->latitude,
                'lng' => $shift->office->longitude,
                'radius' => $shift->office->radius
            ];
        })->values();
    @endphp

    <script>
        // Ambil data yang sudah diolah di atas
        const allowedOffices = @json($officesData);

        // --- Real-time Clock ---
        function updateClock() {
            const now = new Date();
            const options = { timeZone: 'Asia/Jakarta', hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: false };
            const clockElement = document.getElementById('currentTime');
            if (clockElement) {
                clockElement.textContent = now.toLocaleTimeString('id-ID', options) + ' WIB';
            }
        }
        setInterval(updateClock, 1000);
        updateClock();

        // --- Variables ---
        let stream = null;
        let userLat = null;
        let userLng = null;
        let attendanceType = null;
        let isLocationValid = false;

        // --- Camera & Location Logic ---
        function openCamera(type) {
            attendanceType = type;
            document.getElementById('cameraModal').classList.remove('hidden');
            
            // Set Modal Title based on type
            const titleText = type === 'checkin' ? 'Check In - Ambil Foto' : 'Check Out - Ambil Foto';
            document.getElementById('modal-title').innerHTML = `
                <svg class="w-5 h-5 sm:w-6 sm:h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                ${titleText}
            `;

            // Reset UI states
            document.getElementById('cameraContainer').classList.add('hidden');
            document.getElementById('captureBtn').classList.add('hidden');
            
            getLocation();
        }

        function closeCamera() {
            document.getElementById('cameraModal').classList.add('hidden');
            if (stream) {
                stream.getTracks().forEach(track => track.stop());
            }
            
            // Reset UI Elements
            document.getElementById('video').classList.remove('hidden');
            document.getElementById('preview').classList.add('hidden');
            document.getElementById('cameraContainer').classList.add('hidden');
            document.getElementById('captureBtn').classList.add('hidden');
            document.getElementById('retakeBtn').classList.add('hidden');
            document.getElementById('submitBtn').classList.add('hidden');
            isLocationValid = false;
        }

        function calculateDistance(lat1, lon1, lat2, lon2) {
            const R = 6371000; // Radius Bumi dalam meter
            const dLat = (lat2 - lat1) * Math.PI / 180;
            const dLon = (lon2 - lon1) * Math.PI / 180;
            const a = Math.sin(dLat / 2) * Math.sin(dLat / 2) +
                Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
                Math.sin(dLon / 2) * Math.sin(dLon / 2);
            const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
            return R * c;
        }

        function getLocation() {
            const statusEl = document.getElementById('locationStatus');
            statusEl.className = 'mb-4 p-3 sm:p-4 bg-blue-50 border-l-4 border-blue-400 text-blue-700 text-xs sm:text-sm rounded-r-lg';
            statusEl.innerHTML = '<div class="flex items-center"><svg class="animate-spin h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Mendeteksi lokasi...</div>';

            if (!navigator.geolocation) {
                showErrorStatus("Browser Anda tidak mendukung geolokasi.");
                return;
            }

            navigator.geolocation.getCurrentPosition(
                (position) => {
                    userLat = position.coords.latitude;
                    userLng = position.coords.longitude;
                    checkOfficesDistance(userLat, userLng);
                },
                (error) => {
                    let msg = "Gagal mengambil lokasi.";
                    if (error.code === error.PERMISSION_DENIED) msg = "Izin lokasi ditolak. Mohon aktifkan GPS dan izinkan lokasi.";
                    else if (error.code === error.POSITION_UNAVAILABLE) msg = "Informasi lokasi tidak tersedia.";
                    else if (error.code === error.TIMEOUT) msg = "Waktu permintaan lokasi habis.";
                    showErrorStatus(msg);
                },
                { enableHighAccuracy: true, timeout: 10000, maximumAge: 0 }
            );
        }

        function checkOfficesDistance(lat, lng) {
            let nearestDistance = Infinity;
            let nearestOffice = null;
            let isValid = false;

            // Loop semua kantor yang diizinkan untuk mencari yang masuk radius
            allowedOffices.forEach(office => {
                const dist = calculateDistance(lat, lng, office.lat, office.lng);
                
                // Simpan yang terdekat untuk info error jika semua kejauhan
                if (dist < nearestDistance) {
                    nearestDistance = dist;
                    nearestOffice = office;
                }

                // Cek radius
                if (dist <= office.radius) {
                    isValid = true;
                    // Jika valid, prioritas tampilkan info kantor ini
                    nearestDistance = dist; 
                    nearestOffice = office;
                }
            });

            const statusEl = document.getElementById('locationStatus');
            
            if (isValid) {
                isLocationValid = true;
                statusEl.className = 'mb-4 p-3 sm:p-4 bg-green-50 border-l-4 border-green-400 text-green-700 text-xs sm:text-sm rounded-r-lg';
                statusEl.innerHTML = `
                    <div class="flex flex-col">
                        <span class="font-bold flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                            Lokasi Valid: ${nearestOffice.name}
                        </span>
                        <span class="text-xs mt-1">Jarak: ${Math.round(nearestDistance)}m (Radius: ${nearestOffice.radius}m)</span>
                    </div>
                `;
                startCamera();
            } else {
                isLocationValid = false;
                statusEl.className = 'mb-4 p-3 sm:p-4 bg-red-50 border-l-4 border-red-400 text-red-700 text-xs sm:text-sm rounded-r-lg';
                statusEl.innerHTML = `
                    <div class="flex flex-col">
                        <span class="font-bold flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
                            Lokasi Tidak Valid
                        </span>
                        <span class="text-xs mt-1">Kantor terdekat: ${nearestOffice ? nearestOffice.name : '-'}</span>
                        <span class="text-xs">Jarak Anda: ${Math.round(nearestDistance)}m (Maks: ${nearestOffice ? nearestOffice.radius : 0}m)</span>
                    </div>
                `;
                // Jangan start camera jika lokasi tidak valid
            }
        }

        function showErrorStatus(message) {
            const statusEl = document.getElementById('locationStatus');
            statusEl.className = 'mb-4 p-3 sm:p-4 bg-red-50 border-l-4 border-red-400 text-red-700 text-xs sm:text-sm rounded-r-lg';
            statusEl.innerHTML = `<span class="font-bold">Error:</span> ${message}`;
        }

        async function startCamera() {
            try {
                const video = document.getElementById('video');
                // Gunakan user (depan) kamera
                stream = await navigator.mediaDevices.getUserMedia({ 
                    video: { facingMode: 'user', width: { ideal: 640 }, height: { ideal: 640 } } 
                });
                video.srcObject = stream;
                document.getElementById('cameraContainer').classList.remove('hidden');
                
                // Tampilkan tombol capture hanya jika lokasi valid
                if(isLocationValid) {
                    document.getElementById('captureBtn').classList.remove('hidden');
                }
            } catch (err) {
                alert('Gagal mengakses kamera: ' + err.message);
                console.error(err);
            }
        }

        function capturePhoto() {
            const video = document.getElementById('video');
            const canvas = document.getElementById('canvas');
            const photo = document.getElementById('photo');
            
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            const context = canvas.getContext('2d');
            context.drawImage(video, 0, 0);
            
            const dataURL = canvas.toDataURL('image/jpeg', 0.8);
            photo.src = dataURL;

            // UI Changes
            document.getElementById('video').classList.add('hidden');
            document.getElementById('preview').classList.remove('hidden');
            document.getElementById('captureBtn').classList.add('hidden');
            document.getElementById('retakeBtn').classList.remove('hidden');
            document.getElementById('submitBtn').classList.remove('hidden');
        }

        function retakePhoto() {
            document.getElementById('video').classList.remove('hidden');
            document.getElementById('preview').classList.add('hidden');
            document.getElementById('captureBtn').classList.remove('hidden');
            document.getElementById('retakeBtn').classList.add('hidden');
            document.getElementById('submitBtn').classList.add('hidden');
        }

        function submitAttendance() {
            if (!isLocationValid) {
                alert('Lokasi Anda tidak valid. Silakan mendekat ke kantor.');
                return;
            }

            const btn = document.getElementById('submitBtn');
            const originalText = btn.innerHTML;
            btn.disabled = true;
            btn.innerHTML = '<svg class="animate-spin h-5 w-5 mr-2" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Mengirim...';

            const canvas = document.getElementById('canvas');
            
            canvas.toBlob(function(blob) {
                const formData = new FormData();
                formData.append('photo', blob, 'attendance.jpg');
                formData.append('latitude', userLat);
                formData.append('longitude', userLng);
                formData.append('_token', '{{ csrf_token() }}');

                const url = attendanceType === 'checkin' ? '{{ route('attendance.checkin') }}' : '{{ route('attendance.checkout') }}';

                fetch(url, {
                    method: 'POST',
                    headers: { 'X-Requested-With': 'XMLHttpRequest' },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // --- PERUBAHAN DI SINI: Panggil Modal, Bukan Alert ---
                        showSuccessModal(data.message);
                    } else {
                        throw new Error(data.message);
                    }
                })
                .catch(error => {
                    alert(error.message || 'Terjadi kesalahan sistem.');
                    btn.disabled = false;
                    btn.innerHTML = originalText;
                });
            }, 'image/jpeg', 0.8);
        }

        // --- Fungsi Baru untuk Kontrol Modal Sukses ---
        function showSuccessModal(message) {
            // Tutup modal kamera dulu agar tidak tumpang tindih
            closeCamera(); 
            
            // Isi pesan sukses
            document.getElementById('successMessage').textContent = message;
            
            // Tampilkan modal sukses
            document.getElementById('successModal').classList.remove('hidden');
        }

        function closeSuccessModal() {
            // Sembunyikan modal
            document.getElementById('successModal').classList.add('hidden');
            
            // Reload halaman untuk memperbarui data
            window.location.reload();
        }

        // Custom Styles for Animation
        const style = document.createElement('style');
        style.textContent = `
            @keyframes fade-in { from { opacity: 0; transform: translateY(-10px); } to { opacity: 1; transform: translateY(0); } }
            .animate-fade-in { animation: fade-in 0.3s ease-out; }
            .line-clamp-2 { overflow: hidden; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; }
        `;
        document.head.appendChild(style);
    </script>
@endpush