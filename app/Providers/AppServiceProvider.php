<?php

namespace App\Providers;

use App\Settings\GeneralSettings;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
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
        RateLimiter::for('wheel-spin', static function (Request $request) {
            // Get the cooldown in hours from the settings, default to 1 hour
            $hours = app(GeneralSettings::class)->wheel_spin_cooldown ?? 1;
            $decayMinutes = $hours * 60;

            return Limit::perMinutes($decayMinutes, 1)
                ->by($request->user()?->id ?: $request->ip());
        });
    }
}
