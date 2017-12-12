<?php

namespace Spatie\CorsLite\Tests;

use PHPUnit\Framework\TestCase;
use Orchestra\Testbench\TestCase as Orchestra;
use Spatie\CorsLite\Cors;

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
