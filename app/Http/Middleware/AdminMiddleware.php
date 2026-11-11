<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Protege todas as rotas sob /admin/*:
     * - Visitante não autenticado: redirecionar para /login.
     * - Usuário autenticado sem perfil administrativo: responder com 403.
     * - Usuário cujo perfil tem type = admin: permitir que a requisição prossiga.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! Auth::check()) {
            return redirect()->guest(route('login'));
        }

        $user = Auth::user();
        $user->loadMissing('profile');

        if (! $user->profile || $user->profile->type !== 'admin') {
            abort(403, 'Acesso negado. Esta área é restrita a administradores.');
        }

        return $next($request);
    }
}
