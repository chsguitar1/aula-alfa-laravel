<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureAdmin
{
    /**
     * Autenticação != autorização:
     *  - visitante           -> redireciona para /login (guarda o destino pretendido);
     *  - autenticado comum   -> 403;
     *  - perfil type = admin -> segue.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user) {
            return redirect()->guest(route('login'));
        }

        abort_unless($user->isAdmin(), 403);

        return $next($request);
    }
}
