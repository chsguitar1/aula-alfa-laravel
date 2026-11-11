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
        $this->configureLoginRateLimiter();
    }

    /**
     * Limite de tentativas do POST /login (usado como "throttle:login").
     *
     * 1) No máximo 5 tentativas por minuto para a MESMA combinação email + IP.
     * 2) No máximo 20 tentativas por minuto por IP (impede testar vários emails).
     *
     * Passou do limite: volta ao formulário com uma mensagem amigável.
     */
    private function configureLoginRateLimiter(): void
    {
        RateLimiter::for('login', function (Request $request) {
            $email = Str::lower((string) $request->input('email'));

            $tooManyAttempts = function (Request $request, array $headers) {
                $seconds = (int) ($headers['Retry-After'] ?? 60);

                return back()
                    ->onlyInput('email')
                    ->withErrors([
                        'email' => "Muitas tentativas de login. Tente novamente em {$seconds} segundos.",
                    ]);
            };

            return [
                Limit::perMinute(5)->by($email.'|'.$request->ip())->response($tooManyAttempts),
                Limit::perMinute(20)->by($request->ip())->response($tooManyAttempts),
            ];
        });
    }
}
