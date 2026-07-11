<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        RateLimiter::for('login', function (Request $request) {
            $key = $request->input('email').'|'.$request->ip();

            return Limit::perMinute(5)->by($key)->response(function () {
                return response()->json([
                    'message' => 'Demasiados intentos. Espera 1 minuto.',
                    'retry_after' => 60,
                ], 429);
            });
        });

        RateLimiter::for('api', function (Request $request) {
            if ($request->user()) {
                $key = 'user:'.$request->user()->id;

                return Limit::perMinute(60)->by($key);
            }

            return Limit::perMinute(10)->by('ip:'.$request->ip());
        });

        RateLimiter::for('sensible', function (Request $request) {
            return Limit::perHour(3)->by('ip:'.$request->ip())->response(function () {
                return response()->json([
                    'message' => 'Demasiadas solicitudes. Intenta de nuevo en 1 hora.',
                    'retry_after' => 3600,
                ], 429);
            });
        });
    }
}
