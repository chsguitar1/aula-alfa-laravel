<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /** Máximo de tentativas de login falhas por email + IP dentro da janela. */
    private const MAX_ATTEMPTS = 5;

    /** Janela (e tempo de bloqueio) em segundos. */
    private const DECAY_SECONDS = 60;

    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        $throttleKey = $this->throttleKey($request);

        // Limitação de tentativas: bloqueia antes mesmo de consultar o banco.
        if (RateLimiter::tooManyAttempts($throttleKey, self::MAX_ATTEMPTS)) {
            $seconds = RateLimiter::availableIn($throttleKey);

            throw ValidationException::withMessages([
                'email' => "Muitas tentativas de login. Tente novamente em {$seconds} segundos.",
            ])->status(429);
        }

        if (! Auth::attempt($credentials)) {
            RateLimiter::hit($throttleKey, self::DECAY_SECONDS);

            // Mensagem genérica: não revela se o email existe ou se a senha está errada.
            throw ValidationException::withMessages([
                'email' => 'Credenciais inválidas.',
            ]);
        }

        RateLimiter::clear($throttleKey);

        // Evita fixação de sessão: novo identificador após autenticar.
        $request->session()->regenerate();

        if ($request->user()->isAdmin()) {
            // Respeita o destino interno pedido antes do login (guardado pelo middleware).
            return redirect()->intended(route('admin.users.index'));
        }

        // Usuário comum sempre vai para a página inicial.
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

    private function throttleKey(Request $request): string
    {
        return Str::lower($request->string('email')->toString()).'|'.$request->ip();
    }
}
