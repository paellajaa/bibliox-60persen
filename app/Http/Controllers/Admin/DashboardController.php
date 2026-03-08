<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Buku;
use App\Models\User;
use App\Models\Peminjaman;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Statistik Admin
        $total_buku = Buku::count();
        $total_anggota = User::where('peran', 'anggota')->count();
        $total_peminjaman = Peminjaman::where('status', 'dipinjam')->count();

        // Ambil data peminjaman terbaru dengan relasi
        $peminjaman_terbaru = Peminjaman::with(['user', 'buku'])
                                ->latest()
                                ->take(5)
                                ->get();

        return view('admin.dashboard', compact(
            'total_buku', 
            'total_anggota', 
            'total_peminjaman', 
            'peminjaman_terbaru'
        ));
    }

    public function anggota(Request $request)
    {
        $query = Buku::query();

        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('judul', 'like', '%' . $search . '%')
                  ->orWhere('penulis', 'like', '%' . $search . '%');
            });
        }

        $all_books = $query->get();
        $daftarKategori = Buku::whereNotNull('kategori')->distinct()->pluck('kategori');

        return view('anggota.dashboard', compact('all_books', 'daftarKategori'));
    }
}