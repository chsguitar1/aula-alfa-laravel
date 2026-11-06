<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);
        $key = Str::lower($request->input('email')) . '|' . $request->ip();

        RateLimiter::hit($key, 60);
        if (RateLimiter::tooManyAttempts($key,5)){
            $seconds = RateLimiter::availableIn($key);
            return back()->withErrors([
                'email'=>"Muitas tentativas, tente novamente em {$seconds} segundos",
            ])->onlyInput('email');
        }
        

        if (Auth::attempt($credentials)) {
            RateLimiter::clear($key);
            $request->session()->regenerate();

            $user = Auth::user();

            $isAdmin = $user->profile()
            ->where('type', 'admin')
            ->exists();

            if($isAdmin){
                return redirect()->intended(route('admin.users.index'));
            }
            return redirect()->intended('/');
        }
        return back()->withErrors([
            'email'=> 'E-mail ou senha errados',
        ])->onlyInput('email');
    }
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
