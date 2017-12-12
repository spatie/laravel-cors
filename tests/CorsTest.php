<?php

namespace Spatie\CorsLite\Tests;

use Spatie\CorsLite\CorsProfile\CorsProfile;
use Spatie\CorsLite\CorsProfile\DefaultProfile;

class CorsTest extends TestCase
{
    /** @test */
    public function it_can_resolve_the_profile()
    {
        $this->assertInstanceOf(DefaultProfile::class, app(CorsProfile::class));
    }

    /** @test */
    public function it_tests()
    {
        $response = $this->sendPreflightRequest('DELETE', 'https://spatie.be');
    }

    public function sendPreflightRequest(string $method, string $origin)
    {
        $headers = [
            'Access-Control-Request-Method' => $method,
            'Origin' => $origin,
        ];

        $server = $this->transformHeadersToServerVars($headers);

        return $this->call('OPTIONS', 'test-cors', [], [], [], $server);
    }
}
