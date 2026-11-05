<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function showLoginForm(): View
    {
        return view('auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if (! Auth::attempt($credentials)) {
            // Mensagem genérica: não revela se o email existe ou se a senha está errada.
            // Só o email volta ao formulário; a senha nunca é repopulada.
            return back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => 'Credenciais inválidas.']);
        }

        // Evita session fixation: novo identificador de sessão após autenticar.
        $request->session()->regenerate();

        if ($request->user()->isAdmin()) {
            // Respeita o destino interno que o administrador tentou acessar antes do login.
            return redirect()->intended(route('admin.users.index'));
        }

        // Usuário comum sempre vai para a home; descarta destino administrativo guardado.
        $request->session()->forget('url.intended');

        return redirect()->route('home');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
