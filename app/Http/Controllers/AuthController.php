<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AuthController extends Controller
{
    /**
     * Exibe o formulário de login independente (GET /login).
     */
    public function showLoginForm(): View
    {
        return view('auth.login');
    }

    /**
     * Processa a tentativa de autenticação (POST /login).
     */
    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ], [
            'email.required' => 'O campo e-mail é obrigatório.',
            'email.email' => 'Informe um endereço de e-mail válido.',
            'password.required' => 'O campo senha é obrigatório.',
        ]);

        // Chave de limitação por e-mail + IP (máximo de 5 tentativas por minuto)
        $throttleKey = Str::transliterate(Str::lower($request->input('email')) . '|' . $request->ip());

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);

            return back()
                ->withErrors([
                    'email' => "Muitas tentativas de login. Tente novamente em {$seconds} segundos.",
                ])
                ->onlyInput('email');
        }

        if (! Auth::attempt($credentials)) {
            RateLimiter::hit($throttleKey, 60);

            return back()
                ->withErrors([
                    'email' => 'Credenciais inválidas. Verifique seu e-mail e senha.',
                ])
                ->onlyInput('email');
        }

        RateLimiter::clear($throttleKey);

        // Regenera o identificador da sessão
        $request->session()->regenerate();

        $user = Auth::user();
        $user->loadMissing('profile');

        // Usuários com perfil administrativo -> /admin/users (respeitando intended quando aplicável)
        if ($user->profile && $user->profile->type === 'admin') {
            return redirect()->intended(route('users.index'));
        }

        // Usuários comuns -> página inicial (/)
        $request->session()->forget('url.intended');

        return redirect('/');
    }

    /**
     * Encerra a sessão autenticada (POST /logout).
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
