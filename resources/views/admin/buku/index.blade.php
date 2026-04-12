@extends('layouts.admin')

@section('content')
<div class="space-y-10 animate-fadeIn" x-data="{ showImageModal: false, selectedCover: '', selectedTitle: '' }">
    
    <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-slate-100 flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-black text-slate-900 uppercase italic tracking-tighter leading-none">
                Koleksi Perpustakaan <i class="fa-solid fa-book-open ml-2 text-cyan-500"></i>
            </h2>
            <p class="text-slate-500 font-bold text-sm mt-2">Total koleksi buku yang tersedia untuk dipinjam.</p>
        </div>
        <a href="{{ route('admin.buku.create') }}" class="bg-slate-900 text-white px-8 py-4 rounded-2xl font-black text-[10px] uppercase tracking-[0.2em] shadow-xl shadow-slate-200 hover:scale-105 transition-all">
            <i class="fa-solid fa-plus mr-2"></i> Tambah Buku
        </a>
    </div>

    @if(session('success'))
        <div class="bg-emerald-500 text-white px-6 py-4 rounded-2xl shadow-lg flex items-center gap-3 animate-bounce">
            <i class="fa-solid fa-circle-check text-xl"></i>
            <p class="font-bold text-sm">{{ session('success') }}</p>
        </div>
    @endif

    <div class="bg-white rounded-[3rem] overflow-hidden shadow-sm border border-slate-100">
        <table class="w-full text-left border-collapse">
            <thead class="bg-slate-50 border-b border-slate-100">
                <tr>
                    <th class="px-8 py-5 text-[10px] font-black uppercase text-slate-400 tracking-widest">Cover</th>
                    <th class="px-8 py-5 text-[10px] font-black uppercase text-slate-400 tracking-widest">Info Buku</th>
                    <th class="px-8 py-5 text-[10px] font-black uppercase text-slate-400 tracking-widest">Kategori</th>
                    <th class="px-8 py-5 text-[10px] font-black uppercase text-slate-400 tracking-widest text-center">Stok</th>
                    <th class="px-8 py-5 text-[10px] font-black uppercase text-slate-400 tracking-widest text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @forelse($buku as $b)
                <tr class="hover:bg-slate-50/50 transition-all group">
                    <td class="px-8 py-6">
                        <button type="button" @click="showImageModal = true; selectedCover = '{{ asset('covers/' . $b->cover) }}'; selectedTitle = '{{ $b->judul }}'" class="w-16 h-24 rounded-xl overflow-hidden shadow-md border-2 border-white group-hover:border-cyan-500 group-hover:scale-110 transition-all duration-300 cursor-zoom-in">
                            <img src="{{ asset('covers/' . $b->cover) }}" class="w-full h-full object-cover" alt="Cover">
                        </button>
                    </td>
                    <td class="px-8 py-6">
                        <p class="font-black text-slate-900 uppercase text-sm leading-tight mb-1">{{ $b->judul }}</p>
                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">{{ $b->penulis }} ({{ $b->tahun_terbit }})</p>
                    </td>
                    <td class="px-8 py-6">
                        <span class="bg-cyan-50 text-cyan-600 px-3 py-1 rounded-lg text-[9px] font-black uppercase tracking-widest">{{ $b->kategori }}</span>
                    </td>
                    <td class="px-8 py-6 text-center">
                        <div class="inline-flex items-baseline gap-1">
                            <span class="text-lg font-black text-slate-900">{{ $b->stok }}</span>
                            <span class="text-[9px] font-bold text-slate-400 uppercase">Pcs</span>
                        </div>
                    </td>
                    <td class="px-8 py-6">
                        <div class="flex justify-end gap-2">
                            <a href="{{ route('admin.buku.edit', $b->kode_buku) }}" class="w-10 h-10 flex items-center justify-center bg-slate-50 text-slate-400 rounded-xl hover:text-blue-500 hover:bg-blue-50 transition-all">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </a>
                            <form action="{{ route('admin.buku.destroy', $b->kode_buku) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus buku ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="w-10 h-10 flex items-center justify-center bg-slate-50 text-slate-400 rounded-xl hover:text-red-500 hover:bg-red-50 transition-all">
                                    <i class="fa-solid fa-trash-can"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="py-20 text-center"><p class="text-slate-300 font-black uppercase italic tracking-widest text-xs">Belum ada koleksi buku</p></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div x-show="showImageModal" class="fixed inset-0 z-[100] flex items-center justify-center bg-slate-900/80 backdrop-blur-md p-4" x-transition.opacity x-cloak style="display: none;">
        <div @click.away="showImageModal = false" class="bg-white rounded-[3rem] p-6 w-full max-w-sm shadow-2xl relative" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-90 translate-y-10" x-transition:enter-end="opacity-100 scale-100 translate-y-0">
            <button @click="showImageModal = false" class="absolute -top-3 -right-3 bg-red-500 text-white w-10 h-10 rounded-full flex items-center justify-center shadow-lg hover:bg-slate-900 transition-all duration-300 z-10 border-4 border-white">
                <i class="fa-solid fa-xmark font-black text-lg"></i>
            </button>
            <div class="rounded-[2rem] overflow-hidden border-4 border-slate-50 shadow-inner mb-6 bg-slate-50">
                <img :src="selectedCover" class="w-full h-auto max-h-[60vh] object-contain">
            </div>
            <div class="text-center">
                <h3 class="text-xl font-black text-slate-900 uppercase tracking-tighter italic" x-text="selectedTitle"></h3>
                <p class="text-[10px] font-bold text-cyan-600 mt-2 uppercase tracking-[0.3em]">Detail Sampul Buku</p>
            </div>
        </div>
    </div>
</div>
<script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
@endsection