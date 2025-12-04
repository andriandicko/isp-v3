<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 - Akses Ditolak</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 h-screen flex items-center justify-center p-4">
    <div class="max-w-md w-full text-center">
        {{-- Ilustrasi Gembok/Security --}}
        <div class="mb-8 relative">
            <div class="w-24 h-24 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4 animate-bounce">
                <svg class="w-12 h-12 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                </svg>
            </div>
            {{-- Efek Shadow --}}
            <div class="w-24 h-3 bg-red-200/50 rounded-full mx-auto mt-4 blur-sm"></div>
        </div>

        <h1 class="text-6xl font-extrabold text-slate-900 mb-2 tracking-tight">403</h1>
        <h2 class="text-2xl font-bold text-slate-800 mb-3">Akses Ditolak</h2>
        <p class="text-slate-500 mb-8 leading-relaxed">
            Maaf, Anda tidak memiliki izin untuk mengakses halaman ini.<br>
            Silakan hubungi Administrator jika ini adalah kesalahan.
        </p>

        <div class="flex flex-col sm:flex-row gap-3 justify-center">
            {{-- Tombol Dashboard --}}
            <a href="{{ url('/dashboard') }}" class="inline-flex items-center justify-center px-6 py-3 border border-transparent text-sm font-bold rounded-xl text-white bg-blue-600 hover:bg-blue-700 transition-all shadow-lg shadow-blue-500/30 transform hover:-translate-y-0.5">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                </svg>
                Ke Dashboard
            </a>
            
            {{-- Tombol Kembali --}}
            <button onclick="history.back()" class="inline-flex items-center justify-center px-6 py-3 border-2 border-slate-200 text-sm font-bold rounded-xl text-slate-600 bg-white hover:bg-slate-50 hover:border-slate-300 transition-colors">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Kembali
            </button>
        </div>
        
        <div class="mt-12 text-xs text-slate-400 font-medium">
            &copy; {{ date('Y') }} {{ config('app.name') }}. Security System.
        </div>
    </div>
</body>
</html>