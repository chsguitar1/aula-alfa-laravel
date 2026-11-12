<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        // Autenticação: visitantes precisam entrar antes (guest() guarda o destino interno).
        if (! $user) {
            return redirect()->guest(route('login'));
        }

        // Autorização: estar autenticado não basta, o perfil precisa ser admin.
        abort_unless($user->isAdmin(), 403);

        return $next($request);
    }
}
