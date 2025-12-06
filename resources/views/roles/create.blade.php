@extends('layouts.app')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-6">
            <a href="{{ route('roles.index') }}" class="text-gray-500 hover:text-gray-700 flex items-center mb-2">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Kembali ke Daftar Role
            </a>
            <h1 class="text-2xl font-bold text-gray-900">Buat Role Baru</h1>
        </div>

        <form action="{{ route('roles.store') }}" method="POST">
            @csrf
            
            <div class="bg-white p-6 rounded-xl shadow-sm mb-6">
                <div class="max-w-md">
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nama Role</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" placeholder="Contoh: Admin Gudang" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 transition" required>
                </div>
            </div>

            <div class="bg-white p-6 rounded-xl shadow-sm mb-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/></svg>
                    Atur Hak Akses
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($permissions as $groupName => $perms)
                        {{-- Logika Penamaan Modul dari Controller --}}
                        @php
                            $displayTitle = $moduleNames[$groupName] ?? ucwords(str_replace(['-', '_'], ' ', $groupName)) . ' Module';
                        @endphp

                        <div class="border border-gray-200 rounded-lg p-4 hover:border-blue-300 transition-colors bg-gray-50">
                            <div class="flex items-center justify-between mb-3 pb-2 border-b border-gray-200">
                                <h4 class="font-bold text-gray-800 capitalize">{{ $displayTitle }}</h4>
                                <button type="button" onclick="toggleGroup('{{ $groupName }}')" class="text-xs text-blue-600 hover:underline">Pilih Semua</button>
                            </div>
                            
                            <div class="space-y-2">
                                @foreach($perms as $permission)
                                    <label class="flex items-center space-x-3 cursor-pointer group">
                                        {{-- Di create, checkbox default tidak dicentang --}}
                                        <input type="checkbox" 
                                               name="permissions[]" 
                                               value="{{ $permission->name }}" 
                                               class="perm-{{ $groupName }} form-checkbox h-4 w-4 text-blue-600 rounded border-gray-300 focus:ring-blue-500">
                                        <span class="text-sm text-gray-700 group-hover:text-gray-900">
                                            {{ ucwords(str_replace([$groupName . '.', '_', '-'], ['', ' ', ' '], $permission->name)) }}
                                        </span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="flex justify-end gap-3">
                <a href="{{ route('roles.index') }}" class="px-6 py-3 bg-white border border-gray-300 text-gray-700 font-bold rounded-lg hover:bg-gray-50">Batal</a>
                <button type="submit" class="px-6 py-3 bg-blue-600 text-white font-bold rounded-lg shadow hover:bg-blue-700 transition transform hover:-translate-y-0.5">
                    Simpan Role Baru
                </button>
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