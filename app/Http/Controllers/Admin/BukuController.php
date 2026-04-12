<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Buku;
use App\Models\Peminjaman; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class BukuController extends Controller
{
    /**
     * 1.7 PERF: Menampilkan daftar buku dengan Pagination
     */
    public function index()
    {
        // PERBAIKAN: Ubah $semua_buku menjadi $buku agar cocok dengan tampilan
        $buku = Buku::latest()->paginate(10); 
        return view('admin.buku.index', compact('buku'));
    }

    /**
     * Menampilkan form tambah buku
     */
    public function create()
    {
        return view('admin.buku.create');
    }

    /**
     * Menyimpan buku baru ke database
     */
    public function store(Request $request) 
    {
        $request->validate([
            'judul'   => 'required|string|max:255',
            'penulis' => 'required|string|max:255',
            'stok'    => 'required|numeric|min:0',
            'cover'   => 'required|image|mimes:jpeg,png,jpg,webp|max:2048', 
        ]);

        $data = $request->except('cover');
        if ($request->hasFile('cover')) {
            $file = $request->file('cover');
            $nama_file = time() . "_" . str_replace(' ', '_', $file->getClientOriginalName());
            $file->move(public_path('covers'), $nama_file); 
            $data['cover'] = $nama_file;
        }

        Buku::create($data);
        return redirect()->route('admin.buku.index')->with('success', 'Buku baru berhasil ditambahkan!');
    }

    /**
     * Menampilkan form edit
     */
    public function edit($id)
    {
        $buku = Buku::where('kode_buku', $id)->firstOrFail();
        return view('admin.buku.edit', compact('buku'));
    }

    /**
     * Memperbarui data buku
     */
    public function update(Request $request, $id)
    {
        $buku = Buku::where('kode_buku', $id)->firstOrFail();
        $validated = $request->validate([
            'judul'        => 'required|string|max:255',
            'penulis'      => 'required|string|max:255',
            'stok'         => 'required|numeric|min:0',
            'cover'        => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        if ($request->hasFile('cover')) {
            if ($buku->cover && File::exists(public_path('covers/' . $buku->cover))) {
                File::delete(public_path('covers/' . $buku->cover));
            }
            $file = $request->file('cover');
            $nama_file = time() . "_" . str_replace(' ', '_', $file->getClientOriginalName());
            $file->move(public_path('covers'), $nama_file);
            $validated['cover'] = $nama_file;
        }

        $buku->update($validated);
        return redirect()->route('admin.buku.index')->with('success', 'Informasi buku berhasil diperbarui!');
    }

    /**
     * Menghapus buku
     */
    public function destroy($id)
    {
        $buku = Buku::where('kode_buku', $id)->firstOrFail();
        if ($buku->cover && File::exists(public_path('covers/' . $buku->cover))) {
            File::delete(public_path('covers/' . $buku->cover));
        }
        $buku->delete();
        return redirect()->route('admin.buku.index')->with('success', 'Buku telah dihapus dari sistem.');
    }

    /**
     * FUNGSI PINJAM BUKU DENGAN MAKSIMAL PINJAM (Poin 1.8 TYPES & Logic)
     */
    public function pinjam(Request $request, $id)
    {
        // 1. Cari buku berdasarkan kode_buku
        $buku = Buku::where('kode_buku', $id)->firstOrFail();

        // 2. Cek Stok
        if ($buku->stok <= 0) {
            return back()->with('error', 'Stok buku habis!');
        }

        // 3. Cek Batas Pinjam (Maksimal 3 buku aktif)
        $aktif = Peminjaman::where('user_id', Auth::user()->pengenal)
                    ->whereIn('status', ['menunggu', 'dipinjam', 'proses_kembali'])
                    ->count();

        if ($aktif >= 3) {
            return back()->with('error', 'Kamu sudah pinjam 3 buku, balikin dulu ya!');
        }

        // 4. Validasi Durasi
        $request->validate([
            'durasi' => 'required|integer|min:1|max:14'
        ]);

        // FIX: Pastikan durasi dikonversi ke Integer agar Carbon tidak error
        $durasi = (int) $request->durasi;

        // 5. Simpan Data Peminjaman
        Peminjaman::create([
            'user_id' => Auth::user()->pengenal,
            'buku_id' => $buku->kode_buku,
            'tanggal_pinjam' => now(),
            'durasi_hari' => $durasi, // Menggunakan variabel yang sudah di-cast ke int
            'tanggal_jatuh_tempo' => now()->addDays($durasi), // Menggunakan variabel int
            'status' => 'menunggu'
        ]);

        // 6. Kurangi stok buku
        $buku->decrement('stok');

        return redirect()->route('anggota.dashboard')->with('success', 'Berhasil diajukan!');
    }
}