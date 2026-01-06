<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\ResetUserPassword;
use App\Models\User;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Laravel\Fortify\Fortify;

class FortifyServiceProvider extends ServiceProvider
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
        $this->customAuthenticate();
        $this->configureActions();
        $this->configureViews();
        $this->configureRateLimiting();
    }

    /**
     * Customize authentication flow.
     *
     * Enable support for login with username or email address.
     */
    private function customAuthenticate()
    {
        Fortify::authenticateUsing(function (Request $request) {
            // Login with username or email
            $email = strtolower($request->input('email'));
            $password = $request->input('password');
            $validationMessage = 'Invalid login credentials.';

            $user = User::where('email', $email)
                ->orWhere('username', $email)
                ->first();

            if (! $user || ! Hash::check($password, $user->password)) {
                throw ValidationException::withMessages([
                    'login' => __($validationMessage),
                ]);
            }

            return $user;
        });
    }

    /**
     * Configure Fortify actions.
     */
    private function configureActions(): void
    {
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);
        Fortify::createUsersUsing(CreateNewUser::class);
    }

    /**
     * Configure Fortify views.
     */
    private function configureViews(): void
    {
        Fortify::loginView(fn () => view('livewire.auth.login'));
        Fortify::verifyEmailView(fn () => view('livewire.auth.verify-email'));
        Fortify::twoFactorChallengeView(fn () => view('livewire.auth.two-factor-challenge'));
        Fortify::confirmPasswordView(fn () => view('livewire.auth.confirm-password'));
        Fortify::registerView(fn () => view('livewire.auth.register'));
        Fortify::resetPasswordView(fn () => view('livewire.auth.reset-password'));
        Fortify::requestPasswordResetLinkView(fn () => view('livewire.auth.forgot-password'));
    }

    /**
     * Configure rate limiting.
     */
    private function configureRateLimiting(): void
    {
        RateLimiter::for('two-factor', function (Request $request) {
            return Limit::perMinute(5)->by($request->session()->get('login.id'));
        });

        RateLimiter::for('login', function (Request $request) {
            $login = Str::lower($request->input('login'));

            return Limit::perMinute(5)->by($login.'|'.$request->ip());
        });
    }
}
