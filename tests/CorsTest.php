<?php

namespace Spatie\CorsLite\Tests;

use Spatie\CorsLite\CorsProfile\CorsProfile;
use Spatie\CorsLite\CorsProfile\DefaultProfile;

class CorsTest extends TestCase
{
    /** @test */
    public function it_add_the_cors_headers_on_a_valid_requests()
    {
        $this
            ->sendRequest('POST', 'https://spatie.be')
            ->assertSuccessful()
            ->assertHeader('Access-Control-Allow-Origin', '*')
            ->assertSee('real content');
    }

    /** @test */
    public function it_will_send_a_403_for_invalid_requests()
    {
        config()->set('cors-lite.default_profile.allow_origins', ['https://spatie.be']);

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
