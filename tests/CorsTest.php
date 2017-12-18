<?php

namespace Spatie\Cors\Tests;

class CorsTest extends TestCase
{
    /** @test */
    public function it_adds_the_cors_headers_on_a_valid_requests()
    {
        $this
            ->sendRequest('POST', 'https://spatie.be')
            ->assertSuccessful()
            ->assertHeader('Access-Control-Allow-Origin', '*')
            ->assertSee('real content');
    }

    /** @test */
    public function it_adds_the_wildcard_in_the_cors_headers_on_a_valid_request_if_no_allow_origins_are_set()
    {
        $this
            ->sendRequest('POST', 'https://spatie.be')
            ->assertSuccessful()
            ->assertHeader('Access-Control-Allow-Origin', '*')
            ->assertSee('real content');
    }

    /** @test */
    public function it_adds_the_origin_domain_in_the_cors_headers_on_a_valid_request()
    {
        config()->set('cors.default_profile.allow_origins', [
            'https://spatie.be',
            'https://laravel.com',
        ]);

        $this
            ->sendRequest('POST', 'https://spatie.be')
            ->assertSuccessful()
            ->assertHeader('Access-Control-Allow-Origin', 'https://spatie.be')
            ->assertSee('real content');
    }

    /** @test */
    public function it_will_send_a_403_for_invalid_requests()
    {
        config()->set('cors.default_profile.allow_origins', ['https://spatie.be']);

        $this
            ->sendRequest('POST', 'https://laravel.com')
            ->assertStatus(403);
    }

    public function sendRequest(string $method, string $origin)
    {
        $headers = [
            'Origin' => $origin,
        ];

        $server = $this->transformHeadersToServerVars($headers);

        return $this->call($method, 'test-cors', [], [], [], $server);
    }
}
