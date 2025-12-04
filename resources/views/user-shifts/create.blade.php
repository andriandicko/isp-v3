@extends('layouts.app')

@section('title', 'Jadwal Kerja Baru')

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">
    <style>
        /* Custom Style TomSelect agar senada dengan Tailwind */
        .ts-control {
            border-radius: 0.75rem !important; /* rounded-xl */
            padding: 0.75rem 1rem !important;
            border-color: #d1d5db !important;
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05) !important;
            font-size: 0.875rem !important;
        }
        .ts-control.focus {
            border-color: #6366f1 !important; /* indigo-500 */
            box-shadow: 0 0 0 2px rgba(99, 102, 241, 0.2) !important;
        }
        /* Style untuk Item yang dipilih (Tags) */
        .ts-control .item {
            background-color: #e0e7ff !important; /* indigo-100 */
            color: #4338ca !important; /* indigo-700 */
            border-radius: 0.5rem !important;
            font-weight: 600;
            padding: 2px 8px !important;
        }
        .ts-dropdown {
            border-radius: 0.75rem !important;
            overflow: hidden;
            border: 1px solid #e5e7eb;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            z-index: 50;
        }
        .ts-dropdown .option.active {
            background-color: #eff6ff;
            color: #4338ca;
        }
    </style>
@endpush

