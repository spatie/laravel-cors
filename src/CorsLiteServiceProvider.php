<?php

namespace Spatie\CorsLite;

use Illuminate\Support\ServiceProvider;
use Spatie\CorsLite\CorsProfile\CorsProfile;
use Spatie\CorsLite\CorsProfile\DefaultProfile;
use Spatie\CorsLite\Exceptions\InvalidCorsProfile;

class CorsLiteServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/cors-lite.php' => config_path('cors-lite.php'),
            ], 'config');
        }

        $configuredCorsProfile = config('cors-lite.cors_profile');

        if (! is_a($configuredCorsProfile, DefaultProfile::class, true)) {
            throw InvalidCorsProfile::profileDoesNotExtendDefaultProfile($configuredCorsProfile);
        }

        $this->app->bind(CorsProfile::class, $configuredCorsProfile);
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/cors-lite.php', 'cors-lite');
    }
}
