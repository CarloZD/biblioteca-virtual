<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (session('usuario_rol') !== $role) {
            return redirect()->route('dashboard')->with('error', 'No tiene permisos para acceder a esta sección');
        }

        return $next($request);
    }
}