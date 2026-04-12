<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <title>BiblioX - Dashboard Anggota</title>
    {{-- Memanggil Tailwind & JS --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    {{-- AlpineJS untuk Modal --}}
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        [x-cloak] { display: none !important; }
        .animate-fadeIn {
            animation: fadeIn 0.5s ease-out;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body class="bg-[#f8fafc] text-slate-900 font-sans">
    {{-- Navbar --}}
    <nav class="border-b border-slate-100 bg-white/80 backdrop-blur-md sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-6 h-20 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <div class="w-10 h-10 bg-slate-900 rounded-xl flex items-center justify-center">
                    <span class="text-cyan-500 font-black text-xl italic">B</span>
                </div>
                <h1 class="text-2xl font-black italic tracking-tighter text-slate-900">BIBLIOX</h1>
            </div>

            <div class="flex items-center gap-8">
                {{-- Navigasi --}}
                <div class="hidden md:flex items-center gap-6">
                    <a href="{{ route('anggota.dashboard') }}" 
                       class="text-xs font-black uppercase tracking-widest {{ request()->routeIs('anggota.dashboard') ? 'text-cyan-600' : 'text-slate-400 hover:text-slate-900' }}">
                        Katalog
                    </a>
                    <a href="{{ route('anggota.buku-saya') }}" 
                       class="text-xs font-black uppercase tracking-widest {{ request()->routeIs('anggota.buku-saya') ? 'text-cyan-600' : 'text-slate-400 hover:text-slate-900' }}">
                        Pinjaman Saya
                    </a>
                </div>

                {{-- User & Logout --}}
                <div class="flex items-center gap-4 pl-6 border-l border-slate-100">
                    <div class="text-right hidden sm:block">
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-tight">Anggota</p>
                        <p class="text-xs font-bold text-slate-900">{{ Auth::user()->nama }}</p>
                    </div>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button class="p-3 bg-red-50 text-red-500 rounded-xl hover:bg-red-500 hover:text-white transition-all group">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    {{-- Main Content --}}
    <main class="max-w-7xl mx-auto px-6 py-10">
        @yield('content')
    </main>

    {{-- Footer --}}
    <footer class="max-w-7xl mx-auto px-6 py-10 border-t border-slate-100">
        <p class="text-center text-slate-400 text-[10px] font-bold uppercase tracking-[0.3em]">
            &copy; 2026 BiblioX Library Management System - UKK RPL
        </p>
    </footer>
</body>
</html>