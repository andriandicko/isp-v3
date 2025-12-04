@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50/50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        {{-- Header Section --}}
        <div class="mb-8 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 tracking-tight">Buat Role Baru</h1>
                <p class="text-gray-500 text-sm mt-1">Tambahkan peran baru dan atur hak aksesnya.</p>
            </div>
            <a href="{{ route('roles.index') }}" class="inline-flex items-center text-sm font-medium text-gray-500 hover:text-blue-600 transition-colors group">
                <svg class="w-4 h-4 mr-1 transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Kembali
            </a>
        </div>

        <form action="{{ route('roles.store') }}" method="POST">
            @csrf
            
            {{-- SECTION 1: ROLE INFO --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-8">
                <div class="max-w-xl">
                    <label for="name" class="block text-sm font-bold text-gray-900 mb-2">Nama Role <span class="text-red-500">*</span></label>
                    <input type="text" 
                           name="name" 
                           id="name" 
                           class="w-full rounded-xl border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm py-3 px-4 text-gray-900 font-medium transition-all" 
                           placeholder="Contoh: Staff Gudang" 
                           autocomplete="off"
                           required 
                           autofocus>
                    <p class="text-xs text-gray-500 mt-2">Nama role harus unik. Hindari penggunaan simbol khusus.</p>
                </div>
            </div>

            {{-- SECTION 2: PERMISSIONS --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-8">
                <div class="p-6 border-b border-gray-100 bg-gray-50/30 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                    <div>
                        <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                            <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11.536 21H5v-4l2.293-2.293a11.728 11.728 0 011.085-3.085A6 6 0 1119.536 9h-.001z"/></svg>
                            Atur Hak Akses
                        </h3>
                        <p class="text-sm text-gray-500 mt-1">Centang modul-modul yang diizinkan untuk diakses oleh role ini.</p>
                    </div>
                    
                    {{-- Global Actions (Optional) --}}
                    {{-- <button type="button" class="text-sm text-blue-600 font-semibold hover:underline">Pilih Semua Permission</button> --}}
                </div>

                <div class="p-6 sm:p-8">
                    {{-- Grid Layout untuk Permission Groups --}}
                    {{-- Menggunakan Masonry-like grid agar lebih rapi --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($permissions as $groupName => $perms)
                            <div class="border border-gray-200 rounded-xl overflow-hidden hover:border-blue-400 hover:shadow-md transition-all duration-200 group bg-white">
                                {{-- Header Group --}}
                                <div class="px-4 py-3 bg-gray-50 border-b border-gray-200 flex justify-between items-center group-hover:bg-blue-50/50 transition-colors">
                                    <h4 class="font-bold text-gray-800 capitalize text-sm flex items-center gap-2">
                                        <span class="w-2 h-2 rounded-full bg-blue-500"></span>
                                        {{ $groupName }}
                                    </h4>
                                    <button type="button" onclick="toggleGroup('{{ $groupName }}')" class="text-[11px] font-bold text-blue-600 hover:text-blue-800 uppercase tracking-wider hover:underline focus:outline-none">
                                        Pilih Semua
                                    </button>
                                </div>
                                
                                {{-- List Checkboxes --}}
                                <div class="p-4 space-y-3 bg-white">
                                    @foreach($perms as $permission)
                                        <label class="flex items-start gap-3 cursor-pointer select-none group/item">
                                            <div class="flex items-center h-5 mt-0.5">
                                                <input type="checkbox" 
                                                       name="permissions[]" 
                                                       value="{{ $permission->name }}" 
                                                       class="perm-{{ $groupName }} w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500 focus:ring-offset-0 transition duration-150 ease-in-out cursor-pointer">
                                            </div>
                                            <div class="text-sm text-gray-600 group-hover/item:text-gray-900 transition-colors leading-snug">
                                                {{ ucwords(str_replace([$groupName . '.', '_', '-'], ['', ' ', ' '], $permission->name)) }}
                                            </div>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- FOOTER ACTION --}}
            <div class="flex items-center justify-end gap-3 sticky bottom-6 z-10">
                <div class="bg-white/80 backdrop-blur-sm p-2 rounded-2xl shadow-lg border border-gray-100 flex gap-3">
                    <a href="{{ route('roles.index') }}" class="px-6 py-3 rounded-xl text-gray-700 font-bold hover:bg-gray-100 transition-colors">
                        Batal
                    </a>
                    <button type="submit" class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl shadow-md hover:shadow-lg transition-all transform hover:-translate-y-0.5 flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Simpan Role
                    </button>
                </div>
            </div>

        </form>
    </div>
</div>

@push('scripts')
<script>
    function toggleGroup(groupName) {
        const checkboxes = document.querySelectorAll(`.perm-${groupName}`);
        const isAllChecked = Array.from(checkboxes).every(cb => cb.checked);
        
        checkboxes.forEach(cb => {
            cb.checked = !isAllChecked;
        });
    }
</script>
@endpush
@endsection