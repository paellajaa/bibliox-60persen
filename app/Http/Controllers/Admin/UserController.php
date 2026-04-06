<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB; // <-- WAJIB TAMBAH INI

class UserController extends Controller
{
    public function index()
    {
        $users = User::latest()->get();
        
        $totalAdmin = User::where('peran', 'admin')->count();
        $totalAnggota = User::where('peran', 'anggota')->count();

        return view('admin.users.index', [
            'users' => $users,
            'totalAdmin' => $totalAdmin,
            'totalAnggota' => $totalAnggota
        ]);
    }

    public function destroy($pengenal)
    {
        // 1. Cari user-nya
        $user = User::where('pengenal', $pengenal)->firstOrFail();
        
        // 2. Proteksi hapus diri sendiri
        if ($user->pengenal === Auth::user()->pengenal) {
            return back()->with('error', 'Waduh Bang, jangan hapus akun sendiri dong!');
        }

        // 3. SAPU BERSIH RIWAYAT PAKSA DARI DATABASE LANGSUNG
        // Kita tidak pakai Model, langsung tembak ke tabel 'peminjamans'
        DB::table('peminjamans')->where('pengguna_id', $user->pengenal)->delete();

        // 4. Hapus akun usernya
        $user->delete();

        return back()->with('success', 'Akun beserta riwayat peminjamannya berhasil dibumihanguskan!');
    }
}