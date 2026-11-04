<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AuthController extends Controller
{
    /** Máximo de tentativas falhas por email + IP dentro da janela. */
    private const MAX_ATTEMPTS = 5;

    /** Janela (em segundos) em que as falhas são contadas e o bloqueio dura. */
    private const DECAY_SECONDS = 60;

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

        // A chave combina email e IP: limita ataques a uma conta sem bloquear todos os usuários.
        $throttleKey = Str::transliterate(Str::lower($credentials['email']).'|'.$request->ip());

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
            $intended = $request->session()->pull('url.intended');

            // Só respeita destinos do próprio host (evita open redirect).
            if ($intended && parse_url($intended, PHP_URL_HOST) === $request->getHost()) {
                return redirect()->to($intended);
            }

            return redirect()->route('admin.users.index');
        }

        // Usuário comum sempre vai para a home, ignorando destino administrativo guardado.
        $request->session()->forget('url.intended');

        return redirect()->route('home');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
