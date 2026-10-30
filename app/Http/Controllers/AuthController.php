<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (! Auth::attempt($credentials)) {
            throw ValidationException::withMessages([
                'email' => 'Email ou senha inválidos.',
            ]);
        }

        // Regenera a sessão depois do login.
        $request->session()->regenerate();

        $isAdmin = $request->user()->profile?->type === 'admin';

        // Recupera e remove o destino solicitado anteriormente.
        $intended = $request->session()->pull('url.intended');

        // Usuários comuns sempre vão para a página inicial.
        if (! $isAdmin) {
            return redirect()->route('home');
        }

        // Administradores podem retornar a um destino interno seguro.
        if ($this->isSafeInternalUrl($intended)) {
            return redirect()->to($intended);
        }

        // Destino padrão do administrador.
        return redirect()->route('admin.users.index');
    }

    private function isSafeInternalUrl(mixed $destination): bool
    {
        if (! is_string($destination) || $destination === '') {
            return false;
        }

        // Não aceita barras invertidas ou caracteres de controle.
        if (
            str_contains($destination, '\\') ||
            preg_match('/[\x00-\x1F\x7F]/', $destination)
        ) {
            return false;
        }

        // Permite caminhos relativos internos, como /admin/users.
        if (str_starts_with($destination, '/')) {
            return ! str_starts_with($destination, '//');
        }

        // Para URLs absolutas, exige a mesma origem da aplicação.
        $target = parse_url($destination);
        $origin = parse_url(url('/'));

        if (! is_array($target) || ! is_array($origin)) {
            return false;
        }

        if (isset($target['user']) || isset($target['pass'])) {
            return false;
        }

        return strtolower($target['scheme'] ?? '') ===
                strtolower($origin['scheme'] ?? '')
            && strtolower($target['host'] ?? '') ===
                strtolower($origin['host'] ?? '')
            && ($target['port'] ?? null) ===
                ($origin['port'] ?? null);
    }

    public function logout(Request $request)
    {
        Auth::logout();

        // Encerra a sessão e regenera o token CSRF.
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
