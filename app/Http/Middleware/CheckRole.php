<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle(Request $request, Closure $next, string $roles): Response
    {
        // Si el usuario no está autenticado redirige al login
        if (!$request->user()) {
            return redirect('login');
        }

        // Convertir el string de roles a array (ej: "patient,doctor" -> ["patient", "doctor"])
        $rolesArray = explode(',', $roles);

        // Verifica si el rol del usuario está en los roles permitidos
        if (!in_array($request->user()->role, $rolesArray)) {
            abort(403, 'No tienes permiso para acceder a esta sección.');
        }

        return $next($request);
    }
}
