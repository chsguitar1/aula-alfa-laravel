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
        $this->configureRateLimiting();
    }

    /**
     * Limite de tentativas do POST /login.
     *
     * Até 5 requisições por minuto para cada combinação email + IP.
     * O contador fica no cache (CACHE_STORE) e expira após 60 segundos.
     * Ao estourar o limite, o usuário volta ao formulário com uma mensagem
     * informando quantos segundos faltam para tentar de novo.
     */
    protected function configureRateLimiting(): void
    {
        RateLimiter::for('login', function (Request $request) {
            $key = Str::lower((string) $request->input('email')).'|'.$request->ip();

            return Limit::perMinute(5)
                ->by($key)
                ->response(function (Request $request, array $headers) {
                    $seconds = $headers['Retry-After'] ?? 60;

                    return back()
                        ->withErrors(['email' => "Muitas tentativas de login. Tente novamente em {$seconds} segundos."])
                        ->onlyInput('email');
                });
        });
    }
}
