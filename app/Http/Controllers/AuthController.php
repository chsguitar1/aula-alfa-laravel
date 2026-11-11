<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthController extends Controller
{
    /**
     * GET /login - mostra o formulário de login (página independente do layout).
     */
    public function showLoginForm(): View
    {
        return view('auth.login');
    }

    /**
     * POST /login - confere email e senha e cria a sessão.
     */
    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (! Auth::attempt($credentials)) {
            // Mensagem genérica: não revela se o erro foi no email ou na senha.
            // onlyInput('email') devolve só o email; a senha nunca volta para a tela.
            return back()
                ->onlyInput('email')
                ->withErrors(['email' => 'Credenciais inválidas.']);
        }

        // Gera um novo identificador de sessão (evita "session fixation").
        $request->session()->regenerate();

        if ($request->user()->isAdmin()) {
            // Admin vai para o destino interno pedido antes (se houver) ou para o painel.
            return redirect()->intended(route('admin.users.index'));
        }

        // Usuário comum sempre vai para a página inicial; esquece o destino guardado
        // (senão ele cairia numa página admin e receberia 403).
        $request->session()->forget('url.intended');

        return redirect('/');
    }

    /**
     * POST /logout - encerra a sessão.
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
