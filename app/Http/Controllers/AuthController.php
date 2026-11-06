<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthController extends Controller
{
    /**
     * Exibe o formulário de login (página independente do layouts.default).
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Valida as credenciais e autentica o usuário usando a sessão.
     *
     * A rota usa o middleware "throttle:login" (ver AppServiceProvider),
     * que limita as tentativas por email + IP.
     */
    public function store(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ], [
            'email.required' => 'Informe o email.',
            'email.email' => 'Informe um email válido.',
            'password.required' => 'Informe a senha.',
        ]);

        if (! Auth::attempt($credentials)) {
            // Mensagem genérica: não revela se o email existe ou se a senha está errada.
            // onlyInput('email') preserva o email e nunca devolve a senha ao formulário.
            return back()
                ->withErrors(['email' => 'Email ou senha inválidos.'])
                ->onlyInput('email');
        }

        // Evita fixação de sessão: gera um novo identificador após autenticar.
        $request->session()->regenerate();

        if ($request->user()->isAdmin()) {
            // Respeita o destino interno pedido antes do login (ex.: /admin/users/1/edit).
            return redirect()->intended(route('admin.users.index'));
        }

        // Usuário comum sempre vai para a página inicial; descarta um destino
        // administrativo guardado, que só resultaria em 403.
        $request->session()->forget('url.intended');

        return redirect('/');
    }

    /**
     * Encerra a sessão do usuário autenticado.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Você saiu do sistema.');
    }
}