@section('content')
    <div class="min-h-screen bg-gray-50/50 py-8 pb-32">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="mb-6 flex items-center justify-between">
                <div>
                    <nav class="flex mb-1" aria-label="Breadcrumb">
                        <ol class="flex items-center space-x-2">
                            <li><a href="{{ route('user-shifts.index') }}" class="text-xs font-medium text-gray-500 hover:text-indigo-600 transition-colors">Jadwal</a></li>
                            <li><span class="text-gray-400 text-xs">/</span></li>
                            <li><span class="text-xs font-medium text-gray-900" aria-current="page">Buat Baru</span></li>
                        </ol>
                    </nav>
                    <h1 class="text-xl sm:text-2xl font-bold text-gray-900 tracking-tight">Assign Jadwal</h1>
                </div>
                <a href="{{ route('user-shifts.index') }}" class="hidden sm:flex items-center justify-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 transition-all">
                    Kembali
                </a>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50 flex items-center justify-between">
                    <div>
                        <h3 class="text-base font-bold text-gray-900">Formulir Penugasan</h3>
                    </div>
                    <a href="{{ route('user-shifts.index') }}" class="sm:hidden p-2 text-gray-400 hover:text-gray-600 bg-white rounded-lg border border-gray-200 shadow-sm">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </a>
                </div>
                
                <form action="{{ route('user-shifts.store') }}" method="POST" class="p-6 sm:p-8 space-y-6">
                    @csrf

                    <div class="space-y-2">
                        <label class="block text-sm font-bold text-gray-900">
                            Pilih Karyawan <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <select name="user_id" required class="block w-full py-3 pl-4 pr-10 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-xl bg-white shadow-sm">
                                <option value="">-- Cari nama karyawan --</option>
                                @foreach ($users as $user)
                                    @if (!$user->hasRole(['customer', 'korlap']))
                                        <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                            {{ $user->name }} ({{ ucfirst($user->getRoleNames()->first() ?? '-') }})
                                        </option>
                                    @endif
                                @endforeach
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="block text-sm font-bold text-gray-900">
                            Pilih Shift Kerja <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <select name="shift_id" id="shiftSelect" required class="block w-full py-3 pl-4 pr-10 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-xl bg-white shadow-sm">
                                <option value="">-- Pilih Waktu --</option>
                                @foreach ($shifts as $shift)
                                    <option value="{{ $shift->id }}" 
                                        data-time="{{ \Carbon\Carbon::parse($shift->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($shift->end_time)->format('H:i') }}"
                                        data-days="{{ implode(', ', array_map('ucfirst', $shift->days)) }}"
                                        {{ old('shift_id') == $shift->id ? 'selected' : '' }}>
                                        {{ $shift->name }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </div>
                        </div>

                        <div id="shiftInfo" class="hidden bg-indigo-50 border border-indigo-100 rounded-xl p-3 flex gap-3 animate-fade-in-up mt-2">
                            <div class="shrink-0 w-8 h-8 bg-indigo-100 rounded-lg flex items-center justify-center text-indigo-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                            <div>
                                <p class="text-xs font-bold text-indigo-900 uppercase">Detail Waktu</p>
                                <p class="text-sm text-indigo-800 font-medium" id="shiftTimeText"></p>
                                <p class="text-xs text-indigo-600 mt-0.5" id="shiftDaysText"></p>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="block text-sm font-bold text-gray-900">
                            Lokasi Kantor / Basecamp <span class="text-red-500">*</span>
                        </label>
                        
                        <select id="select-offices" name="office_ids[]" multiple placeholder="Pilih satu atau lebih kantor..." autocomplete="off" required>
                            <option value="">Pilih kantor...</option>
                            @foreach($offices as $office)
                                <option value="{{ $office->id }}" data-address="{{ $office->address }}">
                                    {{ $office->name }}
                                </option>
                            @endforeach
                        </select>
                        
                        <p class="text-xs text-gray-500 mt-1">
                            Bisa pilih lebih dari satu. Klik kolom untuk mencari atau menambah.
                        </p>
                        
                        @error('office_ids') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="block text-sm font-bold text-gray-900">Mulai Berlaku</label>
                            <input type="date" name="effective_date" value="{{ old('effective_date', date('Y-m-d')) }}" required class="block w-full px-4 py-3 border-gray-300 rounded-xl shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        </div>
                        
                        <div class="flex items-center pt-6">
                            <label class="flex items-center cursor-pointer">
                                <input type="checkbox" name="is_active" class="sr-only peer" checked>
                                <div class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                                <span class="ms-3 text-sm font-medium text-gray-900">Status Aktif</span>
                            </label>
                        </div>
                    </div>

                    <div class="fixed bottom-0 left-0 right-0 p-4 bg-white/95 backdrop-blur-sm border-t border-gray-200 md:static md:bg-transparent md:border-0 md:p-0 z-30 shadow-[0_-4px_20px_-1px_rgba(0,0,0,0.1)] md:shadow-none">
                        <div class="max-w-3xl mx-auto flex flex-col-reverse sm:flex-row justify-end gap-3">
                            <a href="{{ route('user-shifts.index') }}" class="w-full sm:w-auto inline-flex justify-center items-center px-6 py-3.5 border border-gray-300 shadow-sm text-sm font-medium rounded-xl text-gray-700 bg-white hover:bg-gray-50 focus:outline-none transition-all">
                                Batal
                            </a>
                            <button type="submit" class="w-full sm:w-auto inline-flex justify-center items-center px-6 py-3.5 border border-transparent text-sm font-bold rounded-xl text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none shadow-lg shadow-indigo-200 transition-all transform active:scale-95">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                Simpan Jadwal
                            </button>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // 1. Inisialisasi TomSelect untuk Kantor (Multiple)
        new TomSelect("#select-offices", {
            plugins: ['remove_button', 'checkbox_options'],
            create: false,
            placeholder: "Pilih kantor...",
            maxOptions: null,
            render: {
                option: function(data, escape) {
                    return '<div>' +
                            '<span class="font-bold block text-gray-800">' + escape(data.text) + '</span>' +
                            '<span class="text-xs text-gray-500">' + escape(data.address || '') + '</span>' +
                        '</div>';
                },
                item: function(data, escape) {
                    return '<div>' + escape(data.text) + '</div>';
                }
            }
        });

        // 2. Logic Shift Info Preview
        const shiftSelect = document.getElementById('shiftSelect');
        const shiftInfo = document.getElementById('shiftInfo');
        const timeText = document.getElementById('shiftTimeText');
        const daysText = document.getElementById('shiftDaysText');

        function updateInfo() {
            const selected = shiftSelect.options[shiftSelect.selectedIndex];
            if (shiftSelect.value) {
                timeText.textContent = selected.dataset.time;
                daysText.textContent = selected.dataset.days;
                shiftInfo.classList.remove('hidden');
            } else {
                shiftInfo.classList.add('hidden');
            }
        }

        shiftSelect.addEventListener('change', updateInfo);
        if(shiftSelect.value) updateInfo();
    });
</script>

<style>
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(5px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in-up { animation: fadeInUp 0.2s ease-out forwards; }
</style>
@endpush