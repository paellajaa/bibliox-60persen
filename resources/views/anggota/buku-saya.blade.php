@extends('layouts.admin')

@section('content')
<div class="space-y-6 animate-fadeIn" x-data="{ openReturn: false, pinjamId: '', bukuJudul: '', openBayarModal: false, bayarNominal: '', bayarBuku: '' }">
    
    <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-slate-100 flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-black text-slate-900 uppercase italic tracking-tighter">
                Koleksi Buku Saya <i class="fa-solid fa-book-bookmark ml-2 text-cyan-500"></i>
            </h2>
            <p class="text-slate-500 font-bold text-sm mt-1">Pantau status pinjaman dan tagihan denda kamu di sini.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-emerald-500 text-white px-6 py-4 rounded-2xl shadow-lg flex items-center gap-3 animate-bounce">
            <i class="fa-solid fa-circle-check text-xl"></i>
            <p class="font-bold text-sm">{{ session('success') }}</p>
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($peminjaman as $p)
        <div class="bg-white p-6 rounded-[2.5rem] border border-slate-100 shadow-sm flex flex-col gap-4 relative overflow-hidden group hover:border-cyan-500 transition-all duration-300">
            
            {{-- Badge Status --}}
            <div class="absolute top-0 right-0 px-6 py-2 rounded-bl-3xl font-black text-[10px] uppercase tracking-widest z-10
                {{ $p->status == 'dipinjam' ? 'bg-emerald-500 text-white shadow-lg' : '' }}
                {{ $p->status == 'menunggu' ? 'bg-orange-400 text-white shadow-lg' : '' }}
                {{ $p->status == 'proses_kembali' ? 'bg-cyan-500 text-white shadow-lg' : '' }}
                {{ $p->status == 'kembali' ? 'bg-slate-900 text-white' : '' }}
                {{ $p->status == 'rusak' ? 'bg-red-600 text-white' : '' }}">
                <i class="fa-solid fa-circle-info mr-1"></i> {{ str_replace('_', ' ', $p->status) }}
            </div>

            <div class="flex gap-5 items-start pt-4">
                <div class="w-24 h-32 bg-slate-100 rounded-2xl overflow-hidden flex-shrink-0 shadow-inner group-hover:scale-105 transition-transform duration-500">
                    <img src="{{ asset('covers/' . ($p->buku->cover ?? '')) }}" class="w-full h-full object-cover" onerror="this.src='https://placehold.co/200x300?text=No+Cover'">
                </div>
                <div class="flex-1">
                    {{-- PENGAMAN: Cek jika buku ada --}}
                    <h4 class="font-black text-slate-900 leading-tight uppercase text-sm mb-1 truncate w-40">
                        {{ $p->buku->judul ?? 'Buku Telah Dihapus' }}
                    </h4>
                    <p class="text-[10px] text-slate-400 font-bold mb-4 italic text-sm">
                        Oleh: {{ $p->buku->penulis ?? 'Sistem' }}
                    </p>
                    
                    @if($p->status == 'dipinjam')
                        <div class="bg-emerald-50 p-3 rounded-2xl border border-emerald-100">
                            <p class="text-[8px] text-emerald-600 font-black uppercase mb-1">Batas Kembali:</p>
                            <p class="text-xs font-black text-emerald-700">
                                <i class="fa-solid fa-calendar-day mr-1"></i> 
                                {{ $p->tanggal_jatuh_tempo ? \Carbon\Carbon::parse($p->tanggal_jatuh_tempo)->translatedFormat('d F Y') : '-' }}
                            </p>
                        </div>
                        <button type="button" @click="openReturn = true; pinjamId = '{{ $p->id }}'; bukuJudul = '{{ $p->buku->judul ?? 'Buku' }}'" 
                                class="w-full mt-4 py-3 bg-slate-900 text-white rounded-2xl font-black text-[9px] uppercase tracking-widest hover:bg-cyan-600 transition-all flex items-center justify-center gap-2">
                            <i class="fa-solid fa-arrow-rotate-left"></i> Kembalikan Buku
                        </button>

                    @elseif($p->status == 'rusak')
                        <div class="bg-red-50 p-4 rounded-3xl border-2 border-red-100 relative overflow-hidden">
                            <p class="text-[9px] text-red-600 font-black uppercase tracking-widest mb-1">Total Denda:</p>
                            <p class="text-xl font-black text-red-700 leading-none">Rp {{ number_format($p->denda, 0, ',', '.') }}</p>
                            
                            <div class="mt-3 pt-3 border-t border-red-100">
                                <p class="text-[9px] text-red-500 font-bold italic mb-3 leading-tight">
                                    <i class="fa-solid fa-comment-dots mr-1"></i> "{{ $p->catatan_admin ?? 'Buku rusak/hilang' }}"
                                </p>
                                <button type="button" @click="openBayarModal = true; bayarNominal = '{{ number_format($p->denda, 0, ',', '.') }}'; bayarBuku = '{{ $p->buku->judul ?? 'Buku' }}'" 
                                        class="w-full py-2.5 bg-red-600 text-white rounded-xl font-black text-[8px] uppercase tracking-widest shadow-lg hover:opacity-90 transition-all flex items-center justify-center gap-2">
                                    <i class="fa-solid fa-credit-card"></i> Cara Bayar
                                </button>
                            </div>
                        </div>

                    @elseif($p->status == 'menunggu')
                        <div class="bg-orange-50 p-4 rounded-2xl border border-orange-100 italic text-center">
                            <p class="text-[10px] text-orange-600 font-bold leading-relaxed">
                                <i class="fa-solid fa-hourglass-start mr-1 animate-spin-slow"></i> Menunggu persetujuan admin...
                            </p>
                        </div>

                    @elseif($p->status == 'proses_kembali')
                        <div class="bg-cyan-50 p-4 rounded-2xl border border-cyan-100 italic text-center">
                            <p class="text-[10px] text-cyan-600 font-bold leading-relaxed">
                                <i class="fa-solid fa-magnifying-glass mr-1"></i> Sedang diperiksa admin...
                            </p>
                        </div>

                    @elseif($p->status == 'kembali')
                        <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100 text-center">
                            <p class="text-[10px] text-slate-400 font-black uppercase italic tracking-widest">
                                <i class="fa-solid fa-check-double mr-1"></i> Selesai Dikembalikan
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full py-20 text-center bg-white rounded-[3rem] border-2 border-dashed border-slate-200">
            <i class="fa-solid fa-folder-open text-5xl text-slate-200 mb-4 block"></i>
            <p class="text-slate-400 font-black uppercase tracking-widest italic">Kamu belum meminjam buku apapun.</p>
            <a href="{{ route('anggota.dashboard') }}" class="inline-block mt-6 px-8 py-4 bg-cyan-600 text-white rounded-2xl font-black uppercase text-[10px] tracking-[0.2em] shadow-lg shadow-cyan-100 hover:scale-105 transition-all">
                Cari Buku Sekarang <i class="fa-solid fa-arrow-right ml-1"></i>
            </a>
        </div>
        @endforelse
    </div>

    {{-- MODAL KEMBALIKAN BUKU --}}
    <div x-show="openReturn" class="fixed inset-0 z-[100] flex items-center justify-center bg-slate-900/60 backdrop-blur-sm p-4" x-transition.opacity x-cloak style="display: none;">
        <div @click.away="openReturn = false" class="bg-white rounded-[3rem] p-10 w-full max-w-md shadow-2xl transform transition-all animate-fadeIn">
            <div class="text-center mb-8">
                <div class="w-16 h-16 bg-orange-50 text-orange-500 rounded-2xl flex items-center justify-center text-2xl mx-auto mb-4">
                    <i class="fa-solid fa-box-archive"></i>
                </div>
                <h3 class="text-2xl font-black text-slate-900 uppercase italic tracking-tighter leading-none">Kembalikan Buku</h3>
                <p class="text-slate-400 text-sm font-bold mt-2" x-text="bukuJudul"></p>
            </div>
            <form :action="`/anggota/buku-saya/ajukan-kembali/${pinjamId}`" method="POST">
                @csrf
                <div class="mb-8">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-4">Lapor kondisi buku saat ini:</label>
                    <textarea name="catatan_siswa" required rows="3" class="w-full bg-slate-50 border-2 border-slate-100 rounded-3xl px-6 py-4 text-sm font-bold text-slate-700 outline-none focus:border-orange-500 shadow-inner" placeholder="Contoh: Aman bang, mulus..."></textarea>
                </div>
                <div class="flex gap-4">
                    <button type="button" @click="openReturn = false" class="flex-1 py-5 bg-slate-100 text-slate-500 rounded-2xl font-black text-[11px] uppercase tracking-widest">Batal</button>
                    <button type="submit" class="flex-1 py-5 bg-orange-500 text-white rounded-2xl font-black text-[11px] uppercase tracking-widest shadow-xl shadow-orange-100 hover:bg-orange-600 transition-all">Kirim Laporan</button>
                </div>
            </form>
        </div>
    </div>

    {{-- MODAL CARA BAYAR --}}
    <div x-show="openBayarModal" class="fixed inset-0 z-[100] flex items-center justify-center bg-slate-900/70 backdrop-blur-md p-4" x-transition.opacity x-cloak style="display: none;">
        <div @click.away="openBayarModal = false" class="bg-white rounded-[3rem] p-8 w-full max-w-md shadow-2xl relative animate-fadeIn transform transition-all">
            <div class="text-center mb-6">
                <div class="w-16 h-16 bg-blue-50 text-blue-500 rounded-2xl flex items-center justify-center text-2xl mx-auto mb-4 shadow-inner border-4 border-white">
                    <i class="fa-solid fa-landmark"></i>
                </div>
                <h3 class="text-2xl font-black text-slate-900 uppercase italic tracking-tighter mb-1">Cara Pembayaran</h3>
                <p class="text-slate-400 text-xs font-bold" x-text="bayarBuku"></p>
            </div>
            <div class="bg-red-50 border-2 border-red-100 rounded-[2rem] p-5 mb-6 shadow-inner text-center">
                <p class="text-[10px] font-black text-red-400 uppercase tracking-widest mb-1">Total yang harus dibayar</p>
                <p class="text-3xl font-black text-red-600 tracking-tighter">Rp <span x-text="bayarNominal"></span></p>
            </div>
            <div class="space-y-4 mb-8">
                <div class="bg-slate-50 border-2 border-slate-100 rounded-2xl p-4 flex gap-4 items-center">
                    <div class="bg-white p-3 rounded-xl shadow-sm text-blue-500 text-xl"><i class="fa-solid fa-money-check-dollar"></i></div>
                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Transfer Bank</p>
                        <p class="font-black text-slate-900 text-lg">BCA - 123 456 7890</p>
                        <p class="text-xs font-bold text-slate-500">a.n. Perpustakaan Bibliox</p>
                    </div>
                </div>
                <div class="bg-slate-50 border-2 border-slate-100 rounded-2xl p-4 flex gap-4 items-center">
                    <div class="bg-white p-3 rounded-xl shadow-sm text-emerald-500 text-xl"><i class="fa-solid fa-money-bill-transfer"></i></div>
                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Bayar Tunai</p>
                        <p class="font-black text-slate-900 text-sm">Datang ke Ruang Pustakawan</p>
                    </div>
                </div>
            </div>
            <button type="button" @click="openBayarModal = false" class="w-full py-5 bg-slate-900 text-white rounded-2xl font-black text-[10px] uppercase tracking-widest shadow-xl hover:bg-slate-800 transition-all">Saya Mengerti</button>
        </div>
    </div>

</div>

<script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
@endsection