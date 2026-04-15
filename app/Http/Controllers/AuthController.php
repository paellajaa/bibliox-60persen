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
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = [
            'email' => $request->email,
            'password' => $request->password
        ];

        $remember = $request->has('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();
            return $this->redirectUser();
        }

        return back()->with('error', 'Login Gagal! Email atau Password salah.')->withInput();
    }

    // ... (Fungsi showRegister, register, dan logout biarkan sama persis seperti aslimu) ...

    private function redirectUser() {
        $peran = strtolower(Auth::user()->peran);
        if ($peran === 'admin') {
            return redirect()->route('admin.dashboard');
        }
        return redirect()->route('anggota.dashboard');
    }
}