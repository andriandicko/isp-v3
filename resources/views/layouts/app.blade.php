<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    {{-- Viewport Fix untuk iPhone --}}
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name') }}</title>

    {{-- Fonts & CSS --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    
    {{-- Alpine JS --}}
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    
    <style>
        [x-cloak] { display: none !important; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        
        /* Custom Scrollbar */
        ::-webkit-scrollbar { width: 5px; height: 5px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        
        .leaflet-container { z-index: 0; }

        /* FIX IPHONE BLANK PAGE: Gunakan dvh (Dynamic Viewport Height) */
        .h-screen-safe {
            height: 100vh; /* Fallback */
            height: 100dvh; /* Modern Mobile Browsers */
        }
    </style>
    @stack('styles')
</head>

<body class="bg-slate-50 text-slate-800 antialiased">
    
    {{-- Wrapper Utama: Ganti h-screen jadi h-screen-safe --}}
    <div class="flex h-screen-safe overflow-hidden" x-data="{ sidebarOpen: false }">
        
        {{-- Overlay Mobile Sidebar --}}
        <div x-show="sidebarOpen" 
             x-cloak
             x-transition:enter="transition-opacity ease-linear duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-linear duration-300"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 bg-slate-900/60 z-[90] lg:hidden backdrop-blur-sm"
             @click="sidebarOpen = false">
        </div>

        {{-- Sidebar Include --}}
        @include('layouts.sidebar')

        {{-- Konten Utama --}}
        <div class="flex-1 flex flex-col min-w-0 overflow-hidden relative">
            
            @include('layouts.topbar')

            {{-- Main Content: Pastikan overflow-y-auto agar bisa discroll --}}
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-slate-50 pb-24 lg:pb-8 relative z-0">
                
                {{-- Flash Messages --}}
                @if (session('success'))
                    <div class="mx-4 mt-6 lg:mx-8 p-4 bg-green-50 border-l-4 border-green-500 text-green-700 rounded-r-lg shadow-sm flex items-center animate-pulse">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        {{ session('success') }}
                    </div>
                @endif
                @if (session('error'))
                    <div class="mx-4 mt-6 lg:mx-8 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 rounded-r-lg shadow-sm flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        {{ session('error') }}
                    </div>
                @endif

                {{-- Content Yield --}}
                <div class="px-4 py-6 lg:px-8">
                    @yield('content')
                </div>
            </main>
        </div>
        
        @include('layouts.bottom-nav')

    </div> 

    @stack('modals')

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://unpkg.com/feather-icons"></script>
    <script>
        if (typeof feather !== 'undefined') feather.replace();
    </script>
    @stack('scripts')
</body>
</html>