<?php

namespace Spatie\CorsLite\Tests;

use Spatie\CorsLite\CorsLiteServiceProvider;
use Spatie\CorsLite\Exceptions\InvalidCorsProfile;

class ConfigTest extends TestCase
{
    /** @test */
    public function it_will_throw_an_exception_when_an_invalid_profile_is_set()
    {
        config()->set('cors-lite.cors_profile', 'FakeClass');

        $this->expectException(InvalidCorsProfile::class);

        (new CorsLiteServiceProvider($this->app))->boot();
    }
}
