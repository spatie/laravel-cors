<?php

namespace Spatie\Cors\Tests;

use Exception;
use Spatie\Cors\Cors;
use Illuminate\Contracts\Http\Kernel;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Exceptions\Handler;
use Orchestra\Testbench\TestCase as Orchestra;
use Illuminate\Contracts\Debug\ExceptionHandler;

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
            return 'real content';
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

    protected function disableExceptionHandling()
    {
        $this->app->instance(ExceptionHandler::class, new class extends Handler {
            public function __construct()
            {
            }

            public function report(Exception $e)
            {
            }

            public function render($request, Exception $exception)
            {
                throw $exception;
            }
        });
    }
}
