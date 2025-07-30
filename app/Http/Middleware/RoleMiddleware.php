<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // Si el usuario no está autenticado, redirige al login
        if (!Auth::check()) {
            return redirect('/login');
        }

        // Obtiene el usuario autenticado
        $user = Auth::user();

        // Verifica si el rol del usuario está en la lista de roles permitidos
        if (!in_array($user->role, $roles)) {
            // Si el usuario no tiene el rol adecuado, redirige a una página de error o al dashboard
            // Puedes personalizar esta redirección o mostrar un mensaje de error
            return redirect('/dashboard')->with('error', 'No tienes permiso para acceder a esta sección.');
        }

        return $next($request);
    }
}
