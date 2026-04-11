<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB; 

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


}