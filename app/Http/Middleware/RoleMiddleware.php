<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (! $request->user()) {
            return redirect()->route('login');
        }

        if (! in_array($request->user()->role, $roles)) {
            abort(403, 'Acceso denegado. No posee los permisos requeridos para esta sección.');
        }

        if (! $request->user()->is_active) {
            auth()->logout();
            return redirect()->route('login')->withErrors(['email' => 'Su cuenta ha sido desactivada por el administrador.']);
        }

        return $next($request);
    }
}
