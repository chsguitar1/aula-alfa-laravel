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
        // Limite de tentativas de login (duas camadas):
        //  - 5 por minuto para cada par email + IP (barra força bruta numa conta);
        //  - 20 por minuto por IP (barra quem troca de email a cada tentativa).
        // Passou de qualquer limite, o Laravel responde 429 (Too Many Requests) com Retry-After.
        RateLimiter::for('login', function (Request $request) {
            return [
                Limit::perMinute(5)->by(Str::lower((string) $request->input('email')).'|'.$request->ip()),
                Limit::perMinute(20)->by($request->ip()),
            ];
        });
    }
}
