<?php

namespace Spatie\Cors\Tests;

use Spatie\Cors\CorsServiceProvider;
use Spatie\Cors\Exceptions\InvalidCorsProfile;

class ConfigTest extends TestCase
{
    /** @test */
    public function it_will_throw_an_exception_when_an_invalid_profile_is_set()
    {
        config()->set('cors.cors_profile', 'FakeClass');

        $this->expectException(InvalidCorsProfile::class);

        (new CorsServiceProvider($this->app))->boot();
    }
}
