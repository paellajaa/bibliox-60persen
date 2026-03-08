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
        // Mengambil data yang butuh tindakan Admin
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

        return back()->with('success', 'Peminjaman telah disetujui! Buku resmi dipinjam.');
    }

    /**
     * SISI ADMIN: Menolak Peminjaman Baru
     */
    public function tolak($id)
    {
        $pinjam = Peminjaman::findOrFail($id);
        
        // Kembalikan stok karena batal pinjam (Gunakan ID agar akurat)
        $buku = Buku::find($pinjam->buku_id);
        if ($buku) {
            $buku->increment('stok');
        }

        $pinjam->update(['status' => 'ditolak']);
        
        return back()->with('success', 'Permintaan ditolak dan stok buku telah dikembalikan.');
    }

    /**
     * SISI SISWA: Mengajukan pengembalian (Lapor Kondisi)
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

        return back()->with('success', 'Buku berhasil dikembalikan, tunggu verifikasi admin!');
    }

    /**
     * SISI ADMIN: Verifikasi Akhir Pengembalian (Cek Kondisi & Denda)
     */
    public function verifikasiKembali(Request $request, $id)
    {
        $peminjaman = Peminjaman::findOrFail($id);

        if ($request->kondisi == 'rusak' || $request->kondisi == 'hilang') {
            $peminjaman->status = 'rusak';
            $peminjaman->total_denda = $request->denda;
        } else {
            $peminjaman->status = 'kembali';
            
            // Tambahkan stok buku kembali hanya jika kondisi bagus
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
            'total_denda' => 0,
            'catatan_admin' => $pinjam->catatan_admin . ' | LUNAS PADA ' . now()->format('d/m/Y H:i')
        ]);

        return back()->with('success', 'Pembayaran denda berhasil dikonfirmasi! Status siswa sudah bersih.');
    }

    /**
     * SISI ANGGOTA: Daftar buku saya
     */
    public function bukuSaya()
    {
        // PENTING: Gunakan 'pengguna_id' sesuai database kamu
        $peminjaman = Peminjaman::with('buku')
            ->where('pengguna_id', Auth::user()->pengenal)
            ->latest()
            ->get();

        return view('anggota.buku-saya', compact('peminjaman'));
    }
}