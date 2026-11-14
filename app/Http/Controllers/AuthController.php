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
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (! Auth::attempt($credentials)) {
            // Mensagem genérica: não revela se o email existe ou se a senha é que está errada.
            // onlyInput() devolve apenas o email; a senha nunca é repopulada.
            return back()
                ->withErrors(['email' => 'As credenciais informadas não conferem.'])
                ->onlyInput('email');
        }

        // Evita session fixation: o identificador de sessão muda após autenticar.
        $request->session()->regenerate();

        if ($request->user()->isAdmin()) {
            // intended() volta ao destino interno pedido antes do login (ou ao painel).
            return redirect()->intended(route('admin.users.index'));
        }

        // Usuário comum sempre vai para a home; descarta destino administrativo guardado.
        $request->session()->forget('url.intended');

        return redirect('/');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
