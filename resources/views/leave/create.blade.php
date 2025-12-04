@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-gray-50 py-8 sm:py-12">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Breadcrumb -->
            <nav class="mb-6 sm:mb-8" aria-label="Breadcrumb">
                <ol class="flex items-center space-x-2 text-sm">
                    <li>
                        <a href="{{ route('leave.index') }}" class="text-gray-500 hover:text-gray-700 transition-colors">
                            Pengajuan
                        </a>
                    </li>
                    <li>
                        <svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                clip-rule="evenodd" />
                        </svg>
                    </li>
                    <li>
                        <span class="font-medium text-gray-900">Buat Pengajuan</span>
                    </li>
                </ol>
            </nav>

            <!-- Main Card -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200">
                <!-- Header -->
                <div class="px-6 sm:px-8 py-6 border-b border-gray-100">
                    <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Buat Pengajuan Baru</h1>
                    <p class="mt-2 text-sm text-gray-600">Lengkapi formulir di bawah untuk mengajukan izin, sakit, atau
                        dinas</p>
                </div>

                <!-- Form -->
                <form action="{{ route('leave.store') }}" method="POST" enctype="multipart/form-data"
                    class="px-6 sm:px-8 py-8">
                    @csrf

                    <div class="space-y-8">
                        <!-- Type Selection -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-900 mb-3">
                                Jenis Pengajuan <span class="text-red-600">*</span>
                            </label>
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                                <label class="relative flex cursor-pointer">
                                    <input type="radio" name="type" value="leave"
                                        {{ old('type') === 'leave' ? 'checked' : '' }} class="peer sr-only" required>
                                    <div
                                        class="flex-1 flex flex-col items-center px-4 py-5 rounded-xl border-2 border-gray-200 bg-white transition-all peer-checked:border-blue-600 peer-checked:bg-blue-50 peer-checked:ring-2 peer-checked:ring-blue-100 hover:border-gray-300">
                                        <div
                                            class="w-12 h-12 rounded-full bg-blue-100 flex items-center justify-center mb-3 peer-checked:bg-blue-600 transition-colors">
                                            <svg class="w-6 h-6 text-blue-600 peer-checked:text-white transition-colors"
                                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                            </svg>
                                        </div>
                                        <span class="text-sm font-semibold text-gray-900">Izin</span>
                                        <span class="text-xs text-gray-500 mt-1 text-center">Keperluan pribadi</span>
                                    </div>
                                </label>

                                <label class="relative flex cursor-pointer">
                                    <input type="radio" name="type" value="sick"
                                        {{ old('type') === 'sick' ? 'checked' : '' }} class="peer sr-only">
                                    <div
                                        class="flex-1 flex flex-col items-center px-4 py-5 rounded-xl border-2 border-gray-200 bg-white transition-all peer-checked:border-red-600 peer-checked:bg-red-50 peer-checked:ring-2 peer-checked:ring-red-100 hover:border-gray-300">
                                        <div
                                            class="w-12 h-12 rounded-full bg-red-100 flex items-center justify-center mb-3 peer-checked:bg-red-600 transition-colors">
                                            <svg class="w-6 h-6 text-red-600 peer-checked:text-white transition-colors"
                                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                            </svg>
                                        </div>
                                        <span class="text-sm font-semibold text-gray-900">Sakit</span>
                                        <span class="text-xs text-gray-500 mt-1 text-center">Kondisi kesehatan</span>
                                    </div>
                                </label>

                                <label class="relative flex cursor-pointer">
                                    <input type="radio" name="type" value="business_trip"
                                        {{ old('type') === 'business_trip' ? 'checked' : '' }} class="peer sr-only">
                                    <div
                                        class="flex-1 flex flex-col items-center px-4 py-5 rounded-xl border-2 border-gray-200 bg-white transition-all peer-checked:border-purple-600 peer-checked:bg-purple-50 peer-checked:ring-2 peer-checked:ring-purple-100 hover:border-gray-300">
                                        <div
                                            class="w-12 h-12 rounded-full bg-purple-100 flex items-center justify-center mb-3 peer-checked:bg-purple-600 transition-colors">
                                            <svg class="w-6 h-6 text-purple-600 peer-checked:text-white transition-colors"
                                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                        <span class="text-sm font-semibold text-gray-900">Dinas</span>
                                        <span class="text-xs text-gray-500 mt-1 text-center">Tugas luar kota</span>
                                    </div>
                                </label>
                            </div>
                            @error('type')
                                <p class="mt-3 text-sm text-red-600 flex items-center">
                                    <svg class="w-4 h-4 mr-1.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Date Range -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-900 mb-3">
                                Periode Waktu <span class="text-red-600">*</span>
                            </label>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-medium text-gray-600 mb-2">Tanggal Mulai</label>
                                    <input type="date" name="start_date" value="{{ old('start_date') }}"
                                        class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all @error('start_date') border-red-300 focus:ring-red-500 @enderror"
                                        required>
                                    @error('start_date')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-xs font-medium text-gray-600 mb-2">Tanggal Selesai</label>
                                    <input type="date" name="end_date" value="{{ old('end_date') }}"
                                        class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all @error('end_date') border-red-300 focus:ring-red-500 @enderror"
                                        required>
                                    @error('end_date')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Reason -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-900 mb-3">
                                Keterangan <span class="text-red-600">*</span>
                            </label>
                            <textarea name="reason" rows="5"
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all resize-none @error('reason') border-red-300 focus:ring-red-500 @enderror"
                                placeholder="Jelaskan secara detail alasan pengajuan Anda..." maxlength="1000" required>{{ old('reason') }}</textarea>
                            @error('reason')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-2 text-xs text-gray-500">Maksimal 1000 karakter</p>
                        </div>

                        <!-- Attachment -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-900 mb-3">
                                Dokumen Pendukung
                                <span class="text-xs font-normal text-gray-500 ml-1">(Opsional)</span>
                            </label>

                            <div class="relative">
                                <input type="file" name="attachment" id="file-upload" accept=".pdf,.jpg,.jpeg,.png"
                                    class="hidden" onchange="displayFileName(this)">
                                <label for="file-upload"
                                    class="flex flex-col items-center justify-center w-full px-6 py-8 border-2 border-dashed border-gray-300 rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100 transition-all @error('attachment') border-red-300 bg-red-50 @enderror">
                                    <svg class="w-10 h-10 text-gray-400 mb-3" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                    </svg>
                                    <p class="text-sm font-medium text-gray-700">
                                        <span class="text-blue-600">Klik untuk upload</span> atau drag & drop
                                    </p>
                                    <p class="text-xs text-gray-500 mt-1">PDF, JPG, PNG hingga 2MB</p>
                                </label>
                            </div>

                            <div id="file-info" class="hidden mt-3 p-3 bg-green-50 border border-green-200 rounded-lg">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-2">
                                        <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        <div>
                                            <p id="file-name" class="text-sm font-medium text-green-900"></p>
                                            <p id="file-size" class="text-xs text-green-700"></p>
                                        </div>
                                    </div>
                                    <button type="button" onclick="clearFile()"
                                        class="text-green-600 hover:text-green-800">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>
                            </div>

                            @error('attachment')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror

                            <div class="mt-3 p-3 bg-amber-50 border border-amber-200 rounded-lg">
                                <p class="text-xs text-amber-800 flex items-start">
                                    <svg class="w-4 h-4 mr-2 mt-0.5 flex-shrink-0" fill="currentColor"
                                        viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    <span>Untuk pengajuan <strong>sakit</strong>, wajib melampirkan surat keterangan
                                        dokter</span>
                                </p>
                            </div>
                        </div>

                        <!-- Info Box -->
                        <div class="bg-blue-50 border border-blue-100 rounded-xl p-5">
                            <div class="flex items-start space-x-3">
                                <div class="flex-shrink-0">
                                    <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <h4 class="text-sm font-semibold text-blue-900 mb-2">Ketentuan Pengajuan</h4>
                                    <ul class="space-y-1.5 text-sm text-blue-800">
                                        <li class="flex items-start">
                                            <span class="mr-2">•</span>
                                            <span>Pengajuan <strong>Dinas Luar Kota</strong> tidak memotong tunjangan
                                                transportasi</span>
                                        </li>
                                        <li class="flex items-start">
                                            <span class="mr-2">•</span>
                                            <span>Proses persetujuan memerlukan waktu 1-2 hari kerja</span>
                                        </li>
                                        <li class="flex items-start">
                                            <span class="mr-2">•</span>
                                            <span>Notifikasi akan dikirim setelah pengajuan diproses</span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div
                        class="flex flex-col-reverse sm:flex-row justify-between items-center gap-3 mt-8 pt-6 border-t border-gray-100">
                        <a href="{{ route('leave.index') }}"
                            class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-3 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-all">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                            Batal
                        </a>
                        <button type="submit"
                            class="w-full sm:w-auto inline-flex items-center justify-center px-8 py-3 border border-transparent text-sm font-semibold rounded-lg text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all shadow-sm hover:shadow-md">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7" />
                            </svg>
                            Ajukan Sekarang
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function displayFileName(input) {
            const fileInfo = document.getElementById('file-info');
            const fileName = document.getElementById('file-name');
            const fileSize = document.getElementById('file-size');

            if (input.files && input.files[0]) {
                const file = input.files[0];
                const size = (file.size / 1024 / 1024).toFixed(2);

                fileName.textContent = file.name;
                fileSize.textContent = `${size} MB`;
                fileInfo.classList.remove('hidden');
            }
        }

        function clearFile() {
            const fileInput = document.getElementById('file-upload');
            const fileInfo = document.getElementById('file-info');

            fileInput.value = '';
            fileInfo.classList.add('hidden');
        }
    </script>
@endsection
