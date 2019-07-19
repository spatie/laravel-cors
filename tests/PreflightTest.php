<?php

namespace Spatie\Cors\Tests;

class PreflightTest extends TestCase
{
    /** @test */
    public function it_responds_with_a_204_for_a_valid_preflight_request()
    {
        $response = $this
            ->sendPreflightRequest('DELETE', 'https://spatie.be')
            ->assertStatus(204)
            ->assertHeader('Access-Control-Allow-Methods', 'POST, GET, OPTIONS, PUT, PATCH, DELETE')
            ->assertHeader('Access-Control-Allow-Headers', 'Content-Type, X-Auth-Token, Origin, Authorization')
            ->assertHeader('Access-Control-Allow-Origin', '*')
            ->assertHeader('Access-Control-Max-Age', 60 * 60 * 24);

        $this->assertEmpty($response->content());
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
    public function it_responds_with_correct_header_for_a_preflight_request_when_allow_credentials_is_set_to_true()
    {
        config()->set('cors.default_profile.allow_credentials', true);

        $this
            ->sendPreflightRequest('DELETE', 'https://spatie.be')
            ->assertHeader('Access-Control-Allow-Credentials', 'true')
            ->assertHeader('Access-Control-Allow-Origin', 'https://spatie.be');
    }

    /** @test */
    public function it_responds_with_correct_header_for_a_preflight_request_when_allow_credentials_is_set_to_false()
    {
        config()->set('cors.default_profile.allow_credentials', false);

        $response = $this
            ->sendPreflightRequest('DELETE', 'https://spatie.be')
            ->assertHeader('Access-Control-Allow-Origin', '*');

        $headerName = 'Access-Control-Allow-Credentials';

        $this->assertFalse($response->headers->has($headerName), "Unexpected header [{$headerName}] is present on response.");
    }

    /** @test */
    public function it_responds_with_a_204_for_a_preflight_request_coming_from_an_allowed_origin()
    {
        config()->set('cors.default_profile.allow_origins', ['https://spatie.be']);

        $this
            ->sendPreflightRequest('DELETE', 'https://spatie.be')
            ->assertStatus(204);
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
