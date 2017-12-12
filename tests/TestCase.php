<?php

namespace Spatie\CorsLite\Tests;

use Spatie\CorsLite\Cors;
use PHPUnit\Framework\TestCase;
use Orchestra\Testbench\TestCase as Orchestra;

class ExampleTest extends Orchestra
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
        })->middleware(Cors::class);
    }

    /**
     * @param \Illuminate\Foundation\Application $app
     *
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [
            \Spatie\CorstLite\CorsLiteServiceProvider::class,
        ];
    }
}
