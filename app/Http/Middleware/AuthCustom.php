<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthCustom
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!session('usuario_logueado')) {
            return redirect()->route('login')->with('error', 'Debe iniciar sesión para acceder');
        }

        return $next($request);
    }
}