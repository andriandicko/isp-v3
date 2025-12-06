<aside 
    class="fixed inset-y-0 left-0 z-[100] w-64 bg-white border-r border-slate-200 shadow-2xl transform transition-transform duration-300 ease-in-out lg:translate-x-0 lg:static lg:inset-0 flex flex-col h-screen"
    :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
    @click.away="sidebarOpen = false"
    @keydown.escape.window="sidebarOpen = false"
    x-data="{ logoutModalOpen: false }"
>
    
    <div class="h-16 flex items-center justify-between px-6 border-b border-slate-100 bg-white shrink-0">
        <div class="flex items-center gap-3">
            <div class="bg-indigo-600 p-1.5 rounded-lg shadow-sm flex-shrink-0">
                <img src="/logo.png" alt="SPD" class="w-6 h-6 object-contain invert brightness-0" 
                      onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                <svg class="w-6 h-6 text-white hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                </svg>
            </div>
            <span class="text-xl font-bold text-slate-800 tracking-tight whitespace-nowrap">{{ config('app.name') }}</span>
        </div>
        
        <button 
            type="button" 
            @click="sidebarOpen = false" 
            class="lg:hidden p-2 text-slate-400 hover:text-red-500 hover:bg-red-50 rounded-lg transition-colors focus:outline-none"
        >
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
    </div>

    <div class="p-4 border-b border-slate-100 bg-slate-50/50 shrink-0">
        <div class="flex items-center gap-3">
            <div class="flex-shrink-0">
                <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=4F46E5&color=fff" 
                     class="w-10 h-10 rounded-full ring-2 ring-white shadow-sm" 
                     alt="{{ auth()->user()->name }}">
            </div>
            <div class="overflow-hidden min-w-0">
                <p class="text-sm font-bold text-slate-800 truncate">{{ auth()->user()->name }}</p>
                <p class="text-xs text-slate-500 truncate">{{ auth()->user()->getRoleNames()->first() ?? 'Staff' }}</p>
            </div>
        </div>
    </div>

    <nav class="flex-1 px-3 lg:px-4 py-4 lg:py-6 space-y-3 overflow-y-auto custom-scrollbar pb-24">

        {{-- DASHBOARD --}}
        @can('dashboard.index')
            <a href="{{ route('dashboard') }}" 
               class="group relative flex items-center px-3 lg:px-4 py-2.5 lg:py-3 text-sm font-semibold rounded-xl transition-all duration-300 transform hover:scale-[1.02] {{ request()->routeIs('dashboard') ? 'bg-gradient-to-r from-blue-500 to-blue-600 text-white shadow-lg shadow-blue-500/25' : 'text-slate-700 hover:bg-white hover:shadow-md hover:shadow-slate-200/50' }}">
                <svg class="w-5 h-5 mr-3 lg:mr-4 {{ request()->routeIs('dashboard') ? 'text-white' : 'text-slate-500 group-hover:text-blue-600' }} transition-colors duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                </svg>
                <span class="flex-1">Dashboard</span>
                @if (request()->routeIs('dashboard'))
                    <div class="absolute right-0 top-1/2 transform -translate-y-1/2 w-1 h-8 bg-white rounded-l-full"></div>
                @endif
            </a>
        @endcan

        {{-- ABSENSI GROUP --}}
        {{-- Muncul jika punya salah satu permission di bawah ini --}}
        @canany(['attendance.index', 'leave.index', 'shifts.index', 'user-shifts.index', 'offices.index'])
        <div class="space-y-1" x-data="{ open: {{ request()->routeIs('attendance.*') || request()->routeIs('leave.*') || request()->routeIs('shifts.*') || request()->routeIs('user-shifts.*') || request()->routeIs('offices.*') ? 'true' : 'false' }} }">
            <button @click="open = !open" class="w-full group flex items-center justify-between px-3 lg:px-4 py-2.5 lg:py-3 text-sm font-semibold rounded-xl transition-all duration-300 {{ request()->routeIs('attendance.*') || request()->routeIs('leave.*') ? 'bg-purple-50 text-purple-700' : 'text-slate-700 hover:bg-white hover:shadow-md' }}">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-3 lg:mr-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span>Absensi</span>
                </div>
                <svg class="w-4 h-4 transition-transform duration-300" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
            </button>
            <div x-show="open" x-collapse class="ml-4 space-y-1">
                @can('attendance.index')
                    <a href="{{ route('attendance.index') }}" class="flex items-center px-3 py-2 text-sm rounded-lg transition-all {{ request()->routeIs('attendance.*') ? 'bg-purple-100 text-purple-700 font-semibold' : 'text-slate-600 hover:bg-slate-50' }}">Absensi</a>
                @endcan
                
                @can('leave.index')
                    <a href="{{ route('leave.index') }}" class="flex items-center px-3 py-2 text-sm rounded-lg transition-all {{ request()->routeIs('leave.*') ? 'bg-purple-100 text-purple-700 font-semibold' : 'text-slate-600 hover:bg-slate-50' }}">Manage Izin/Sakit/Dinas</a>
                @endcan
                
                @can('shifts.index')
                    <a href="{{ route('shifts.index') }}" class="flex items-center px-3 py-2 text-sm rounded-lg transition-all {{ request()->routeIs('shifts.*') ? 'bg-purple-100 text-purple-700 font-semibold' : 'text-slate-600 hover:bg-slate-50' }}">Manage Shift</a>
                @endcan

                @can('user-shifts.index')
                    <a href="{{ route('user-shifts.index') }}" class="flex items-center px-3 py-2 text-sm rounded-lg transition-all {{ request()->routeIs('user-shifts.*') ? 'bg-purple-100 text-purple-700 font-semibold' : 'text-slate-600 hover:bg-slate-50' }}">Jadwal Karyawan</a>
                @endcan

                @can('offices.index')
                    <a href="{{ route('offices.index') }}" class="flex items-center px-3 py-2 text-sm rounded-lg transition-all {{ request()->routeIs('offices.*') ? 'bg-purple-100 text-purple-700 font-semibold' : 'text-slate-600 hover:bg-slate-50' }}">Manage Office</a>
                @endcan
            </div>
        </div>
        @endcanany

        {{-- BILLING & SUPPORT GROUP --}}
        @canany(['billings.index', 'tickets.index', 'customers.index'])
        <div class="space-y-1" x-data="{ open: {{ request()->routeIs('billings.*') || request()->routeIs('tickets.*') || request()->routeIs('customers.*') ? 'true' : 'false' }} }">
            <button @click="open = !open" class="w-full group flex items-center justify-between px-3 lg:px-4 py-2.5 lg:py-3 text-sm font-semibold rounded-xl transition-all duration-300 {{ request()->routeIs('billings.*') || request()->routeIs('tickets.*') || request()->routeIs('customers.*') ? 'bg-blue-50 text-blue-700' : 'text-slate-700 hover:bg-white hover:shadow-md' }}">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-3 lg:mr-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    <span>Billing & Support</span>
                </div>
                <svg class="w-4 h-4 transition-transform duration-300" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
            </button>
            <div x-show="open" x-collapse class="ml-4 space-y-1">
                @can('billings.index')
                <a href="{{ route('billings.index') }}" class="flex items-center px-3 py-2 text-sm rounded-lg transition-all {{ request()->routeIs('billings.*') ? 'bg-blue-100 text-blue-700 font-semibold' : 'text-slate-600 hover:bg-slate-50' }}">Tagihan</a>
                @endcan

                @can('tickets.index')
                <a href="{{ route('tickets.index') }}" class="flex items-center px-3 py-2 text-sm rounded-lg transition-all {{ request()->routeIs('tickets.*') ? 'bg-blue-100 text-blue-700 font-semibold' : 'text-slate-600 hover:bg-slate-50' }}">Tiket Support</a>
                @endcan

                @can('customers.index')
                <a href="{{ route('customers.index') }}" class="flex items-center px-3 py-2 text-sm rounded-lg transition-all {{ request()->routeIs('customers.*') ? 'bg-blue-100 text-blue-700 font-semibold' : 'text-slate-600 hover:bg-slate-50' }}">Pelanggan</a>
                @endcan
            </div>
        </div>
        @endcanany

        {{-- KEUANGAN GROUP --}}
        @canany(['payments.index'])
        <div class="space-y-1" x-data="{ open: {{ request()->routeIs('payments.*') ? 'true' : 'false' }} }">
            <button @click="open = !open" class="w-full group flex items-center justify-between px-3 lg:px-4 py-2.5 lg:py-3 text-sm font-semibold rounded-xl transition-all duration-300 {{ request()->routeIs('payments.*') ? 'bg-emerald-50 text-emerald-700' : 'text-slate-700 hover:bg-white hover:shadow-md' }}">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-3 lg:mr-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    <span>Keuangan</span>
                </div>
                <svg class="w-4 h-4 transition-transform duration-300" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
            </button>
            <div x-show="open" x-collapse class="ml-4 space-y-1">
                @can('payments.index')
                <a href="{{ route('payments.index') }}" class="flex items-center px-3 py-2 text-sm rounded-lg transition-all {{ request()->routeIs('payments.*') ? 'bg-emerald-100 text-emerald-700 font-semibold' : 'text-slate-600 hover:bg-slate-50' }}">Pembayaran</a>
                @endcan
            </div>
        </div>
        @endcanany

        {{-- GUDANG GROUP --}}
        @canany(['warehouse.index', 'item-category.index', 'item.index', 'supplier.index', 'incoming-goods.index', 'outgoing-goods.index', 'warehouse-transfer.index', 'stock-report.index'])
        <div class="space-y-1" x-data="{ open: {{ request()->routeIs('warehouse.*') || request()->routeIs('item*') || request()->routeIs('supplier.*') || request()->routeIs('incoming*') || request()->routeIs('outgoing*') || request()->routeIs('stock*') || request()->routeIs('warehouse-transfer*') ? 'true' : 'false' }} }">
            <button @click="open = !open" class="w-full group flex items-center justify-between px-3 lg:px-4 py-2.5 lg:py-3 text-sm font-semibold rounded-xl transition-all duration-300 {{ request()->routeIs('warehouse.*') || request()->routeIs('item*') || request()->routeIs('incoming*') || request()->routeIs('outgoing*') ? 'bg-orange-50 text-orange-700' : 'text-slate-700 hover:bg-white hover:shadow-md' }}">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-3 lg:mr-4 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                    <span>Gudang</span>
                </div>
                <svg class="w-4 h-4 transition-transform duration-300" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
            </button>
            <div x-show="open" x-collapse class="ml-4 space-y-1">
                @can('warehouse.index')
                <a href="{{ route('warehouse.index') }}" class="flex items-center px-3 py-2 text-sm rounded-lg transition-all {{ request()->routeIs('warehouse.index') ? 'bg-orange-100 text-orange-700 font-semibold' : 'text-slate-600 hover:bg-slate-50' }}">Manage Gudang</a>
                @endcan

                @can('item-category.index')
                 <a href="{{ route('item-category.index') }}" class="flex items-center px-3 py-2 text-sm rounded-lg transition-all {{ request()->routeIs('item-category.index') ? 'bg-orange-100 text-orange-700 font-semibold' : 'text-slate-600 hover:bg-slate-50' }}">Kategori Barang</a>
                @endcan

                @can('item.index')
                 <a href="{{ route('item.index') }}" class="flex items-center px-3 py-2 text-sm rounded-lg transition-all {{ request()->routeIs('item.index') ? 'bg-orange-100 text-orange-700 font-semibold' : 'text-slate-600 hover:bg-slate-50' }}">Manage Barang</a>
                @endcan

                @can('supplier.index')
                 <a href="{{ route('supplier.index') }}" class="flex items-center px-3 py-2 text-sm rounded-lg transition-all {{ request()->routeIs('supplier.index') ? 'bg-orange-100 text-orange-700 font-semibold' : 'text-slate-600 hover:bg-slate-50' }}">Manage Supplier</a>
                @endcan

                @can('incoming-goods.index')
                 <a href="{{ route('incoming-goods.index') }}" class="flex items-center px-3 py-2 text-sm rounded-lg transition-all {{ request()->routeIs('incoming-goods.*') ? 'bg-orange-100 text-orange-700 font-semibold' : 'text-slate-600 hover:bg-slate-50' }}">Barang Masuk</a>
                @endcan

                @can('outgoing-goods.index')
                 <a href="{{ route('outgoing-goods.index') }}" class="flex items-center px-3 py-2 text-sm rounded-lg transition-all {{ request()->routeIs('outgoing-goods.*') ? 'bg-orange-100 text-orange-700 font-semibold' : 'text-slate-600 hover:bg-slate-50' }}">Barang Keluar</a>
                @endcan

                @can('warehouse-transfer.index')
                 <a href="{{ route('warehouse-transfer.index') }}" class="flex items-center px-3 py-2 text-sm rounded-lg transition-all {{ request()->routeIs('warehouse-transfer.*') ? 'bg-orange-100 text-orange-700 font-semibold' : 'text-slate-600 hover:bg-slate-50' }}">Transfer Gudang</a>
                @endcan

                @can('stock-report.index')
                 <a href="{{ route('stock-report.index') }}" class="flex items-center px-3 py-2 text-sm rounded-lg transition-all {{ request()->routeIs('stock-report.index') ? 'bg-orange-100 text-orange-700 font-semibold' : 'text-slate-600 hover:bg-slate-50' }}">Laporan Stok</a>
                 <a href="{{ route('stock-report.minimum-stock') }}" class="flex items-center px-3 py-2 text-sm rounded-lg transition-all {{ request()->routeIs('stock-report.minimum-stock') ? 'bg-orange-100 text-orange-700 font-semibold' : 'text-slate-600 hover:bg-slate-50' }}">Stok Minimum</a>
                @endcan
            </div>
        </div>
        @endcanany
        
        {{-- MANAJEMEN GROUP --}}
        @canany(['packages.index', 'users.index', 'coverage_areas.index', 'korlaps.index', 'roles.index'])
        <div class="space-y-1" x-data="{ open: {{ request()->routeIs('packages.*') || request()->routeIs('users.*') || request()->routeIs('coverage_areas.*') || request()->routeIs('korlaps.*') || request()->routeIs('roles.*') ? 'true' : 'false' }} }">
            <button @click="open = !open" class="w-full group flex items-center justify-between px-3 lg:px-4 py-2.5 lg:py-3 text-sm font-semibold rounded-xl transition-all duration-300 {{ request()->routeIs('users.*') || request()->routeIs('roles.*') ? 'bg-purple-50 text-purple-700' : 'text-slate-700 hover:bg-white hover:shadow-md' }}">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-3 lg:mr-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    <span>Manajemen</span>
                </div>
                <svg class="w-4 h-4 transition-transform duration-300" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
            </button>
            <div x-show="open" x-collapse class="ml-4 space-y-1">
                @can('packages.index')
                <a href="{{ route('packages.index') }}" class="flex items-center px-3 py-2 text-sm rounded-lg transition-all {{ request()->routeIs('packages.*') ? 'bg-purple-100 text-purple-700 font-semibold' : 'text-slate-600 hover:bg-slate-50' }}">Manage Paket Internet</a>
                @endcan

                @can('users.index')
                <a href="{{ route('users.index') }}" class="flex items-center px-3 py-2 text-sm rounded-lg transition-all {{ request()->routeIs('users.*') ? 'bg-purple-100 text-purple-700 font-semibold' : 'text-slate-600 hover:bg-slate-50' }}">User</a>
                @endcan

                @can('coverage_areas.index')
                <a href="{{ route('coverage_areas.index') }}" class="flex items-center px-3 py-2 text-sm rounded-lg transition-all {{ request()->routeIs('coverage_areas.*') ? 'bg-purple-100 text-purple-700 font-semibold' : 'text-slate-600 hover:bg-slate-50' }}">Manage Area Tercover</a>
                @endcan

                @can('korlaps.index')
                <a href="{{ route('korlaps.index') }}" class="flex items-center px-3 py-2 text-sm rounded-lg transition-all {{ request()->routeIs('korlaps.*') ? 'bg-purple-100 text-purple-700 font-semibold' : 'text-slate-600 hover:bg-slate-50' }}">Manage Korlap</a>
                @endcan

                {{-- FITUR BARU: MANAJEMEN ROLE --}}
                @can('roles.index')
                <a href="{{ route('roles.index') }}" class="flex items-center px-3 py-2 text-sm rounded-lg transition-all {{ request()->routeIs('roles.*') ? 'bg-purple-100 text-purple-700 font-semibold' : 'text-slate-600 hover:bg-slate-50' }}">
                    <span class="flex-1">Manage Role & Akses</span>
                    <span class="px-2 py-0.5 text-[10px] bg-red-100 text-red-700 rounded-full font-bold">New</span>
                </a>
                @endcan
            </div>
        </div>
        @endcanany

    </nav>

    <div class="p-4 border-t border-slate-100 bg-white shrink-0" x-data="{ logoutModalOpen: false }">
        
        <button 
            type="button" 
            @click="logoutModalOpen = true" 
            class="w-full flex items-center justify-center px-4 py-2.5 text-sm font-bold text-red-600 bg-red-50 hover:bg-red-600 hover:text-white rounded-xl transition-all shadow-sm active:scale-95"
        >
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
            Keluar
        </button>

        <div class="flex justify-between items-center mt-3 px-1">
            <p class="text-[10px] text-slate-800">v3.1.0</p>
            <span class="text-[10px] text-slate-800">&copy; {{ date('Y') }} SPD-Link</span>
        </div>

        <template x-teleport="body">
        <div x-show="logoutModalOpen" class="fixed inset-0 z-[999] flex items-center justify-center px-4 sm:px-6" style="display: none;">
            
            <div x-show="logoutModalOpen" 
                 x-transition.opacity.duration.300ms
                 class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" 
                 @click="logoutModalOpen = false"></div>

            <div x-show="logoutModalOpen" 
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:scale-95"
                 class="relative bg-white rounded-2xl shadow-2xl w-full max-w-sm overflow-hidden transform transition-all p-6 border border-slate-100">
                
                <div class="text-center">
                    <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-red-100 mb-4">
                        <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-slate-900">Konfirmasi Keluar</h3>
                    <p class="text-sm text-slate-500 mt-2">Apakah Anda yakin ingin mengakhiri sesi ini?</p>
                </div>
                
                <div class="mt-6 grid grid-cols-2 gap-3">
                    <button type="button" @click="logoutModalOpen = false" class="w-full inline-flex justify-center rounded-xl bg-white px-4 py-2.5 text-sm font-bold text-slate-700 shadow-sm ring-1 ring-inset ring-slate-300 hover:bg-slate-50 transition-colors">
                        Batal
                    </button>
                    <form action="{{ route('logout') }}" method="POST" class="w-full">
                        @csrf
                        <button type="submit" class="w-full inline-flex justify-center rounded-xl bg-red-600 px-4 py-2.5 text-sm font-bold text-white shadow-sm hover:bg-red-700 transition-colors">
                            Ya, Keluar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </template>

</aside>