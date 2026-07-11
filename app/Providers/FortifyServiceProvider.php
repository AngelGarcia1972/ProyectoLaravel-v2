<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserPassword;
use App\Actions\Fortify\UpdateUserProfileInformation;
use App\Models\User;
use App\Notifications\IntentosLoginSospechosos;
use App\Services\AuditoriaService;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Laravel\Fortify\Actions\RedirectIfTwoFactorAuthenticatable;
use Laravel\Fortify\Fortify;

class FortifyServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Fortify::createUsersUsing(CreateNewUser::class);
        Fortify::updateUserProfileInformationUsing(UpdateUserProfileInformation::class);
        Fortify::updateUserPasswordsUsing(UpdateUserPassword::class);
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);
        Fortify::redirectUserForTwoFactorAuthenticationUsing(RedirectIfTwoFactorAuthenticatable::class);

        Fortify::loginView(fn () => view('auth.login'));
        Fortify::registerView(fn () => view('auth.register'));
        Fortify::twoFactorChallengeView(fn () => view('auth.two-factor-challenge'));
        Fortify::confirmPasswordView(fn () => view('auth.passwords.confirm'));
        Fortify::requestPasswordResetLinkView(fn () => view('auth.forgot-password'));
        Fortify::resetPasswordView(fn (Request $request) => view('auth.reset-password', ['request' => $request]));

        Fortify::authenticateUsing(function (Request $request) {
            $key = $request->ip().'|'.$request->input('email');
            $limiterKey = 'login-attempts:'.$key;

            if (RateLimiter::tooManyAttempts($limiterKey, 5)) {
                $seconds = RateLimiter::availableIn($limiterKey);
                abort(429, 'Demasiados intentos. Espera '.$seconds.' segundos.');
            }

            $user = User::where('email', $request->email)->first();

            if (! $user || ! Hash::check($request->password, $user->password)) {
                RateLimiter::hit($limiterKey, 300);

                AuditoriaService::security('LOGIN_FALLIDO', 'warning', [
                    'email_intento' => $request->email,
                ]);

                if (RateLimiter::attempts($limiterKey) >= 3 && $user) {
                    $user->notify(new IntentosLoginSospechosos($request->ip()));
                }

                return null;
            }

            RateLimiter::clear($limiterKey);

            AuditoriaService::log('LOGIN_EXITOSO', ['email' => $user->email]);

            return $user;
        });

        RateLimiter::for('two-factor', function (Request $request) {
            return Limit::perMinute(5)->by($request->session()->get('login.id'));
        });
    }
}
