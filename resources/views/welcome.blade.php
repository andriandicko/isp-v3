<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SPDLINK - Quality Internet Solutions</title>
    
    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    {{-- Tailwind CSS --}}
    <script src="https://cdn.tailwindcss.com"></script>
    
    {{-- Alpine.js for Interactivity --}}
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .gradient-text {
            background-clip: text;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-image: linear-gradient(to right, #2563EB, #4F46E5);
        }
    </style>
</head>
<body class="antialiased bg-white text-slate-800">

    {{-- NAVBAR --}}
    <nav class="fixed w-full z-50 transition-all duration-300" 
         x-data="{ scrolled: false, mobileMenu: false }" 
         @scroll.window="scrolled = (window.pageYOffset > 20) ? true : false"
         :class="scrolled ? 'bg-white/90 backdrop-blur-md shadow-sm py-3' : 'bg-transparent py-5'">
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center">
                {{-- Logo --}}
                <div class="flex items-center gap-2">
                    <img src="{{ asset('Logo-SPDLINK.png') }}" alt="Logo SPDLINK" class="h-10 w-auto rounded-md shadow-sm bg-white">
                    <span class="text-xl font-bold tracking-tight" :class="scrolled ? 'text-slate-900' : 'text-slate-900 text-white'">SPDLINK</span>
                </div>

                {{-- Desktop Menu --}}
                <div class="hidden lg:flex items-center space-x-8">
                    <a href="#home" class="text-sm font-medium hover:text-blue-500 transition" :class="scrolled ? 'text-slate-600' : 'text-white/90 hover:text-white'">Beranda</a>
                    <a href="#about" class="text-sm font-medium hover:text-blue-500 transition" :class="scrolled ? 'text-slate-600' : 'text-white/90 hover:text-white'">Tentang</a>
                    <a href="#pricing" class="text-sm font-medium hover:text-blue-500 transition" :class="scrolled ? 'text-slate-600' : 'text-white/90 hover:text-white'">Paket</a>
                    <a href="#testimonials" class="text-sm font-medium hover:text-blue-500 transition" :class="scrolled ? 'text-slate-600' : 'text-white/90 hover:text-white'">Testimoni</a>
                    <a href="#coverage" class="text-sm font-medium hover:text-blue-500 transition" :class="scrolled ? 'text-slate-600' : 'text-white/90 hover:text-white'">Cek Area</a>
                </div>

                {{-- Auth Buttons --}}
                <div class="hidden lg:flex items-center gap-3">
                    @auth
                        <a href="{{ route('dashboard') }}" class="px-5 py-2.5 bg-white text-blue-600 font-semibold rounded-full shadow-lg hover:shadow-xl transition transform hover:-translate-y-0.5 text-sm">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="px-5 py-2.5 font-semibold rounded-full transition transform hover:-translate-y-0.5 text-sm"
                           :class="scrolled ? 'bg-blue-600 text-white hover:bg-blue-700' : 'bg-white text-blue-600 hover:bg-slate-100'">
                            Login LinkOps
                        </a>
                    @endauth
                </div>

                {{-- Mobile Menu Button --}}
                <button @click="mobileMenu = !mobileMenu" class="lg:hidden p-2 rounded-md focus:outline-none" :class="scrolled ? 'text-slate-900' : 'text-slate-900 text-white'">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
            </div>
        </div>

        {{-- Mobile Dropdown --}}
        <div x-show="mobileMenu" x-transition class="lg:hidden bg-white border-t border-gray-100 absolute w-full shadow-xl">
            <div class="px-4 pt-2 pb-6 space-y-2">
                <a href="#home" class="block px-3 py-2 text-base font-medium text-slate-700 hover:bg-blue-50 hover:text-blue-600 rounded-md">Beranda</a>
                <a href="#about" class="block px-3 py-2 text-base font-medium text-slate-700 hover:bg-blue-50 hover:text-blue-600 rounded-md">Tentang</a>
                <a href="#pricing" class="block px-3 py-2 text-base font-medium text-slate-700 hover:bg-blue-50 hover:text-blue-600 rounded-md">Paket Internet</a>
                <a href="#testimonials" class="block px-3 py-2 text-base font-medium text-slate-700 hover:bg-blue-50 hover:text-blue-600 rounded-md">Testimoni</a>
                <a href="#coverage" class="block px-3 py-2 text-base font-medium text-slate-700 hover:bg-blue-50 hover:text-blue-600 rounded-md">Cek Area</a>
                <div class="pt-4 border-t border-gray-100">
                    @auth
                        <a href="{{ route('dashboard') }}" class="block w-full text-center px-4 py-2 bg-blue-600 text-white font-bold rounded-lg">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="block w-full text-center px-4 py-2 bg-slate-900 text-white font-bold rounded-lg">Login LinkOps</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    {{-- HERO SECTION --}}
    <section id="home" class="relative pt-32 pb-20 lg:pt-48 lg:pb-32 overflow-hidden bg-slate-900">
        <div class="absolute inset-0">
            <div class="absolute inset-0 bg-gradient-to-br from-blue-900/40 to-slate-900/90 z-10"></div>
            <img src="https://images.unsplash.com/photo-1451187580459-43490279c0fa?q=80&w=2072&auto=format&fit=crop" class="w-full h-full object-cover opacity-30" alt="Background Network">
        </div>

        <div class="relative z-20 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center lg:text-left">
            <div class="grid lg:grid-cols-2 gap-12 items-center">
                <div>
                    <span class="inline-block py-1 px-3 rounded-full bg-blue-500/20 border border-blue-500/30 text-blue-300 text-sm font-semibold mb-6">
                        🚀 Internet Fiber Optic Tercepat
                    </span>
                    <h1 class="text-4xl lg:text-6xl font-extrabold text-white leading-tight mb-6">
                        Quality Internet <br>
                        <span class="text-blue-500">Solutions</span>
                    </h1>
                    <p class="text-lg text-slate-300 mb-8 leading-relaxed max-w-lg mx-auto lg:mx-0">
                        <span class="font-bold text-white">PT. Semangat Pagi Diginet</span> menghadirkan pengalaman internet super cepat, stabil, dan tanpa batasan kuota untuk mendukung aktivitas digital Anda.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
                        <a href="#pricing" class="px-8 py-4 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl shadow-lg shadow-blue-600/30 transition transform hover:-translate-y-1">
                            Lihat Paket
                        </a>
                        <a href="#coverage" class="px-8 py-4 bg-white/10 hover:bg-white/20 backdrop-blur-sm border border-white/20 text-white font-bold rounded-xl transition">
                            Cek Jangkauan
                        </a>
                    </div>
                </div>
                {{-- Hero Illustration --}}
                <div class="hidden lg:block relative">
                    <div class="relative w-full max-w-lg mx-auto">
                        <div class="absolute top-0 -left-4 w-72 h-72 bg-blue-500 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-blob"></div>
                        <div class="absolute top-0 -right-4 w-72 h-72 bg-purple-500 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-blob animation-delay-2000"></div>
                        <div class="absolute -bottom-8 left-20 w-72 h-72 bg-pink-500 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-blob animation-delay-4000"></div>
                        <div class="relative bg-white/5 backdrop-blur-xl border border-white/10 rounded-2xl p-6 shadow-2xl transform rotate-3 hover:rotate-0 transition duration-500">
                            <div class="flex items-center gap-4 mb-4">
                                <div class="w-12 h-12 bg-green-500 rounded-full flex items-center justify-center">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                </div>
                                <div>
                                    <h3 class="text-white font-bold">Status Koneksi</h3>
                                    <p class="text-green-400 text-sm">Terhubung • 100 Mbps</p>
                                </div>
                            </div>
                            <div class="space-y-3">
                                <div class="h-2 bg-white/20 rounded-full w-3/4"></div>
                                <div class="h-2 bg-white/20 rounded-full w-full"></div>
                                <div class="h-2 bg-white/20 rounded-full w-5/6"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- FEATURES SECTION --}}
    <section id="about" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl font-bold text-slate-900 mb-4">Kenapa Memilih <span class="gradient-text">SPDLINK?</span></h2>
                <p class="text-slate-500 max-w-2xl mx-auto">Kami berkomitmen memberikan layanan terbaik dengan infrastruktur modern untuk kenyamanan digital Anda.</p>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                <div class="p-8 rounded-2xl bg-slate-50 border border-slate-100 hover:shadow-lg transition group">
                    <div class="w-14 h-14 bg-blue-100 rounded-xl flex items-center justify-center text-blue-600 mb-6 group-hover:scale-110 transition duration-300">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-3">Kecepatan Tinggi</h3>
                    <p class="text-slate-500 leading-relaxed">
                        Jaringan Fiber Optic 100% menjamin kecepatan download dan upload simetris tanpa lag.
                    </p>
                </div>
                <div class="p-8 rounded-2xl bg-slate-50 border border-slate-100 hover:shadow-lg transition group">
                    <div class="w-14 h-14 bg-purple-100 rounded-xl flex items-center justify-center text-purple-600 mb-6 group-hover:scale-110 transition duration-300">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-3">Support 24/7</h3>
                    <p class="text-slate-500 leading-relaxed">
                        Tim teknis kami siap membantu kapanpun Anda mengalami kendala, siang maupun malam.
                    </p>
                </div>
                <div class="p-8 rounded-2xl bg-slate-50 border border-slate-100 hover:shadow-lg transition group">
                    <div class="w-14 h-14 bg-green-100 rounded-xl flex items-center justify-center text-green-600 mb-6 group-hover:scale-110 transition duration-300">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-3">Unlimited Quota</h3>
                    <p class="text-slate-500 leading-relaxed">
                        Bebas akses internet sepuasnya tanpa batasan FUP (Fair Usage Policy). Streaming non-stop!
                    </p>
                </div>
            </div>
        </div>
    </section>

    {{-- PRICING SECTION --}}
    <section id="pricing" class="py-20 bg-slate-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl font-bold text-slate-900 mb-4">Pilih Paket Sesuai Kebutuhan</h2>
                <p class="text-slate-500">Harga transparan, tanpa biaya tersembunyi.</p>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                {{-- Paket 1 --}}
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-8 hover:border-blue-500 hover:shadow-xl hover:shadow-blue-500/10 transition duration-300 relative overflow-hidden">
                    <h3 class="text-lg font-bold text-slate-900 mb-2">Paket Rumahan</h3>
                    <div class="flex items-baseline gap-1 mb-6">
                        <span class="text-4xl font-extrabold text-slate-900">150rb</span>
                        <span class="text-slate-500">/bulan</span>
                    </div>
                    <ul class="space-y-4 mb-8">
                        <li class="flex items-center text-slate-600 text-sm">
                            <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Up to <strong>10 Mbps</strong>
                        </li>
                        <li class="flex items-center text-slate-600 text-sm">
                            <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Unlimited Quota
                        </li>
                        <li class="flex items-center text-slate-600 text-sm">
                            <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Ideal untuk 2-3 Perangkat
                        </li>
                    </ul>
                    <a href="https://wa.me/628123456789?text=Halo%20SPD-Link,%20saya%20mau%20pasang%20Paket%20Rumahan" class="block w-full py-3 px-4 bg-slate-100 hover:bg-slate-200 text-slate-900 font-bold text-center rounded-xl transition">
                        Pilih Paket
                    </a>
                </div>

                {{-- Paket 2 --}}
                <div class="bg-white rounded-2xl shadow-lg border-2 border-blue-500 p-8 relative transform md:-translate-y-4">
                    <div class="absolute top-0 right-0 bg-blue-600 text-white text-xs font-bold px-3 py-1 rounded-bl-xl">POPULAR</div>
                    <h3 class="text-lg font-bold text-slate-900 mb-2">Paket Gamer</h3>
                    <div class="flex items-baseline gap-1 mb-6">
                        <span class="text-4xl font-extrabold text-blue-600">250rb</span>
                        <span class="text-slate-500">/bulan</span>
                    </div>
                    <ul class="space-y-4 mb-8">
                        <li class="flex items-center text-slate-600 text-sm">
                            <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Up to <strong>20 Mbps</strong>
                        </li>
                        <li class="flex items-center text-slate-600 text-sm">
                            <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Unlimited Quota
                        </li>
                        <li class="flex items-center text-slate-600 text-sm">
                            <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Ideal untuk 4-6 Perangkat
                        </li>
                    </ul>
                    <a href="https://wa.me/628123456789?text=Halo%20SPD-Link,%20saya%20mau%20pasang%20Paket%20Gamer" class="block w-full py-3 px-4 bg-blue-600 hover:bg-blue-700 text-white font-bold text-center rounded-xl shadow-lg shadow-blue-500/30 transition">
                        Pilih Paket
                    </a>
                </div>

                {{-- Paket 3 --}}
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-8 hover:border-blue-500 hover:shadow-xl hover:shadow-blue-500/10 transition duration-300 relative overflow-hidden">
                    <h3 class="text-lg font-bold text-slate-900 mb-2">Paket Bisnis</h3>
                    <div class="flex items-baseline gap-1 mb-6">
                        <span class="text-4xl font-extrabold text-slate-900">450rb</span>
                        <span class="text-slate-500">/bulan</span>
                    </div>
                    <ul class="space-y-4 mb-8">
                        <li class="flex items-center text-slate-600 text-sm">
                            <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Up to <strong>50 Mbps</strong>
                        </li>
                        <li class="flex items-center text-slate-600 text-sm">
                            <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Unlimited Quota
                        </li>
                        <li class="flex items-center text-slate-600 text-sm">
                            <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Prioritas Support VIP
                        </li>
                    </ul>
                    <a href="https://wa.me/628123456789?text=Halo%20SPD-Link,%20saya%20mau%20pasang%20Paket%20Bisnis" class="block w-full py-3 px-4 bg-slate-100 hover:bg-slate-200 text-slate-900 font-bold text-center rounded-xl transition">
                        Pilih Paket
                    </a>
                </div>
            </div>
        </div>
    </section>

    {{-- TESTIMONIALS SECTION (BARU) --}}
    <section id="testimonials" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl font-bold text-slate-900 mb-4">Apa Kata Mereka?</h2>
                <p class="text-slate-500 max-w-2xl mx-auto">Kepuasan pelanggan adalah prioritas utama kami. Berikut pengalaman mereka menggunakan SPDLINK.</p>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                {{-- Testimonial 1 --}}
                <div class="bg-white p-8 rounded-2xl shadow-sm border border-slate-100 hover:shadow-lg transition duration-300">
                    <div class="flex items-center mb-4">
                        <div class="flex text-yellow-400">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        </div>
                    </div>
                    <p class="text-slate-600 mb-6 italic">"Awalnya ragu pasang baru, ternyata koneksinya ngebut banget! Buat WFH zoom meeting lancar jaya tanpa putus-putus. Recommended!"</p>
                    <div class="flex items-center gap-3">
                        <img src="https://ui-avatars.com/api/?name=Andi+Pratama&background=2563EB&color=fff" class="w-10 h-10 rounded-full" alt="User">
                        <div>
                            <h4 class="font-bold text-slate-900 text-sm">Andi Pratama</h4>
                            <p class="text-xs text-slate-500">Karyawan Swasta</p>
                        </div>
                    </div>
                </div>

                {{-- Testimonial 2 --}}
                <div class="bg-white p-8 rounded-2xl shadow-sm border border-slate-100 hover:shadow-lg transition duration-300">
                    <div class="flex items-center mb-4">
                        <div class="flex text-yellow-400">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        </div>
                    </div>
                    <p class="text-slate-600 mb-6 italic">"Paling suka sama paket Gamernya! Ping stabil, nggak pernah RTO pas lagi war. Supportnya juga gercep kalau ada gangguan."</p>
                    <div class="flex items-center gap-3">
                        <img src="https://ui-avatars.com/api/?name=Dimas+R&background=4F46E5&color=fff" class="w-10 h-10 rounded-full" alt="User">
                        <div>
                            <h4 class="font-bold text-slate-900 text-sm">Dimas Ramadhan</h4>
                            <p class="text-xs text-slate-500">Mahasiswa</p>
                        </div>
                    </div>
                </div>

                {{-- Testimonial 3 --}}
                <div class="bg-white p-8 rounded-2xl shadow-sm border border-slate-100 hover:shadow-lg transition duration-300">
                    <div class="flex items-center mb-4">
                        <div class="flex text-yellow-400">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        </div>
                    </div>
                    <p class="text-slate-600 mb-6 italic">"SPDLINK solusi banget buat usaha cafe saya. Pengunjung betah karena wifi kenceng, saya juga happy karena harganya terjangkau."</p>
                    <div class="flex items-center gap-3">
                        <img src="https://ui-avatars.com/api/?name=Siti+N&background=10B981&color=fff" class="w-10 h-10 rounded-full" alt="User">
                        <div>
                            <h4 class="font-bold text-slate-900 text-sm">Siti Nurhaliza</h4>
                            <p class="text-xs text-slate-500">Pemilik Cafe</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- COVERAGE CHECK SECTION --}}
    <section id="coverage" class="py-20 bg-blue-600 relative overflow-hidden">
        {{-- Background Pattern --}}
        <div class="absolute inset-0 opacity-10" style="background-image: url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'none\' fill-rule=\'evenodd\'%3E%3Cg fill=\'%23ffffff\' fill-opacity=\'1\'%3E%3Cpath d=\'M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>

        <div class="max-w-4xl mx-auto px-4 relative z-10 text-center">
            <h2 class="text-3xl font-bold text-white mb-6">Cek Ketersediaan Jaringan</h2>
            <p class="text-blue-100 mb-8">Masukkan nama area atau kelurahan Anda untuk mengecek apakah layanan kami sudah tersedia di lokasi Anda.</p>

            <form action="{{ route('customers.check-coverage') }}" method="POST" class="bg-white p-2 rounded-2xl shadow-2xl flex flex-col sm:flex-row gap-2">
                @csrf
                <div class="flex-1 relative">
                    <svg class="w-5 h-5 text-gray-400 absolute left-4 top-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    <input type="text" name="area_name" placeholder="Masukkan nama kelurahan/desa..." class="w-full pl-12 pr-4 py-3 rounded-xl border-none focus:ring-0 text-slate-800 placeholder-slate-400 font-medium h-full" required>
                </div>
                <button type="submit" class="px-8 py-3 bg-slate-900 text-white font-bold rounded-xl hover:bg-slate-800 transition shadow-lg">
                    Cek Sekarang
                </button>
            </form>
            
            {{-- Pesan Flash --}}
            @if(session('status'))
                <div class="mt-6 p-4 rounded-xl bg-white/20 backdrop-blur-md border border-white/30 text-white font-medium animate-fade-in-up">
                    {{ session('status') }}
                </div>
            @endif
        </div>
    </section>

    {{-- FOOTER --}}
    <footer class="bg-slate-900 text-slate-400 py-12 border-t border-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-4 gap-8 mb-8">
                <div class="col-span-1 md:col-span-2">
                    <div class="flex items-center gap-2 mb-4">
                        {{-- Logo Footer --}}
                        <img src="{{ asset('Logo-SPDLINK.png') }}" alt="Logo SPDLINK" class="h-10 w-auto rounded-md bg-white p-0.5">
                        {{-- Nama Perusahaan Resmi di Footer --}}
                        <span class="text-xl font-bold text-white">PT. Semangat Pagi Diginet</span>
                    </div>
                    <p class="text-sm leading-relaxed max-w-xs">
                        Penyedia layanan internet (ISP) berbasis Fiber Optic yang berkomitmen untuk menghubungkan pelosok negeri dengan dunia digital.
                    </p>
                </div>
                <div>
                    <h4 class="text-white font-bold mb-4">Menu</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="#home" class="hover:text-blue-500 transition">Beranda</a></li>
                        <li><a href="#about" class="hover:text-blue-500 transition">Tentang Kami</a></li>
                        <li><a href="#pricing" class="hover:text-blue-500 transition">Paket Internet</a></li>
                        <li><a href="#coverage" class="hover:text-blue-500 transition">Cek Area</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-white font-bold mb-4">Kontak</h4>
                    <ul class="space-y-2 text-sm">
                        <li class="flex items-start gap-2">
                            <svg class="w-5 h-5 text-blue-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            <span>Jl. Raya Internet No. 123, Kota Digital, Indonesia</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                            <span>+62 812-3456-7890</span>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-slate-800 pt-8 text-center text-sm">
                {{-- Nama Perusahaan di Copyright --}}
                &copy; {{ date('Y') }} PT. Semangat Pagi Diginet. All rights reserved.
            </div>
        </div>
    </footer>

    <style>
        /* Animasi Tambahan */
        @keyframes blob {
            0% { transform: translate(0px, 0px) scale(1); }
            33% { transform: translate(30px, -50px) scale(1.1); }
            66% { transform: translate(-20px, 20px) scale(0.9); }
            100% { transform: translate(0px, 0px) scale(1); }
        }
        .animate-blob {
            animation: blob 7s infinite;
        }
        .animation-delay-2000 {
            animation-delay: 2s;
        }
        .animation-delay-4000 {
            animation-delay: 4s;
        }
        .animate-fade-in-up {
            animation: fadeInUp 0.5s ease-out;
        }
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</body>
</html>