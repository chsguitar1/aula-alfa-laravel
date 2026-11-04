<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Limite de login: 5 tentativas por minuto para cada combinação email + IP.
        RateLimiter::for('login', function (Request $request) {
            $key = Str::lower((string) $request->input('email')).'|'.$request->ip();

            return Limit::perMinute(5)->by($key)->response(function (Request $request, array $headers) {
                $seconds = (int) ($headers['Retry-After'] ?? 60);

                return back()
                    ->withInput($request->only('email'))
                    ->withErrors(['email' => "Muitas tentativas de login. Tente novamente em {$seconds} segundos."]);
            });
        });
    }
}
