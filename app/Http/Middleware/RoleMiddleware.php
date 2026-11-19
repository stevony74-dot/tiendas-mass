<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string $role): Response
    {
        // Si no hay sesión o el rol no coincide, se bloquea el acceso
        if (!$request->user() || $request->user()->role !== $role) {
            abort(403, 'No tienes permiso para entrar aquí.');
        }

        return $next($request);
    }
}