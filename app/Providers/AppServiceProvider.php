<?php

namespace App\Providers;

use App\Models\Administrador;
use App\Models\User;
use App\Policies\AdministradorPolicy;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

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
        ResetPassword::createUrlUsing(function (object $notifiable, string $token) {
            return config('app.frontend_url')."/password-reset/$token?email={$notifiable->getEmailForPasswordReset()}";
        });

        Gate::policy(Administrador::class, AdministradorPolicy::class);

        Gate::define('is-admin', function (User $user) {
            return $user->esAdmin() === true;
        });

        Gate::before(function (User $user, string $ability) {
            if ($user->esAdmin()) {
                return true;
            }
        });
    }
}
