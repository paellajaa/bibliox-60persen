<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BIBLIOX - Modern Library System</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; scroll-behavior: smooth; }
        ::-webkit-scrollbar { width: 5px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-[#F8FAFC] text-slate-800 antialiased">
    <div class="flex min-h-screen">
        <aside class="w-72 bg-white border-r border-slate-100 flex flex-col fixed h-full z-20 shadow-sm">
            <div class="p-8">
                <div class="flex items-center gap-3 mb-2">
                    <div class="w-10 h-10 bg-cyan-600 rounded-xl flex items-center justify-center text-white shadow-lg shadow-cyan-200 font-bold text-xl">
                        <i class="fa-solid fa-book-open"></i>
                    </div>
                    <h1 class="text-2xl font-black text-slate-800 tracking-tighter uppercase italic">BIBLIO<span class="text-cyan-600">X</span></h1>
                </div>
                <p class="text-[10px] text-slate-400 uppercase tracking-widest font-extrabold ml-1">
                    {{ Auth::user()->peran === 'admin' ? 'Pustakawan System' : 'Member Library' }}
                </p>
            </div>

            <nav class="flex-1 px-4 space-y-1 overflow-y-auto">
                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mb-2 px-5">Utama</p>
                <a href="{{ Auth::user()->peran === 'admin' ? route('admin.dashboard') : route('anggota.dashboard') }}" 
                   class="flex items-center gap-3 px-5 py-4 {{ Request::is('admin/dashboard') || Request::is('anggota/dashboard') ? 'bg-cyan-50 text-cyan-600 font-black' : 'text-slate-500 hover:bg-slate-50' }} rounded-2xl font-bold transition-all">
                    <i class="fa-solid fa-house text-lg w-6 text-center"></i> <span class="text-sm whitespace-nowrap">Beranda</span>
                </a>

                @if(Auth::user()->peran === 'admin')
                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-6 mb-2 px-5">Manajemen</p>
                    
                    <a href="{{ route('admin.peminjaman.index') }}" 
                       class="flex items-center justify-between px-5 py-4 {{ Request::is('admin/verifikasi*') ? 'bg-cyan-50 text-cyan-600 font-black' : 'text-slate-500 hover:bg-slate-50' }} rounded-2xl font-bold transition-all">
                        <div class="flex items-center gap-3">
                            <i class="fa-solid fa-clipboard-check text-lg w-6 text-center"></i> 
                            <span class="text-sm whitespace-nowrap">Pusat Verifikasi</span>
                        </div>
                        @php
                            $pCount = 0;
                            if (class_exists('App\Models\Peminjaman')) {
                                $pCount = \App\Models\Peminjaman::where('status', 'menunggu')->count();
                            }
                        @endphp
                        @if($pCount > 0)
                            <span class="bg-red-500 text-white text-[9px] px-2 py-1 rounded-full animate-pulse flex-shrink-0">{{ $pCount }}</span>
                        @endif
                    </a>

                    <a href="{{ route('admin.buku.index') }}" class="flex items-center gap-3 px-5 py-4 {{ Request::is('admin/buku*') ? 'bg-cyan-50 text-cyan-600 font-black' : 'text-slate-500 hover:bg-slate-50' }} rounded-2xl font-bold transition-all">
                        <i class="fa-solid fa-book text-lg w-6 text-center"></i> <span class="text-sm whitespace-nowrap">Kelola Buku</span>
                    </a>

                    <a href="{{ route('admin.users.index') }}" class="flex items-center gap-3 px-5 py-4 {{ Request::is('admin/users*') ? 'bg-cyan-50 text-cyan-600 font-black' : 'text-slate-500 hover:bg-slate-50' }} rounded-2xl font-bold transition-all">
                        <i class="fa-solid fa-users-gear text-lg w-6 text-center"></i> <span class="text-sm whitespace-nowrap">Kelola User</span>
                    </a>
                @endif

                @if(Auth::user()->peran === 'anggota')
                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-6 mb-2 px-5">Layanan Siswa</p>
                    <a href="{{ route('anggota.dashboard') }}" class="flex items-center gap-3 px-5 py-4 {{ Request::is('anggota/dashboard') ? 'text-cyan-600 font-black' : 'text-slate-500 hover:bg-slate-50' }} rounded-2xl font-bold transition-all">
                        <i class="fa-solid fa-magnifying-glass text-lg w-6 text-center"></i> <span class="text-sm whitespace-nowrap">Cari Buku</span>
                    </a>
                    <a href="{{ route('anggota.buku-saya') }}" class="flex items-center gap-3 px-5 py-4 {{ Request::is('anggota/buku-saya') ? 'bg-cyan-50 text-cyan-600 font-black' : 'text-slate-500 hover:bg-slate-50' }} rounded-2xl font-bold transition-all">
                        <i class="fa-solid fa-bookmark text-lg w-6 text-center"></i> <span class="text-sm whitespace-nowrap">Buku Saya</span>
                    </a>
                @endif
            </nav>

            <div class="p-6 border-t border-slate-50">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="group w-full flex items-center gap-3 px-5 py-4 text-slate-400 hover:bg-red-50 hover:text-red-600 rounded-2xl font-bold transition-all">
                        <i class="fa-solid fa-arrow-right-from-bracket text-lg w-6 text-center group-hover:-translate-x-1 transition-transform"></i> <span class="text-sm whitespace-nowrap uppercase">Log Out</span>
                    </button>
                </form>
            </div>
        </aside>

        <div class="flex-1 ml-72 flex flex-col">
            <header class="h-20 flex items-center justify-between px-10 bg-white sticky top-0 z-10 border-b border-slate-100 shadow-sm">
                <span class="px-5 py-2 bg-slate-900 rounded-2xl text-[10px] font-black text-white uppercase tracking-[0.2em]">
                    {{ Auth::user()->peran === 'admin' ? 'Pusat Kendali Admin' : 'Area Siswa BiblioX' }}
                </span>
                <div class="flex items-center gap-4 pl-6 border-l border-slate-100">
                    <div class="text-right hidden md:block">
                        <p class="text-sm font-black text-slate-900 leading-none mb-1">{{ Auth::user()->nama }}</p>
                        <span class="px-2 py-0.5 bg-cyan-100 text-cyan-700 text-[9px] font-black uppercase rounded-md">{{ Auth::user()->peran }}</span>
                    </div>
                    <div class="w-11 h-11 rounded-2xl bg-gradient-to-br from-cyan-500 to-blue-600 flex items-center justify-center text-white font-bold text-lg shadow-md shadow-cyan-100">
                        {{ substr(Auth::user()->nama, 0, 1) }}
                    </div>
                </div>
            </header>
            <main class="p-10">
                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>