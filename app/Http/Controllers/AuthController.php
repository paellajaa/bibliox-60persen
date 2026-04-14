<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin() {
        if (Auth::check()) return $this->redirectUser();
        return view('auth.login');
    }

    public function login(Request $request) {
        $request->validate([
            'pengenal' => 'required', 
            'password' => 'required',
        ]);

        // PERBAIKAN: Laravel harus mencari di kolom 'pengenal', bukan 'email'
        // Karena di User.php Anda sudah set getAuthPassword(), 'password' di sini otomatis mengecek ke 'kata_sandi'
        if (Auth::attempt(['pengenal' => $request->pengenal, 'password' => $request->password])) {
            $request->session()->regenerate();
            return $this->redirectUser();
        }

        return back()->with('error', 'Login Gagal! ID atau Password salah.')->withInput();
    }

    public function showRegister() {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'username' => 'required|string|email|max:255|unique:pengguna,email', 
            'password' => 'required|min:6|confirmed', 
        ], [
            'username.unique' => 'Email ini sudah terdaftar!',
            'password.confirmed' => 'Konfirmasi password tidak cocok.'
        ]);

        $count = User::count() + 1;
        $pengenal = date('Y') . str_pad($count, 3, '0', STR_PAD_LEFT);

        $user = User::create([
            'pengenal'   => $pengenal,
            'nama'       => $request->nama,
            'email'      => $request->username,
            'kata_sandi' => Hash::make($request->password),
            'peran'      => 'anggota',
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('anggota.dashboard')->with('success', 'Selamat Datang! ID Login kamu adalah: ' . $pengenal);
    }

    public function logout(Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('home');
    }

    private function redirectUser() {
        $peran = strtolower(Auth::user()->peran);
        if ($peran === 'admin') {
            return redirect()->intended('/admin/dashboard');
        }
        return redirect()->intended('/anggota/dashboard');
    }
}