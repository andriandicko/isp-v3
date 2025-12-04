@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-gray-50 py-6 sm:py-8 lg:py-12">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-6 sm:mb-8">
                <a href="{{ route('shifts.index') }}"
                    class="inline-flex items-center text-sm text-gray-600 hover:text-gray-900 transition-colors mb-4">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    Kembali ke Daftar Shift
                </a>
                <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Edit Shift</h1>
                <p class="mt-2 text-sm text-gray-600">Perbarui informasi shift kerja: <span
                        class="font-medium text-gray-900">{{ $shift->name }}</span></p>
            </div>

            <!-- Form Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <form action="{{ route('shifts.update', $shift) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="p-6 sm:p-8 space-y-6 sm:space-y-8">

                        <!-- Informasi Dasar Section -->
                        <div>
                            <h2 class="text-lg font-semibold text-gray-900 mb-4 pb-2 border-b border-gray-200">
                                Informasi Dasar
                            </h2>

                            <div class="space-y-5">
                                <!-- Nama Shift -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Nama Shift <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="name" value="{{ old('name', $shift->name) }}"
                                        class="w-full px-4 py-3 rounded-lg border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all @error('name') border-red-500 focus:border-red-500 focus:ring-red-200 @enderror"
                                        placeholder="Contoh: Shift Pagi, Shift Siang" required>
                                    @error('name')
                                        <p class="mt-2 text-sm text-red-600 flex items-center">
                                            <svg class="w-4 h-4 mr-1 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>

                                <!-- Jam Kerja -->
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            Jam Mulai <span class="text-red-500">*</span>
                                        </label>
                                        <div class="relative">
                                            <input type="time" name="start_time"
                                                value="{{ old('start_time', \Carbon\Carbon::parse($shift->start_time)->format('H:i')) }}"
                                                class="w-full px-4 py-3 rounded-lg border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all @error('start_time') border-red-500 focus:border-red-500 focus:ring-red-200 @enderror"
                                                required>
                                            <svg class="absolute right-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400 pointer-events-none"
                                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </div>
                                        @error('start_time')
                                            <p class="mt-2 text-sm text-red-600 flex items-center">
                                                <svg class="w-4 h-4 mr-1 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                                {{ $message }}
                                            </p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            Jam Selesai <span class="text-red-500">*</span>
                                        </label>
                                        <div class="relative">
                                            <input type="time" name="end_time"
                                                value="{{ old('end_time', \Carbon\Carbon::parse($shift->end_time)->format('H:i')) }}"
                                                class="w-full px-4 py-3 rounded-lg border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all @error('end_time') border-red-500 focus:border-red-500 focus:ring-red-200 @enderror"
                                                required>
                                            <svg class="absolute right-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400 pointer-events-none"
                                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </div>
                                        @error('end_time')
                                            <p class="mt-2 text-sm text-red-600 flex items-center">
                                                <svg class="w-4 h-4 mr-1 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                                {{ $message }}
                                            </p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Jadwal Kerja Section -->
                        <div>
                            <h2 class="text-lg font-semibold text-gray-900 mb-4 pb-2 border-b border-gray-200">
                                Jadwal Kerja
                            </h2>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-3">
                                    Hari Kerja <span class="text-red-500">*</span>
                                </label>
                                <p class="text-sm text-gray-500 mb-4">Pilih hari-hari kerja untuk shift ini</p>

                                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3">
                                    @php
                                        $days = [
                                            'monday' => ['label' => 'Senin', 'short' => 'Sen'],
                                            'tuesday' => ['label' => 'Selasa', 'short' => 'Sel'],
                                            'wednesday' => ['label' => 'Rabu', 'short' => 'Rab'],
                                            'thursday' => ['label' => 'Kamis', 'short' => 'Kam'],
                                            'friday' => ['label' => 'Jumat', 'short' => 'Jum'],
                                            'saturday' => ['label' => 'Sabtu', 'short' => 'Sab'],
                                            'sunday' => ['label' => 'Minggu', 'short' => 'Min'],
                                        ];
                                        $selectedDays = old('days', $shift->days);
                                    @endphp

                                    @foreach ($days as $value => $day)
                                        <label
                                            class="relative flex items-center justify-center p-4 border-2 rounded-lg cursor-pointer transition-all hover:border-blue-300 hover:bg-blue-50 has-[:checked]:border-blue-500 has-[:checked]:bg-blue-50 has-[:checked]:ring-2 has-[:checked]:ring-blue-200">
                                            <input type="checkbox" name="days[]" value="{{ $value }}"
                                                {{ is_array($selectedDays) && in_array($value, $selectedDays) ? 'checked' : '' }}
                                                class="sr-only peer">
                                            <div class="text-center">
                                                <span
                                                    class="block text-sm font-medium text-gray-700 peer-checked:text-blue-700 sm:hidden">
                                                    {{ $day['short'] }}
                                                </span>
                                                <span
                                                    class="hidden sm:block text-sm font-medium text-gray-700 peer-checked:text-blue-700">
                                                    {{ $day['label'] }}
                                                </span>
                                            </div>
                                            <svg class="absolute top-2 right-2 w-5 h-5 text-blue-600 opacity-0 peer-checked:opacity-100 transition-opacity"
                                                fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </label>
                                    @endforeach
                                </div>

                                @error('days')
                                    <p class="mt-3 text-sm text-red-600 flex items-center">
                                        <svg class="w-4 h-4 mr-1 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>
                        </div>

                        <!-- Status Section -->
                        <div>
                            <h2 class="text-lg font-semibold text-gray-900 mb-4 pb-2 border-b border-gray-200">
                                Status Shift
                            </h2>

                            <div class="bg-gray-50 rounded-lg p-4">
                                <label class="flex items-start cursor-pointer group">
                                    <div class="flex items-center h-6">
                                        <input type="checkbox" name="is_active" value="1"
                                            {{ old('is_active', $shift->is_active) ? 'checked' : '' }}
                                            class="w-5 h-5 rounded border-gray-300 text-blue-600 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all">
                                    </div>
                                    <div class="ml-3">
                                        <span class="block text-sm font-medium text-gray-900 group-hover:text-gray-700">
                                            Aktifkan shift ini
                                        </span>
                                        <span class="block text-sm text-gray-500 mt-1">
                                            Shift yang diaktifkan dapat langsung digunakan untuk penjadwalan karyawan
                                        </span>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <!-- Informasi Tambahan -->
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                            <div class="flex">
                                <svg class="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5" fill="currentColor"
                                    viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                        clip-rule="evenodd" />
                                </svg>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-blue-900">Informasi</h3>
                                    <p class="mt-1 text-sm text-blue-700">
                                        Perubahan pada shift ini akan mempengaruhi jadwal yang sudah ada. Pastikan untuk
                                        mengecek jadwal karyawan setelah melakukan update.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="px-6 sm:px-8 py-4 bg-gray-50 border-t border-gray-200">
                        <div class="flex flex-col-reverse sm:flex-row sm:justify-between gap-3">
                            <a href="{{ route('shifts.index') }}"
                                class="w-full sm:w-auto inline-flex justify-center items-center px-6 py-3 border-2 border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 hover:border-gray-400 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-all">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                                Batal
                            </a>
                            <button type="submit"
                                class="w-full sm:w-auto inline-flex justify-center items-center px-6 py-3 border border-transparent text-sm font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 shadow-sm hover:shadow-md transition-all">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                </svg>
                                Update Shift
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Informasi Terakhir Diupdate -->
            @if ($shift->updated_at)
                <div class="mt-4 text-center text-sm text-gray-500">
                    Terakhir diupdate: {{ $shift->updated_at->format('d M Y, H:i') }} WIB
                </div>
            @endif
        </div>
    </div>
@endsection
