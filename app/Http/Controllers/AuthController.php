<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function showLogin()
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
            // O Laravel não reenvia o campo "password" ao formulário (está em $dontFlash).
            throw ValidationException::withMessages([
                'email' => 'Credenciais inválidas.',
            ]);
        }

        // Evita session fixation: novo ID de sessão após autenticar.
        $request->session()->regenerate();

        if ($request->user()->isAdmin()) {
            // Respeita o destino interno guardado por redirect()->guest() no middleware.
            return redirect()->intended(route('admin.users.index'));
        }

        // Usuário comum nunca é levado a um destino administrativo.
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
