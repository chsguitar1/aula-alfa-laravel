<?php

namespace App\Http\Middleware;

use App\Models\Profile;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        // Visitantes precisam autenticar antes de acessar qualquer rota administrativa.
        // redirect()->guest() guarda a URL pedida para voltar a ela depois do login.
        if (! $request->user()) {
            return redirect()->guest(route('login'));
        }

        // Estar autenticado não basta: o perfil precisa ter o tipo administrativo.
        abort_unless($request->user()->isAdmin(), 403, 'Acesso restrito a administradores.');

        return $next($request);
    }
}
