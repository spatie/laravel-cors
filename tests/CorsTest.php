<?php

namespace Spatie\CorsLite\Tests;

use Spatie\CorsLite\CorsProfile\CorsProfile;
use Spatie\CorsLite\CorsProfile\DefaultProfile;

class CorsTest extends TestCase
{
    /** @test */
    public function it_responds_with_a_200_for_a_valid_preflight_request()
    {
        $response = $this
            ->sendPreflightRequest('DELETE', 'https://spatie.be')
            ->assertSuccessful()
            ->assertSee('Preflight OK');
        dd($response);
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
