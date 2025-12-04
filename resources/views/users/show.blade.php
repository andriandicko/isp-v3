@extends('layouts.app')

@section('title', 'Detail User')

@section('content')
    <div class="min-h-screen bg-gray-50 py-6">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header Section -->
            <div class="mb-6">
                <div class="flex items-center gap-3 mb-4">
                    <a href="{{ route('users.index') }}"
                        class="inline-flex items-center justify-center w-10 h-10 rounded-lg border border-gray-300 bg-white hover:bg-gray-50 transition-colors">
                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                    </a>
                    <div class="flex-1">
                        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Detail User</h1>
                        <p class="text-sm text-gray-600 mt-0.5">Informasi lengkap user</p>
                    </div>
                    <div class="hidden sm:flex gap-2">
                        <a href="{{ route('users.edit', $user) }}"
                            class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 transition-colors shadow-sm">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            Edit
                        </a>
                    </div>
                </div>
            </div>

            <!-- User Profile Card -->
            <div class="bg-gradient-to-r from-blue-600 to-blue-700 rounded-xl shadow-lg overflow-hidden mb-6">
                <div class="px-6 py-8">
                    <div class="flex flex-col sm:flex-row sm:items-center gap-6">
                        <!-- Avatar -->
                        <div class="flex-shrink-0 mx-auto sm:mx-0">
                            <div
                                class="h-24 w-24 bg-white bg-opacity-20 rounded-full flex items-center justify-center ring-4 ring-white ring-opacity-20">
                                <span class="text-white font-bold text-3xl">
                                    {{ strtoupper(substr($user->name, 0, 2)) }}
                                </span>
                            </div>
                        </div>

                        <!-- User Info -->
                        <div class="flex-1 text-center sm:text-left">
                            <h2 class="text-2xl font-bold text-white mb-1">{{ $user->name }}</h2>
                            <p class="text-blue-100 mb-3">{{ $user->email }}</p>

                            <!-- Roles -->
                            @if ($user->roles->isNotEmpty())
                                <div class="flex flex-wrap justify-center sm:justify-start gap-2">
                                    @foreach ($user->roles as $role)
                                        <span
                                            class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-white bg-opacity-20 text-white backdrop-blur-sm">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                            </svg>
                                            {{ ucfirst($role->name) }}
                                        </span>
                                    @endforeach
                                </div>
                            @endif
                        </div>

                        <!-- Verification Badge -->
                        <div class="flex-shrink-0 mx-auto sm:mx-0">
                            @if ($user->email_verified_at)
                                <div
                                    class="bg-green-500 bg-opacity-20 backdrop-blur-sm rounded-lg px-4 py-3 text-center border border-green-400 border-opacity-30">
                                    <svg class="w-8 h-8 text-white mx-auto mb-1" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <p class="text-xs font-medium text-white">Terverifikasi</p>
                                </div>
                            @else
                                <div
                                    class="bg-amber-500 bg-opacity-20 backdrop-blur-sm rounded-lg px-4 py-3 text-center border border-amber-400 border-opacity-30">
                                    <svg class="w-8 h-8 text-white mx-auto mb-1" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                    </svg>
                                    <p class="text-xs font-medium text-white">Belum Verifikasi</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Mobile Actions -->
            <div class="sm:hidden mb-6">
                <a href="{{ route('users.edit', $user) }}"
                    class="w-full inline-flex items-center justify-center px-4 py-2.5 rounded-lg text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 transition-colors shadow-sm">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Edit User
                </a>
            </div>

            <!-- Information Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                <!-- Contact Information Card -->
                <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                    <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            Informasi Kontak
                        </h3>
                    </div>
                    <div class="px-6 py-4 space-y-4">
                        <!-- Email -->
                        <div>
                            <label class="text-xs font-medium text-gray-500 uppercase tracking-wider">Email</label>
                            <div class="mt-1 flex items-center">
                                <svg class="w-5 h-5 text-gray-400 mr-2" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                                <span class="text-sm text-gray-900">{{ $user->email }}</span>
                            </div>
                        </div>

                        <!-- Phone -->
                        <div>
                            <label class="text-xs font-medium text-gray-500 uppercase tracking-wider">No. Telepon</label>
                            <div class="mt-1 flex items-center">
                                <svg class="w-5 h-5 text-gray-400 mr-2" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                </svg>
                                <span class="text-sm text-gray-900">{{ $user->phone ?? '-' }}</span>
                            </div>
                        </div>

                        <!-- Email Verification Status -->
                        <div>
                            <label class="text-xs font-medium text-gray-500 uppercase tracking-wider">Status Verifikasi
                                Email</label>
                            <div class="mt-1">
                                @if ($user->email_verified_at)
                                    <span
                                        class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        Terverifikasi
                                    </span>
                                    <p class="text-xs text-gray-500 mt-1">
                                        {{ $user->email_verified_at->format('d M Y, H:i') }}</p>
                                @else
                                    <span
                                        class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                        Belum Terverifikasi
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Account Information Card -->
                <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                    <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Informasi Akun
                        </h3>
                    </div>
                    <div class="px-6 py-4 space-y-4">
                        <!-- Created At -->
                        <div>
                            <label class="text-xs font-medium text-gray-500 uppercase tracking-wider">Terdaftar
                                Sejak</label>
                            <div class="mt-1 flex items-center">
                                <svg class="w-5 h-5 text-gray-400 mr-2" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <div>
                                    <span
                                        class="text-sm text-gray-900 block">{{ $user->created_at->format('d M Y') }}</span>
                                    <span class="text-xs text-gray-500">{{ $user->created_at->format('H:i') }} WIB</span>
                                </div>
                            </div>
                        </div>

                        <!-- Updated At -->
                        <div>
                            <label class="text-xs font-medium text-gray-500 uppercase tracking-wider">Terakhir
                                Diupdate</label>
                            <div class="mt-1 flex items-center">
                                <svg class="w-5 h-5 text-gray-400 mr-2" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                </svg>
                                <div>
                                    <span
                                        class="text-sm text-gray-900 block">{{ $user->updated_at->format('d M Y') }}</span>
                                    <span class="text-xs text-gray-500">{{ $user->updated_at->format('H:i') }} WIB</span>
                                </div>
                            </div>
                        </div>

                        <!-- Account Age -->
                        <div>
                            <label class="text-xs font-medium text-gray-500 uppercase tracking-wider">Durasi Akun</label>
                            <div class="mt-1 flex items-center">
                                <svg class="w-5 h-5 text-gray-400 mr-2" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span class="text-sm text-gray-900">{{ $user->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Roles Card (Full Width) -->
            @if ($user->roles->isNotEmpty())
                <div class="bg-white rounded-lg shadow-sm overflow-hidden mb-6">
                    <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                            Hak Akses & Permissions
                        </h3>
                    </div>
                    <div class="px-6 py-4">
                        <div class="flex flex-wrap gap-2">
                            @foreach ($user->roles as $role)
                                <div
                                    class="inline-flex items-center px-4 py-2 rounded-lg bg-blue-50 border border-blue-200">
                                    <svg class="w-4 h-4 text-blue-600 mr-2" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                    </svg>
                                    <span class="text-sm font-medium text-blue-900">{{ ucfirst($role->name) }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            <!-- Danger Zone -->
            <div class="bg-white rounded-lg shadow-sm overflow-hidden border-2 border-red-200">
                <div class="px-6 py-4 bg-red-50 border-b border-red-200">
                    <h3 class="text-lg font-semibold text-red-900 flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        Danger Zone
                    </h3>
                    <p class="mt-1 text-sm text-red-700">Tindakan ini bersifat permanen dan tidak dapat dibatalkan</p>
                </div>
                <div class="px-6 py-5">
                    <div class="sm:flex sm:items-center sm:justify-between">
                        <div class="mb-4 sm:mb-0">
                            <h4 class="text-sm font-medium text-gray-900">Hapus User</h4>
                            <p class="text-sm text-gray-600 mt-1">Semua data user akan dihapus secara permanen dari sistem
                            </p>
                        </div>
                        <form action="{{ route('users.destroy', $user) }}" method="POST"
                            onsubmit="return confirm('⚠️ PERINGATAN!\n\nApakah Anda yakin ingin menghapus user:\n{{ $user->name }} ({{ $user->email }})\n\nSemua data akan hilang permanen dan tidak dapat dikembalikan!')">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2.5 border-2 border-red-600 rounded-lg text-sm font-semibold text-red-700 bg-white hover:bg-red-600 hover:text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-all">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                                Hapus User
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
