<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
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
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        // Limita as tentativas por e-mail e endereço IP.
        $key = Str::lower($request->input('email')) . '|' . $request->ip();

        if (RateLimiter::tooManyAttempts($key, 5)) {
            throw ValidationException::withMessages([
                'email' => 'Muitas tentativas de login. Aguarde um minuto e tente novamente.',
            ]);
        }

        if (!Auth::attempt($credentials)) {
            RateLimiter::hit($key, 60);

            throw ValidationException::withMessages([
                'email' => 'E-mail ou senha inválidos.',
            ]);
        }

        RateLimiter::clear($key);

        // Gera um novo ID de sessão após o login.
        $request->session()->regenerate();

        $user = $request->user();

        $destination = $user->profile?->type === 'admin'
            ? route('admin.users.index')
            : route('home');

        return redirect()->intended($destination);
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')
            ->with('success', 'Você saiu do sistema com sucesso.');
    }
}