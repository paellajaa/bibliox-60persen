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

        // Logic login bisa pake Email atau ID Pengenal
        $fieldType = filter_var($request->pengenal, FILTER_VALIDATE_EMAIL) ? 'email' : 'pengenal';

        if (Auth::attempt([$fieldType => $request->pengenal, 'password' => $request->password])) {
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
    // 1. Validasi Data
    $request->validate([
        'nama' => 'required|string|max:255',
        // PENTING: Ganti 'users' jadi 'pengguna' agar tidak error saat cek email
        'username' => 'required|string|email|max:255|unique:pengguna,email', 
        'password' => 'required|min:6|confirmed', 
    ], [
        'username.unique' => 'Email ini sudah terdaftar!',
        'password.confirmed' => 'Konfirmasi password tidak cocok.'
    ]);

    // 2. Generate Pengenal Otomatis
    $count = User::count() + 1;
    $pengenal = date('Y') . str_pad($count, 3, '0', STR_PAD_LEFT);

    // 3. Simpan ke Database
    $user = User::create([
        'pengenal'   => $pengenal,
        'nama'       => $request->nama,
        'email'      => $request->username,
        'kata_sandi' => Hash::make($request->password), // Pakai kata_sandi sesuai tabel pengguna
        'peran'      => 'anggota',
    ]);

    // 4. Langsung Login
    Auth::login($user);
    $request->session()->regenerate();

    return redirect()->route('anggota.dashboard')->with('success', 'Selamat Datang! ID Login kamu adalah: ' . $pengenal);
}

    public function logout(Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }

    private function redirectUser() {
        return Auth::user()->peran === 'admin' 
            ? redirect()->intended('/admin/dashboard') 
            : redirect()->intended('/anggota/dashboard');
    }
}