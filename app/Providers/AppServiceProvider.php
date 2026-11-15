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
     * Limite de tentativas do POST /login (usado como middleware "throttle:login").
     *
     * - 5 tentativas por minuto para a mesma combinação email + IP (barra força bruta contra uma conta);
     * - 20 tentativas por minuto por IP (barra quem testa muitos emails diferentes).
     * Ao estourar, o usuário volta ao formulário com a mensagem e o tempo de espera.
     */
    private function configureLoginRateLimiter(): void
    {
        RateLimiter::for('login', function (Request $request) {
            $email = Str::transliterate(Str::lower((string) $request->input('email')));

            $tooManyAttempts = function (Request $request, array $headers) {
                $seconds = (int) ($headers['Retry-After'] ?? 60);

                return redirect()->route('login')
                    ->withInput($request->only('email'))
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
