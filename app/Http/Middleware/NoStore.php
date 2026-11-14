<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Impede que o navegador guarde cópia das páginas.
 *
 * Sem isso, depois do logout o botão "Voltar" mostra a página autenticada
 * guardada no cache do navegador (e restaura os campos do formulário de login).
 * Com "no-store", o navegador pede a página de novo ao servidor, que redireciona
 * ao login quando não há sessão.
 */
class NoStore
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        $response->headers->set('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0');
        $response->headers->set('Pragma', 'no-cache');
        $response->headers->set('Expires', '0');

        return $response;
    }
}
