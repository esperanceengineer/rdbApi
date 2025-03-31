<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\RateLimiter;
use App\Data\Profile;
use App\Enums\GLOBAL_ROLE;
use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;

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
        //Define rate limiting for 60 requests in a miniute  by user id or ip
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip())->response(function (Request $request, array $headers) {
                return response('Les requêtes depassent le nombre défini ...', 429, $headers);
            });
        });

        Gate::define(GLOBAL_ROLE::ADMIN->value, function (User $user) {
            return $user->profile == Profile::ADMIN;
        });

        Gate::define(GLOBAL_ROLE::CONSULTANT->value, function (User $user) {
            return $user->profile == Profile::CONSULTANT;
        });

        Gate::define(GLOBAL_ROLE::REPRESANTANT->value, function (User $user) {
            return $user->profile != Profile::REPRESENTANT;
        });


        JsonResource::withoutWrapping();
    }
}
