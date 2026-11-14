<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function create(): View
    {
        return view('auth.login');
    }

    public function store(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (! Auth::attempt($credentials)) {
            return back()
                ->withErrors(['email' => 'As credenciais informadas são inválidas.'])
                ->onlyInput('email');
        }

        $request->session()->regenerate();

        if ($request->user()->profile?->type === 'admin') {
            return $this->redirectToIntendedUrl($request);
        }

        $request->session()->forget('url.intended');

        return redirect()->route('home');
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    private function redirectToIntendedUrl(Request $request): RedirectResponse
    {
        $fallback = route('admin.users.index');
        $intended = $request->session()->pull('url.intended');

        if (! is_string($intended) || $intended === '') {
            return redirect($fallback);
        }

        $parts = parse_url($intended);

        if (
            $parts === false
            || (isset($parts['host']) && strcasecmp($parts['host'], $request->getHost()) !== 0)
        ) {
            return redirect($fallback);
        }

        $path = $parts['path'] ?? '/';

        if (! str_starts_with($path, '/') || str_starts_with($path, '//')) {
            return redirect($fallback);
        }

        $query = isset($parts['query']) ? '?'.$parts['query'] : '';

        return redirect()->to($path.$query);
    }
}
