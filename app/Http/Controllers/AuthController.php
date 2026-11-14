<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function login()
    {
        return view('auth.login');
    }

    public function store(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if (! Auth::attempt($credentials)) {
            throw ValidationException::withMessages(['email' => 'Email ou senha inválidos.']);
        }

        $request->session()->regenerate();
        $admin = $request->user()->profile?->type === 'admin';
        $fallback = $admin ? route('admin.users.index') : url('/');
        $intended = $request->session()->pull('url.intended');

        // Resume only URLs on this origin; ordinary users always land on home.
        if ($admin && is_string($intended) && str_starts_with($intended, $request->getSchemeAndHttpHost().'/')) {
            if (! in_array(parse_url($intended, PHP_URL_PATH), ['/login', '/logout'])) {
                return redirect()->to($intended);
            }
        }

        return redirect()->to($fallback);
    }

    public function destroy(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
