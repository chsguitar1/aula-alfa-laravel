<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        // Se não estiver logado, redireciona para o login
        if (! $user) {
            return redirect()->guest(route('login'));
        }

        // Se estiver logado mas não for admin, bloqueia com 403
        if (! $user->isAdmin()) {
            abort(403);
        }

        return $next($request);
    }
}