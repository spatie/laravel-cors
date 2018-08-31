<?php

namespace Spatie\Cors\Tests;

use Spatie\Cors\CorsProfile\DefaultProfile;

class DefaultProfileTest extends TestCase
{
    /** @test */
    public function it_returns_false_when_allow_credentials_is_not_set_in_config()
    {
        config()->set('cors.default_profile.allow_credentials', null);

        $defaultProfile = new DefaultProfile();

        $this->assertFalse($defaultProfile->allowCredentials());
    }
}
