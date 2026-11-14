<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        // Visitantes precisam autenticar antes de acessar qualquer rota administrativa.
        if (! $request->user()) {
            return redirect()->guest(route('login'));
        }

        // Estar autenticado não basta: o perfil precisa ter o tipo administrativo.
        abort_unless($request->user()->profile?->type === 'admin', 403);

        return $next($request);
    }
}
