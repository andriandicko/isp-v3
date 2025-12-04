<header class="sticky top-0 z-30 bg-white/80 backdrop-blur-md border-b border-slate-200 shadow-sm h-16 transition-all duration-300" x-data="{ logoutModalOpen: false }">
    <div class="px-4 sm:px-6 lg:px-8 h-full flex items-center justify-between">

        <div class="flex items-center gap-4">
            
            <button type="button" 
                    @click.stop="sidebarOpen = !sidebarOpen" 
                    class="lg:hidden p-2 -ml-2 text-slate-600 hover:bg-slate-100 active:bg-slate-200 rounded-lg transition-colors focus:outline-none relative z-50">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
            </button>

            <div class="flex flex-col justify-center">
                <h2 class="text-lg font-bold text-slate-800 leading-none tracking-tight">
                    @yield('title')
                </h2>
                <div class="hidden sm:flex items-center text-xs text-slate-500 font-medium mt-0.5">
                    <span>{{ config('app.name') }}</span>
                    <svg class="w-3 h-3 mx-1 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    <span class="text-indigo-600">
                        {{ Request::segment(1) ? ucfirst(str_replace('-', ' ', Request::segment(1))) : 'Home' }}
                    </span>
                </div>
            </div>
        </div>

        <div class="flex items-center gap-3 sm:gap-4">
            
            <div class="hidden md:flex items-center px-3 py-1 bg-slate-50 rounded-full border border-slate-200/60">
                <span class="relative flex h-2 w-2 mr-2">
                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                  <span class="relative inline-flex rounded-full h-2 w-2 bg-green-500"></span>
                </span>
                <span id="liveTime" class="text-xs font-mono font-bold text-slate-600 tracking-wide">00:00:00</span>
            </div>

            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open" class="flex items-center gap-2 focus:outline-none group pl-1 pr-1 py-1 rounded-full hover:bg-slate-50 border border-transparent hover:border-slate-100">
                    <div class="w-8 h-8 rounded-full bg-gradient-to-tr from-indigo-500 to-purple-500 flex items-center justify-center text-white font-bold text-sm shadow-sm ring-2 ring-white group-hover:ring-indigo-100 transition">
                        {{ substr(auth()->user()->name ?? 'U', 0, 1) }}
                    </div>
                    <div class="hidden md:block text-left mr-1">
                        <p class="text-xs font-bold text-slate-700 group-hover:text-indigo-600 transition leading-none">{{ auth()->user()->name ?? 'User' }}</p>
                        <p class="text-[10px] text-slate-500 capitalize leading-none mt-0.5">{{ auth()->user()->getRoleNames()->first() ?? 'Member' }}</p>
                    </div>
                    <svg class="w-3 h-3 text-slate-400 hidden md:block group-hover:text-slate-600 transition transform duration-200" 
                         :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>

                <div x-show="open" 
                     @click.away="open = false"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 scale-95 translate-y-2"
                     x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                     x-transition:leave="transition ease-in duration-75"
                     x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                     x-transition:leave-end="opacity-0 scale-95 translate-y-2"
                     class="absolute right-0 mt-2 w-56 bg-white rounded-xl shadow-xl border border-slate-100 py-1 z-50 origin-top-right divide-y divide-slate-50"
                     style="display: none;">
                    
                    <div class="px-4 py-3 border-b border-slate-50 md:hidden">
                        <p class="text-sm font-bold text-slate-800">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-slate-500 truncate">{{ auth()->user()->email }}</p>
                    </div>

                    <div class="py-1">
                        <a href="#" class="flex items-center px-4 py-2 text-sm text-slate-600 hover:bg-indigo-50 hover:text-indigo-600 transition group">
                            <svg class="w-4 h-4 mr-3 text-slate-400 group-hover:text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                            Profil Saya
                        </a>
                    </div>
                    
                    <div class="py-1">
                        <button type="button" 
                                @click="open = false; logoutModalOpen = true" 
                                class="w-full text-left px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 font-medium flex items-center transition-colors">
                            <svg class="w-4 h-4 mr-3 text-red-400 group-hover:text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                            Keluar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <template x-teleport="body">
        <div x-show="logoutModalOpen" class="fixed inset-0 z-[999] flex items-center justify-center px-4 sm:px-6" style="display: none;" x-cloak>
            
            <div x-show="logoutModalOpen" 
                 x-transition.opacity.duration.300ms
                 class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" 
                 @click="logoutModalOpen = false"></div>

            <div x-show="logoutModalOpen" 
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 class="relative bg-white rounded-2xl shadow-2xl w-full max-w-sm overflow-hidden transform transition-all p-6 border border-slate-100">
                
                <div class="text-center">
                    <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-red-100 mb-4">
                        <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-slate-900">Konfirmasi Keluar</h3>
                    <p class="text-sm text-slate-500 mt-2">Apakah Anda yakin ingin mengakhiri sesi ini?</p>
                </div>
                
                <div class="mt-6 grid grid-cols-2 gap-3">
                    <button type="button" @click="logoutModalOpen = false" class="w-full inline-flex justify-center rounded-xl bg-white px-4 py-2.5 text-sm font-bold text-slate-700 shadow-sm ring-1 ring-inset ring-slate-300 hover:bg-slate-50 transition-colors">Batal</button>
                    <form action="{{ route('logout') }}" method="POST" class="w-full">
                        @csrf
                        <button type="submit" class="w-full inline-flex justify-center rounded-xl bg-red-600 px-4 py-2.5 text-sm font-bold text-white shadow-sm hover:bg-red-700 transition-colors">Ya, Keluar</button>
                    </form>
                </div>
            </div>
        </div>
    </template>
</header>

<script>
    function updateClock() {
        const el = document.getElementById('liveTime');
        if(el) el.innerText = new Date().toLocaleTimeString('id-ID', { hour12: false });
    }
    setInterval(updateClock, 1000);
    updateClock();
</script>