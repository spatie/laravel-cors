<?php

namespace Spatie\Cors\Tests;

use Illuminate\Contracts\Http\Kernel;
use Illuminate\Support\Facades\Route;
use Orchestra\Testbench\TestCase as Orchestra;
use Spatie\Cors\Cors;
use Symfony\Component\HttpFoundation\StreamedResponse;

class TestCase extends Orchestra
{
    public function setUp(): void
    {
        parent::setUp();

        $this->setupRoutes();
    }

    protected function setupRoutes()
    {
        Route::post('test-cors', function () {
            return 'real content';
        });

        Route::post('test-cors-stream', function () {
            return new StreamedResponse();
        });
    }

    /**
     * @param \Illuminate\Foundation\Application $app
     */
    protected function getEnvironmentSetUp($app)
    {
        $app->make(Kernel::class)->prependMiddleware(Cors::class);
    }

    /**
     * @param \Illuminate\Foundation\Application $app
     *
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [
            \Spatie\Cors\CorsServiceProvider::class,
        ];
    }
}
