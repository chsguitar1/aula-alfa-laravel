<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class LoginController extends Controller
{
    public function showLoginForm(): View
    {
        return view('auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (! Auth::attempt($credentials)) {
            // Mensagem genérica: não revela se o email existe. Só o email volta ao formulário.
            return redirect()->route('login')
                ->withInput($request->only('email'))
                ->withErrors(['email' => 'As credenciais informadas não conferem.']);
        }

        // Novo identificador de sessão após autenticar (evita session fixation).
        $request->session()->regenerate();

        if ($request->user()->isAdmin()) {
            // Respeita o destino interno que o visitante tentou abrir antes do login.
            return redirect()->intended(route('admin.users.index'));
        }

        // Usuário comum sempre vai para a página inicial; descarta destino antigo guardado na sessão.
        $request->session()->forget('url.intended');

        return redirect()->route('home');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Você saiu da sua conta.');
    }
}
