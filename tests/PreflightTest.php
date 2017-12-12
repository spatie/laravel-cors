<?php

namespace Spatie\Cors\Tests;

class PreflightTest extends TestCase
{
    /** @test */
    public function it_responds_with_a_200_for_a_valid_preflight_request()
    {
        $response = $this
            ->sendPreflightRequest('DELETE', 'https://spatie.be')
            ->assertSuccessful()
            ->assertSee('Preflight OK')
            ->assertHeader('Access-Control-Allow-Methods', 'POST, GET, OPTIONS, PUT, DELETE')
            ->assertHeader('Access-Control-Allow-Headers', 'Content-Type, X-Auth-Token, Origin, Authorization')
            ->assertHeader('Access-Control-Allow-Origin', '*')
            ->assertHeader('Access-Control-Max-Age', 60 * 60 * 24);
    }

    /** @test */
    public function it_responds_with_a_403_for_a_preflight_request_with_an_invalid_method()
    {
        config()->set('cors.default_profile.allow_methods', ['GET']);

        $this
            ->sendPreflightRequest('DELETE', 'https://spatie.be')
            ->assertStatus(403);
    }

    /** @test */
    public function it_responds_with_a_200_for_a_preflight_request_coming_from_an_allowed_origin()
    {
        config()->set('cors.default_profile.allow_origins', ['https://spatie.be']);

        $this
            ->sendPreflightRequest('DELETE', 'https://spatie.be')
            ->assertStatus(200);
    }

    /** @test */
    public function it_responds_with_a_403_for_a_preflight_request_with_an_invalid_origin()
    {
        config()->set('cors.default_profile.allow_origins', ['https://spatie.be']);

        $this
            ->sendPreflightRequest('DELETE', 'https://laravel.com')
            ->assertStatus(403);
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
