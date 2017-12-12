<?php

namespace Spatie\CorsLite;

use Illuminate\Support\ServiceProvider;
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

        if (! is_subclass_of($configuredCorsProfile, CorsProfile::class)) {
            throw InvalidCorsProfile::profileDoesNotExtendDefaultProfile($configuredCorsProfile);
        }

        $this->app->bind(CorsProfile::class, $defaultProfileClass);
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/cors-lite.php', 'cors-lite');
    }
}
