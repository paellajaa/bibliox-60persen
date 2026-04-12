@extends('layouts.admin') 

@section('content')
<div class="space-y-8 animate-fadeIn" x-data="{ openModal: false, bukuId: '', bukuJudul: '' }">
    
    {{-- Header Sapaan --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-8 rounded-[2.5rem] border border-slate-100 shadow-sm">
        <div>
            <h2 class="text-3xl font-black text-slate-900 italic uppercase tracking-tighter leading-tight">
                Halo, {{ Auth::user()->nama }}! <i class="fa-solid fa-hand-wave ml-2 text-cyan-500"></i>
            </h2>
            <p class="text-slate-500 font-bold text-lg">Siap menjelajahi dunia pengetahuan hari ini?</p>
        </div>
    </div>

    {{-- Pencarian & Filter --}}
    <div class="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm">
        <form action="{{ route('anggota.dashboard') }}" method="GET" class="flex flex-col md:flex-row gap-4">
            <div class="flex-1 relative">
                <i class="fa-solid fa-magnifying-glass absolute left-5 top-1/2 -translate-y-1/2 text-slate-400"></i>
                <input type="text" name="search" placeholder="Cari judul buku atau penulis..." value="{{ request('search') }}"
                       class="w-full pl-14 pr-6 py-4 bg-slate-50 border-2 border-transparent focus:border-cyan-500 rounded-2xl outline-none transition-all font-bold text-slate-700">
            </div>
            <button type="submit" class="px-8 py-4 bg-cyan-600 text-white rounded-2xl font-black uppercase tracking-widest hover:bg-cyan-700 transition-all shadow-lg shadow-cyan-100">
                Cari Koleksi
            </button>
        </form>
    </div>

    {{-- Grid Buku --}}
    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-8">
        @forelse($all_books as $buku)
        <div class="group bg-white p-4 rounded-[2.5rem] border-2 border-transparent hover:border-cyan-500 transition-all duration-500 shadow-sm hover:shadow-xl">
            <div class="aspect-[3/4] bg-slate-50 rounded-[2rem] mb-5 overflow-hidden relative shadow-inner">
                @if($buku->cover)
                    <img src="{{ asset('covers/' . $buku->cover) }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                @else
                    <div class="w-full h-full flex flex-col items-center justify-center text-slate-300 bg-slate-100">
                        <i class="fa-solid fa-image text-3xl mb-2"></i>
                        <span class="font-black text-xs italic uppercase">No Cover</span>
                    </div>
                @endif
                
                <div class="absolute top-3 right-3 px-3 py-1.5 bg-white/90 backdrop-blur-md rounded-xl text-[10px] font-black {{ $buku->stok > 0 ? 'text-cyan-600' : 'text-red-500' }}">
                    <i class="fa-solid {{ $buku->stok > 0 ? 'fa-check' : 'fa-xmark' }} mr-1"></i> {{ $buku->stok > 0 ? 'Tersedia' : 'Habis' }}
                </div>
            </div>

            <div class="px-2">
                <h4 class="text-slate-900 font-black truncate text-sm mb-1 uppercase tracking-tight">{{ $buku->judul }}</h4>
                <p class="text-slate-400 text-[10px] mb-4 font-bold italic">Oleh: {{ $buku->penulis }}</p>
                
                <button @click="openModal = true; bukuId = '{{ $buku->kode_buku }}'; bukuJudul = '{{ $buku->judul }}'"
                        {{ $buku->stok <= 0 ? 'disabled' : '' }}
                        class="w-full py-3 {{ $buku->stok > 0 ? 'bg-slate-900 text-white hover:bg-cyan-600 shadow-lg shadow-slate-200' : 'bg-slate-100 text-slate-400 cursor-not-allowed' }} rounded-2xl text-[10px] font-black transition-all uppercase tracking-widest flex items-center justify-center gap-2">
                    @if($buku->stok > 0)
                        <i class="fa-solid fa-bookmark"></i> Pinjam Buku
                    @else
                        <i class="fa-solid fa-ban"></i> Stok Habis
                    @endif
                </button>
            </div>
        </div>
        @empty
            <div class="col-span-full text-center py-20 bg-white rounded-[3rem] border-2 border-dashed border-slate-200">
                <i class="fa-solid fa-book-open text-4xl text-slate-300 mb-4 block"></i>
                <p class="text-slate-400 font-bold uppercase tracking-widest text-sm">Belum ada koleksi buku yang tersedia.</p>
            </div>
        @endforelse
    </div>

    {{-- MODAL PINJAM --}}
    <div x-show="openModal" class="fixed inset-0 z-[100] flex items-center justify-center bg-slate-900/60 backdrop-blur-sm p-4" x-transition.opacity x-cloak>
        <div @click.away="openModal = false" class="bg-white rounded-[3rem] p-10 w-full max-w-md shadow-2xl">
            <div class="text-center mb-8">
                <div class="w-16 h-16 bg-cyan-50 text-cyan-500 rounded-2xl flex items-center justify-center text-2xl mx-auto mb-4">
                    <i class="fa-solid fa-calendar-check"></i>
                </div>
                <h3 class="text-2xl font-black text-slate-900 uppercase italic tracking-tighter leading-none">Konfirmasi Pinjam</h3>
                <p class="text-slate-400 text-sm font-bold mt-2" x-text="bukuJudul"></p>
            </div>
            
            <form :action="'/anggota/pinjam/' + bukuId" method="POST">
                @csrf
                <div class="mb-8 text-center">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-4">Berapa hari ingin dipinjam?</label>
                    <input type="number" name="durasi" min="1" max="14" value="3" required
                           class="w-32 bg-slate-50 border-2 border-slate-100 rounded-3xl px-4 py-4 text-center text-4xl font-black text-cyan-600 focus:outline-none focus:border-cyan-300 transition-all">
                    <p class="text-[10px] text-red-400 font-bold italic mt-3">*Maksimal 14 hari</p>
                </div>

                <div class="flex gap-4">
                    <button type="button" @click="openModal = false" class="flex-1 py-4 bg-slate-100 text-slate-500 rounded-2xl font-black text-[10px] uppercase">Batal</button>
                    <button type="submit" class="flex-1 py-4 bg-slate-900 text-white rounded-2xl font-black text-[10px] uppercase hover:bg-cyan-600 shadow-xl transition-all">Kirim Request</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
@endsection