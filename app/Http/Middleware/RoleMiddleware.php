<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
   public function handle(Request $request, Closure $next, $role): Response
{
    // PERBAIKAN: Gunakan strtolower untuk perbandingan role
    if (!auth()->check() || strtolower(auth()->user()->peran) !== strtolower($role)) {
        abort(403, 'Akses Dilarang!');
    }

    return $next($request);

    
}
}