<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Visitante não autenticado: redirecionar para /login
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        // 2. Usuário autenticado sem perfil administrativo: responder com 403
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Acesso não autorizado.');
        }

        // 3. Permitir prosseguir se for admin
        return $next($request);
    }
}