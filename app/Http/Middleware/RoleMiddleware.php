<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, $role): Response
    {
        
        if (!auth()->check()) {
            dd('ERROR 1: SESI HILANG! KTP Digital kamu jatuh saat pindah halaman. auth()->check() bernilai FALSE.');
        }

        $userPeran = auth()->user()->peran;
        
        if (strtolower($userPeran) !== strtolower($role)) {
            dd('ERROR 2: PERAN BEDA!', 'Peran kamu: ' . $userPeran, 'Peran yang diminta: ' . $role);
        }

        return $next($request);
    }
}