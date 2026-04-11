@extends('layouts.admin')

@section('content')
<div class="space-y-8 animate-fadeIn">
    <div class="flex flex-col md:flex-row gap-6">
        <div class="flex-1 bg-white p-8 rounded-[2.5rem] shadow-sm border border-slate-100 flex flex-col justify-center">
            <h2 class="text-2xl font-black text-slate-900 uppercase italic tracking-tighter leading-none">Manajemen Akun 👥</h2>
            <p class="text-slate-500 font-bold text-sm mt-2">Pantau dan kelola semua pengguna terdaftar di BiblioX.</p>
        </div>

        <div class="w-full md:w-56 bg-white p-8 rounded-[2.5rem] shadow-sm border border-slate-100 flex flex-col items-center justify-center relative overflow-hidden">
            <div class="absolute top-0 left-0 w-1 h-full bg-cyan-500"></div>
            <p class="text-[10px] font-black uppercase text-slate-400 mb-1 tracking-widest">Total Admin</p>
            <p class="text-4xl font-black text-slate-900">{{ $totalAdmin ?? 0 }}</p>
        </div>

        <div class="w-full md:w-56 bg-white p-8 rounded-[2.5rem] shadow-sm border border-slate-100 flex flex-col items-center justify-center relative overflow-hidden">
            <div class="absolute top-0 left-0 w-1 h-full bg-orange-500"></div>
            <p class="text-[10px] font-black uppercase text-slate-400 mb-1 tracking-widest">Total Anggota</p>
            <p class="text-4xl font-black text-slate-900">{{ $totalAnggota ?? 0 }}</p>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-slate-900 text-white px-6 py-4 rounded-2xl shadow-lg font-bold flex items-center gap-3 italic tracking-tight">
            <span class="text-cyan-400 text-xl">✓</span> {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-[2.5rem] overflow-hidden shadow-sm border border-slate-100">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-slate-50/50 border-b border-slate-100">
                    <tr>
                        <th class="px-8 py-6 text-[10px] font-black uppercase text-slate-400 tracking-[0.2em]">Nama User</th>
                        <th class="px-8 py-6 text-[10px] font-black uppercase text-slate-400 tracking-[0.2em]">ID / Pengenal</th>
                        <th class="px-8 py-6 text-[10px] font-black uppercase text-slate-400 tracking-[0.2em]">Peran</th>
                        <th class="px-8 py-6 text-[10px] font-black uppercase text-slate-400 tracking-[0.2em] text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @foreach($users as $user) 
                    <tr class="hover:bg-slate-50/30 transition-all group">
                        <td class="px-8 py-6">
                            <span class="font-black text-slate-900 uppercase text-sm tracking-tight">{{ $user->nama }}</span>
                        </td>
                        <td class="px-8 py-6">
                            <span class="font-bold text-slate-400 text-xs tracking-widest">{{ $user->pengenal }}</span>
                        </td>
                        <td class="px-8 py-6">
                            <span class="px-3 py-1 rounded-full font-black text-[9px] uppercase tracking-tighter
                                {{ $user->peran == 'admin' ? 'bg-cyan-50 text-cyan-600' : 'bg-orange-50 text-orange-600' }}">
                                {{ $user->peran }}
                            </span>
                        </td>
                        <td class="px-8 py-6 text-right">
                                <span class="text-slate-300 italic text-[10px] font-bold uppercase tracking-widest">Owner</span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection