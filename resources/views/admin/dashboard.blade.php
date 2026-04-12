@extends('layouts.admin')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
    <div class="bg-white p-8 rounded-[32px] border border-slate-100 shadow-sm hover:shadow-xl hover:shadow-blue-600/5 transition-all duration-300">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-slate-400 text-xs font-bold uppercase tracking-widest mb-2">Total Koleksi</p>
                <h3 class="text-4xl font-black text-slate-900 tracking-tighter">{{ $total_buku }}</h3>
            </div>
            <div class="w-12 h-12 bg-blue-50 text-blue-500 rounded-2xl flex items-center justify-center text-xl">
                <i class="fa-solid fa-book"></i>
            </div>
        </div>
        <div class="mt-6 flex items-center text-[10px] font-bold text-blue-600 bg-blue-50 px-3 py-1.5 rounded-full w-fit">
            <i class="fa-solid fa-clock-rotate-left mr-1.5"></i> UPDATE REAL-TIME
        </div>
    </div>

    <div class="bg-white p-8 rounded-[32px] border border-slate-100 shadow-sm hover:shadow-xl hover:shadow-blue-600/5 transition-all duration-300">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-slate-400 text-xs font-bold uppercase tracking-widest mb-2">Anggota Aktif</p>
                <h3 class="text-4xl font-black text-slate-900 tracking-tighter">{{ $total_anggota }}</h3>
            </div>
            <div class="w-12 h-12 bg-cyan-50 text-cyan-500 rounded-2xl flex items-center justify-center text-xl">
                <i class="fa-solid fa-users"></i>
            </div>
        </div>
        <div class="mt-6 flex items-center text-[10px] font-bold text-cyan-600 bg-cyan-50 px-3 py-1.5 rounded-full w-fit">
            <i class="fa-solid fa-shield-check mr-1.5"></i> DATA TERVERIFIKASI
        </div>
    </div>
</div>

<div class="mt-12 bg-gradient-to-br from-blue-600 to-blue-800 p-12 rounded-[40px] shadow-2xl shadow-blue-600/20 relative overflow-hidden">
    <div class="absolute top-0 right-0 w-64 h-64 bg-white/10 rounded-full -mr-20 -mt-20 blur-3xl"></div>
    <div class="relative z-10 max-w-2xl">
        <h1 class="text-4xl font-black text-white leading-tight italic">
            Sistem Perpustakaan<br>
            <span class="text-cyan-300 underline decoration-white/30">Generasi Digital</span>
        </h1>
        <p class="mt-6 text-blue-100 font-medium leading-relaxed opacity-80">
            <i class="fa-solid fa-hand-wave mr-2"></i> Halo Admin! Kelola data buku dan pantau aktivitas anggota BIBLIOX dengan antarmuka modern yang lebih bersih dan efisien.
        </p>
    </div>
</div>
@endsection