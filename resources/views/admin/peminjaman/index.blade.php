@extends('layouts.admin')

@section('content')
<div class="space-y-10 animate-fadeIn" x-data="{ openDenda: false, formAction: '', selectedJudul: '', selectedSiswa: '' }">
    
    <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-slate-100 flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-black text-slate-900 uppercase italic tracking-tighter leading-none">Pusat Verifikasi 📥</h2>
            <p class="text-slate-500 font-bold text-sm mt-2">Kelola permintaan pinjam, cek kondisi, dan pelunasan denda.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-emerald-500 text-white px-6 py-4 rounded-2xl shadow-lg flex items-center gap-3 animate-bounce">
            <span class="text-xl">✅</span>
            <p class="font-bold text-sm">{{ session('success') }}</p>
        </div>
    @endif

    <div class="space-y-4">
        <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] px-6">Request Peminjaman Baru</h3>
        <div class="bg-white rounded-[2.5rem] overflow-hidden shadow-sm border border-slate-100">
            <table class="w-full text-left">
                <thead class="bg-slate-50 border-b border-slate-100">
                    <tr>
                        <th class="px-8 py-5 text-[10px] font-black uppercase text-blue-600 tracking-widest">Siswa</th>
                        <th class="px-8 py-5 text-[10px] font-black uppercase text-blue-600 tracking-widest">Buku</th>
                        <th class="px-8 py-5 text-[10px] font-black uppercase text-blue-600 tracking-widest">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($permintaan->where('status', 'menunggu') as $p)
                    <tr class="hover:bg-slate-50/50 transition-all">
                        <td class="px-8 py-6 uppercase font-black text-slate-900 text-sm">{{ $p->user->nama }}</td>
                        <td class="px-8 py-6">
                            <p class="font-bold text-slate-700 text-sm">{{ $p->buku->judul }}</p>
                            <p class="text-[10px] text-cyan-600 font-black italic uppercase">Durasi: {{ $p->durasi_hari }} Hari</p>
                        </td>
                        <td class="px-8 py-6 text-sm">
                            <div class="flex gap-2">
                                <form action="{{ route('admin.peminjaman.setujui', $p->id) }}" method="POST">
                                    @csrf
                                    <button class="bg-emerald-500 text-white px-5 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-widest hover:scale-105 transition-all shadow-lg shadow-emerald-100">Setujui</button>
                                </form>
                                <form action="{{ route('admin.peminjaman.tolak', $p->id) }}" method="POST">
                                    @csrf
                                    <button class="bg-white border border-slate-200 text-slate-400 px-5 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-widest hover:text-red-500 transition-all">Tolak</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="py-12 text-center text-blue-500 italic font-bold uppercase text-[10px] tracking-widest">Tidak ada request baru</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="space-y-4">
        <h3 class="text-[10px] font-black text-orange-400 uppercase tracking-[0.3em] px-6">Verifikasi Kondisi Pengembalian</h3>
        <div class="bg-orange-50/50 rounded-[2.5rem] overflow-hidden border-2 border-dashed border-orange-200">
            <table class="w-full text-left">
                <thead class="bg-orange-100/50 border-b border-orange-200">
                    <tr>
                        <th class="px-8 py-5 text-[10px] font-black uppercase text-orange-600">Siswa & Laporan</th>
                        <th class="px-8 py-5 text-[10px] font-black uppercase text-orange-600">Buku</th>
                        <th class="px-8 py-5 text-[10px] font-black uppercase text-orange-600 text-right">Aksi Pustakawan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-orange-100">
                    @forelse($permintaan->where('status', 'proses_kembali') as $p)
                    <tr>
                        <td class="px-8 py-6">
                            <p class="font-black text-slate-900 uppercase text-sm">{{ $p->user->nama }}</p>
                            <div class="bg-white p-3 rounded-xl border border-orange-200 mt-2">
                                <p class="text-[9px] font-black text-orange-400 uppercase italic mb-1">Catatan Siswa:</p>
                                <p class="text-xs text-slate-600 font-bold italic">
                                    "{{ $p->catatan_siswa ?? 'Tidak ada catatan' }}"
                                </p>
                            </div>
                        </td>
                        <td class="px-8 py-6 font-bold text-slate-700 text-sm">{{ $p->buku->judul }}</td>
                        <td class="px-8 py-6 text-right">
                            <button @click="openDenda = true; formAction = '{{ route('admin.peminjaman.verifikasi-kembali', $p->id) }}'; selectedId = '{{ $p->id }}'; selectedJudul = '{{ $p->buku->judul }}'; selectedSiswa = '{{ $p->user->nama }}'" 
                                    style="background-color: #f97316 !important; color: white !important;"
                                    class="px-6 py-3 rounded-2xl text-[10px] font-black uppercase tracking-widest shadow-xl shadow-orange-200 hover:opacity-90 transition-all">
                                Periksa Buku
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="3" class="py-12 text-center text-orange-300 italic font-bold uppercase text-[10px] tracking-widest">Belum ada laporan pengembalian</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="space-y-4">
        <h3 class="text-[10px] font-black text-red-500 uppercase tracking-[0.3em] px-6">Daftar Tunggakan Denda 💸</h3>
        <div class="bg-white rounded-[2.5rem] overflow-hidden shadow-sm border border-red-100">
            <table class="w-full text-left">
                <thead class="bg-red-50 border-b border-red-100">
                    <tr>
                        <th class="px-8 py-5 text-[10px] font-black uppercase text-red-600">Siswa</th>
                        <th class="px-8 py-5 text-[10px] font-black uppercase text-red-600">Total Tagihan</th>
                        <th class="px-8 py-5 text-[10px] font-black uppercase text-red-600 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-red-50">
                    @php
                        $denda_aktif = \App\Models\Peminjaman::with(['user', 'buku'])->where('status', 'rusak')->get();
                    @endphp
                    @forelse($denda_aktif as $d)
                    <tr>
                        <td class="px-8 py-6">
                            <p class="font-black text-slate-900 text-sm uppercase">{{ $d->user->nama }}</p>
                            <p class="text-[9px] text-slate-400 font-bold">Buku: {{ $d->buku->judul }}</p>
                        </td>
                        <td class="px-8 py-6 font-black text-red-600 text-lg tracking-tighter">Rp {{ number_format($d->denda, 0, ',', '.') }}</td>
                        <td class="px-8 py-6 text-right">
                            <form action="{{ route('admin.peminjaman.bayar-denda', $d->id) }}" method="POST">
                                @csrf
                                <button type="submit" 
                                        style="background-color: #dc2626 !important; color: white !important;"
                                        class="px-6 py-3 rounded-2xl text-[10px] font-black uppercase tracking-widest hover:bg-slate-900 transition-all shadow-lg shadow-red-100">
                                    Konfirmasi Lunas
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="3" class="py-12 text-center text-slate-300 font-bold uppercase text-[10px]">Semua tagihan lunas</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="space-y-4">
        <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] px-6">Buku Masih Dibawa Siswa</h3>
        <div class="bg-white rounded-[2.5rem] overflow-hidden shadow-sm border border-slate-100 opacity-60">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-900 border-b border-slate-800">
                    <tr>
                        <th class="px-8 py-5 text-[10px] font-black uppercase text-slate-400">Peminjam</th>
                        <th class="px-8 py-5 text-[10px] font-black uppercase text-slate-400">Judul Buku</th>
                        <th class="px-8 py-5 text-[10px] font-black uppercase text-slate-400">Batas Kembali</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($permintaan->where('status', 'dipinjam') as $p)
                    <tr>
                        <td class="px-8 py-6 font-bold text-slate-900 uppercase">{{ $p->user->nama }}</td>
                        <td class="px-8 py-6 text-slate-500 font-medium">{{ $p->buku->judul }}</td>
                        <td class="px-8 py-6 uppercase font-black text-[10px] text-slate-500">
                            {{ \Carbon\Carbon::parse($p->tanggal_jatuh_tempo)->format('d/m/Y') }}
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="3" class="py-12 text-center text-slate-300 font-bold uppercase text-[10px]">Kosong</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div x-show="openDenda" class="fixed inset-0 z-[100] flex items-center justify-center bg-slate-900/60 backdrop-blur-sm p-4" x-transition.opacity x-cloak>
        <div @click.away="openDenda = false" class="bg-white rounded-[3rem] p-10 w-full max-w-lg shadow-2xl animate-fadeIn">
            <h3 class="text-2xl font-black text-slate-900 uppercase italic tracking-tighter leading-none mb-2">Verifikasi Buku</h3>
            <p class="text-slate-400 text-xs font-bold mb-8" x-text="selectedSiswa + ' - ' + selectedJudul"></p>
            
            <form :action="formAction" method="POST">
                @csrf
                <div class="space-y-6">
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase mb-3">Kondisi Fisik Buku</label>
                        <select name="kondisi" required class="w-full bg-slate-50 border-2 border-slate-100 rounded-2xl px-6 py-4 font-bold text-slate-700 focus:border-cyan-500 outline-none transition-all">
                            <option value="bagus">Kondisi Bagus (Tanpa Cacat)</option>
                            <option value="rusak">Rusak (Sobek / Coretan)</option>
                            <option value="hilang">Buku Hilang</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase mb-3">Jumlah Denda (Jika ada)</label>
                        <div class="relative">
                            <span class="absolute left-6 top-4 font-black text-slate-400">Rp</span>
                            <input type="number" name="denda" value="0" class="w-full bg-slate-50 border-2 border-slate-100 rounded-2xl pl-14 pr-6 py-4 font-black text-slate-900 focus:border-orange-500 outline-none transition-all">
                        </div>
                    </div>

                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase mb-3">Catatan Pustakawan</label>
                        <textarea name="catatan_admin" rows="2" class="w-full bg-slate-50 border-2 border-slate-100 rounded-2xl px-6 py-4 text-sm font-medium focus:border-cyan-500 outline-none transition-all" placeholder="Contoh: Halaman 12 sobek sedikit..."></textarea>
                    </div>
                </div>

                <div class="flex gap-4 mt-10">
                    <button type="button" @click="openDenda = false" class="flex-1 py-5 bg-slate-100 text-slate-500 rounded-2xl font-black text-[10px] uppercase tracking-widest">Batal</button>
                    <button type="submit" 
                            style="background-color: #0f172a !important; color: white !important;"
                            class="flex-1 py-5 rounded-2xl font-black text-[10px] uppercase tracking-widest shadow-xl shadow-slate-200 hover:opacity-90 transition-all">
                        Selesaikan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
@endsection