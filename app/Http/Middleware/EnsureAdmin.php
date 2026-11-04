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

        // Estar autenticado não basta (autenticação ≠ autorização): o perfil precisa ser admin.
        abort_unless($request->user()->isAdmin(), 403);

        return $next($request);
    }
}
