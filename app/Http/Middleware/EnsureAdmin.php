<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        // Autenticação: visitantes precisam entrar antes de acessar qualquer rota administrativa.
        if (! $request->user()) {
            return redirect()->guest(route('login'));
        }

        // Autorização: estar autenticado não basta; o perfil precisa ser administrativo.
        abort_unless($request->user()->isAdmin(), 403);

        return $next($request);
    }
}
