@extends('layouts.app')

@section('title', 'Input Tiket Gangguan')

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">
    <style>
        /* Custom Style agar TomSelect mirip dengan Input Tailwind Anda */
        .ts-control {
            border-radius: 0.5rem !important; /* rounded-lg */
            padding: 0.625rem 0.75rem !important; /* py-2.5 px-3 */
            border-color: #d1d5db !important; /* border-gray-300 */
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05) !important;
            background-color: #ffffff;
            font-size: 0.875rem; /* text-sm */
        }
        .ts-control.focus {
            border-color: #6366f1 !important; /* indigo-500 */
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1) !important;
        }
        .ts-dropdown {
            border-radius: 0.5rem;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            border: 1px solid #e5e7eb;
            overflow: hidden;
            z-index: 50;
        }
        .ts-dropdown .option {
            padding: 8px 12px;
        }
        .ts-dropdown .active {
            background-color: #e0e7ff; /* indigo-50 */
            color: #4338ca; /* indigo-700 */
        }
    </style>
@endpush

@section('content')
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        
        <div class="mb-6 flex justify-between items-center">
            <div>
                <div class="flex items-center gap-2 text-sm text-gray-500 mb-1">
                    <a href="{{ route('tickets.index') }}" class="hover:text-indigo-600 transition">Tiket</a>
                    <span>/</span>
                    <span>Input Baru</span>
                </div>
                <h2 class="text-2xl font-bold text-gray-900">Input Laporan Gangguan</h2>
            </div>
            <a href="{{ route('tickets.index') }}" class="text-gray-500 hover:text-gray-700 flex items-center font-medium transition text-sm">
                <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Kembali
            </a>
        </div>

        <div class="bg-indigo-50 border-l-4 border-indigo-500 p-4 mb-6 rounded-r-lg shadow-sm flex items-start">
            <div class="flex-shrink-0">
                <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
            </div>
            <div class="ml-3">
                <p class="text-sm text-indigo-700">
                    Anda menginput sebagai: <span class="font-bold">{{ auth()->user()->name }}</span> 
                    ({{ auth()->user()->getRoleNames()->first() }})
                </p>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-24">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                <h3 class="text-lg font-semibold text-gray-800">Detail Keluhan Pelanggan</h3>
                <p class="text-xs text-gray-500 mt-0.5">Cari data pelanggan berdasarkan Nama, Email, HP, atau No ODP.</p>
            </div>
            
            <form action="{{ route('tickets.store') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-6">
                @csrf

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Cari Pelanggan <span class="text-red-500">*</span></label>
                    
                    <select id="select-customer" name="customer_id" placeholder="Ketik Nama, Email, No HP, atau ODP..." autocomplete="off" required>
                        <option value="">Cari pelanggan...</option>
                        @foreach($customers as $c)
                            <option value="{{ $c->id }}" 
                                data-email="{{ $c->user->email }}"
                                data-phone="{{ $c->user->phone }}"
                                data-odp="{{ $c->no_odp ?? '-' }}"
                                data-company="{{ $c->company_name ?? 'Personal' }}">
                                {{ $c->user->name }}
                            </option>
                        @endforeach
                    </select>
                    <p class="text-xs text-gray-500 mt-1">Tips: Ketik 4 digit terakhir No HP atau Kode ODP untuk pencarian cepat.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Gangguan <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <select name="issue_type" class="block w-full rounded-lg border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 py-2.5 pl-3 pr-10 appearance-none bg-white">
                                <option value="slow">Koneksi Lambat (Slow)</option>
                                <option value="intermittent">Putus - Nyambung (Intermiten)</option>
                                <option value="no_internet">Mati Total (LOS / No Internet)</option>
                                <option value="device">Masalah Perangkat (Router/Kabel)</option>
                                <option value="other">Lainnya</option>
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Judul Masalah <span class="text-red-500">*</span></label>
                        <input type="text" name="subject" value="{{ old('subject') }}" required 
                            class="block w-full rounded-lg border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 placeholder-gray-400 transition py-2.5 px-3" 
                            placeholder="Cth: LOS Merah di Modem">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi / Kronologi <span class="text-red-500">*</span></label>
                    <textarea name="description" rows="4" required 
                        class="block w-full rounded-lg border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 placeholder-gray-400 transition p-3" 
                        placeholder="Jelaskan detail laporan dari pelanggan...">{{ old('description') }}</textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Foto Bukti (Dari Pelanggan)</label>
                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:bg-gray-50 transition cursor-pointer relative bg-white group">
                        <input id="file-upload" name="photo" type="file" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" accept="image/*" onchange="document.getElementById('file-name').innerText = this.files[0].name">
                        <div class="space-y-1 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400 group-hover:text-indigo-500 transition" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <div class="flex text-sm text-gray-600 justify-center">
                                <span class="relative cursor-pointer rounded-md font-medium text-indigo-600 hover:text-indigo-500">Upload file</span>
                            </div>
                            <p class="text-xs text-gray-500">PNG, JPG up to 2MB</p>
                            <p id="file-name" class="text-xs text-indigo-600 font-semibold mt-2"></p>
                        </div>
                    </div>
                </div>

                <div class="pt-4 border-t border-gray-100 mt-4">
                    <button type="submit" class="w-full bg-indigo-600 text-white font-bold py-3 px-6 rounded-xl hover:bg-indigo-700 transition shadow-lg shadow-indigo-200 transform active:scale-95 flex justify-center items-center text-base">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                        Buat Tiket
                    </button>
                </div>
                
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
    
    <script>
        // Inisialisasi TomSelect
        new TomSelect("#select-customer",{
            create: false,
            sortField: {
                field: "text",
                direction: "asc"
            },
            // Field apa saja yang mau dicari?
            searchField: ['text', 'email', 'phone', 'odp', 'company'],
            // Custom Render agar tampilan dropdown informatif
            render: {
                option: function(data, escape) {
                    return `<div class="flex flex-col py-1">
                                <div class="flex justify-between items-center">
                                    <span class="font-bold text-gray-800">${escape(data.text)}</span>
                                    <span class="text-xs text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded-full">${escape(data.odp || 'No ODP')}</span>
                                </div>
                                <div class="text-xs text-gray-500 mt-0.5">
                                    ${escape(data.email)} | ${escape(data.phone)}
                                </div>
                                <div class="text-[10px] text-gray-400">
                                    ${escape(data.company)}
                                </div>
                            </div>`;
                },
                item: function(data, escape) {
                    return `<div>${escape(data.text)} <span class="text-gray-400 text-xs">(${escape(data.odp || '-')})</span></div>`;
                }
            }
        });
    </script>
@endpush