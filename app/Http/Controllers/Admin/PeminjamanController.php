<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use App\Models\Buku;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PeminjamanController extends Controller
{
    /**
     * SISI ADMIN: Menampilkan daftar permintaan (Pinjam, Kembali, & Denda)
     */
    public function index()
    {
        $permintaan = Peminjaman::with(['user', 'buku'])
            ->whereIn('status', ['menunggu', 'proses_kembali', 'dipinjam', 'rusak'])
            ->latest()
            ->get();

        return view('admin.peminjaman.index', compact('permintaan'));
    }

    /**
     * SISI ADMIN: Menyetujui Peminjaman Baru
     */
    public function setujui($id)
    {
        $pinjam = Peminjaman::findOrFail($id);
        
        $pinjam->update([
            'status' => 'dipinjam',
            'tanggal_pinjam' => now(),
            'tanggal_jatuh_tempo' => now()->addDays($pinjam->durasi_hari)
        ]);

        return back()->with('success', 'Peminjaman telah disetujui!');
    }

    /**
     * SISI ADMIN: Menolak Peminjaman Baru
     */
    public function tolak($id)
    {
        $pinjam = Peminjaman::findOrFail($id);
        
        $buku = Buku::find($pinjam->buku_id);
        if ($buku) {
            $buku->increment('stok');
        }

        $pinjam->update(['status' => 'ditolak']);
        
        return back()->with('success', 'Permintaan ditolak.');
    }

    /**
     * SISI SISWA: Mengajukan pengembalian
     */
    public function ajukanPengembalian(Request $request, $id)
    {
        $request->validate([
            'catatan_siswa' => 'nullable|string'
        ]);

        $p = Peminjaman::findOrFail($id);
        $p->update([
            'status' => 'proses_kembali',
            'catatan_siswa' => $request->catatan_siswa,
            'tanggal_kembali' => now()
        ]);

        return back()->with('success', 'Berhasil dikembalikan, tunggu verifikasi admin!');
    }

    /**
     * SISI ADMIN: Verifikasi Akhir Pengembalian
     */
    public function verifikasiKembali(Request $request, $id)
    {
        $peminjaman = Peminjaman::findOrFail($id);

        if ($request->kondisi == 'rusak' || $request->kondisi == 'hilang') {
            $peminjaman->status = 'rusak';
            $peminjaman->denda = $request->denda; 
        } else {
            $peminjaman->status = 'kembali';
            $buku = Buku::find($peminjaman->buku_id);
            if($buku) {
                $buku->increment('stok');
            }
        }

        $peminjaman->catatan_admin = $request->catatan_admin;
        $peminjaman->save();

        return back()->with('success', 'Buku berhasil diverifikasi!');
    }

    /**
     * SISI ADMIN: Konfirmasi Pembayaran Denda
     */
    public function bayarDenda($id)
    {
        $pinjam = Peminjaman::findOrFail($id);
        $pinjam->update([
            'status' => 'kembali',
            'denda' => 0, 
            'catatan_admin' => $pinjam->catatan_admin . ' | LUNAS PADA ' . now()->format('d/m/Y H:i')
        ]);

        return back()->with('success', 'Denda lunas!');
    }

    /**
     * SISI ANGGOTA: Daftar buku saya
     */
    public function bukuSaya()
    {
        $peminjaman = Peminjaman::with('buku')
            ->where('user_id', Auth::user()->pengenal) 
            ->latest()
            ->get();

        return view('anggota.buku-saya', compact('peminjaman'));
    }
}