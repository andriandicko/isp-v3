<!-- Mobile Bottom Navigation Bar -->
<nav class="lg:hidden fixed bottom-0 left-0 right-0 z-50 bg-white border-t border-slate-200 shadow-lg">
    <div class="flex items-center justify-around h-16 px-2">
        <!-- Home -->
        <a href="{{ route('dashboard') }}"
            class="flex flex-col items-center justify-center flex-1 py-2 px-1 transition-all duration-200 {{ request()->routeIs('dashboard') ? 'text-blue-600' : 'text-slate-500 hover:text-blue-600' }}">
            <div class="relative">
                <svg class="w-6 h-6 mb-1" fill="{{ request()->routeIs('dashboard') ? 'currentColor' : 'none' }}"
                    stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                    </path>
                </svg>
                @if (request()->routeIs('dashboard'))
                    <div
                        class="absolute -bottom-1 left-1/2 transform -translate-x-1/2 w-1 h-1 bg-blue-600 rounded-full">
                    </div>
                @endif
            </div>
            <span class="text-xs font-medium">Home</span>
        </a>

        <!-- Berita/Tagihan -->
        <a href="{{ route('billings.index') }}"
            class="flex flex-col items-center justify-center flex-1 py-2 px-1 transition-all duration-200 {{ request()->routeIs('billings.*') ? 'text-blue-600' : 'text-slate-500 hover:text-blue-600' }}">
            <div class="relative">
                <svg class="w-6 h-6 mb-1" fill="{{ request()->routeIs('billings.*') ? 'currentColor' : 'none' }}"
                    stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                    </path>
                </svg>
                @if (request()->routeIs('billings.*'))
                    <div
                        class="absolute -bottom-1 left-1/2 transform -translate-x-1/2 w-1 h-1 bg-blue-600 rounded-full">
                    </div>
                @endif
            </div>
            <span class="text-xs font-medium">Tagihan</span>
        </a>

        <!-- Kartu/Tickets (Center with special styling) -->
        <a href="{{ route('tickets.index') }}"
            class="flex flex-col items-center justify-center flex-1 py-2 px-1 -mt-6 transition-all duration-200">
            <div
                class="w-14 h-14 rounded-full {{ request()->routeIs('tickets.*') ? 'bg-gradient-to-br from-blue-500 to-blue-600' : 'bg-gradient-to-br from-blue-400 to-blue-500' }} shadow-lg shadow-blue-500/30 flex items-center justify-center mb-1 transform hover:scale-105 transition-transform duration-200">
                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 11-4 0V7a2 2 0 00-2-2H5z">
                    </path>
                </svg>
            </div>
            <span
                class="text-xs font-medium {{ request()->routeIs('tickets.*') ? 'text-blue-600' : 'text-slate-500' }}">Tiket</span>
        </a>

        <!-- Payments (only visible for admin/master) -->
        @hasrole('admin|master')
            <a href="{{ route('payments.index') }}"
                class="flex flex-col items-center justify-center flex-1 py-2 px-1 transition-all duration-200 {{ request()->routeIs('payments.*') ? 'text-blue-600' : 'text-slate-500 hover:text-blue-600' }}">
                <div class="relative">
                    <svg class="w-6 h-6 mb-1" fill="{{ request()->routeIs('payments.*') ? 'currentColor' : 'none' }}"
                        stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z">
                        </path>
                    </svg>
                    @if (request()->routeIs('payments.*'))
                        <div
                            class="absolute -bottom-1 left-1/2 transform -translate-x-1/2 w-1 h-1 bg-blue-600 rounded-full">
                        </div>
                    @endif
                </div>
                <span class="text-xs font-medium">Bayar</span>
            </a>
        @else
            <!-- For non-admin users, show Profile -->
            <a href="#" onclick="toggleUserMenu(); return false;"
                class="flex flex-col items-center justify-center flex-1 py-2 px-1 transition-all duration-200 text-slate-500 hover:text-blue-600">
                <div class="relative">
                    <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                        </path>
                    </svg>
                </div>
                <span class="text-xs font-medium">FAQ</span>
            </a>
        @endrole

        <!-- Profile -->
        <a href="#" onclick="toggleUserMenu(); return false;"
            class="flex flex-col items-center justify-center flex-1 py-2 px-1 transition-all duration-200 text-slate-500 hover:text-blue-600">
            <div class="relative">
                <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
            </div>
            <span class="text-xs font-medium">Profil</span>
        </a>
    </div>
</nav>
