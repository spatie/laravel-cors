<?php

namespace Spatie\CorsLite\Tests;

use Spatie\CorsLite\Cors;
use Illuminate\Contracts\Http\Kernel;
use Illuminate\Support\Facades\Route;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    public function setUp()
    {
        parent::setUp();

        $this->setupRoutes();
    }

    protected function setupRoutes()
    {
        Route::post('test-cors', function () {
            return 'ok';
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
            \Spatie\CorsLite\CorsLiteServiceProvider::class,
        ];
    }
}
