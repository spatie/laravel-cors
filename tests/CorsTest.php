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
    public function it_adds_the_credentials_cors_headers_on_a_valid_request_if_allow_credentials_is_set_to_true()
    {
        config()->set('cors.default_profile.allow_credentials', true);

        $this
            ->sendRequest('POST', 'https://spatie.be')
            ->assertSuccessful()
            ->assertHeader('Access-Control-Allow-Credentials', 'true')
            ->assertHeader('Access-Control-Allow-Origin', 'https://spatie.be')
            ->assertSee('real content');
    }

    /** @test */
    public function it_does_not_add_the_credentials_cors_headers_on_a_valid_request_if_allow_credentials_is_set_to_false()
    {
        config()->set('cors.default_profile.allow_credentials', false);

        $response = $this
            ->sendRequest('POST', 'https://spatie.be')
            ->assertSuccessful()
            ->assertHeader('Access-Control-Allow-Origin', '*')
            ->assertSee('real content');

        $headerName = 'Access-Control-Allow-Credentials';

        $this->assertFalse($response->headers->has($headerName), "Unexpected header [{$headerName}] is present on response.");
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
    public function it_adds_the_origin_domain_in_the_cors_headers_on_a_valid_request_with_wildcard_content()
    {
        config()->set('cors.default_profile.allow_origins', [
            'https://*.be',
            'https://*.com',
        ]);

        $this
            ->sendRequest('POST', 'https://spatie.be')
            ->assertSuccessful()
            ->assertHeader('Access-Control-Allow-Origin', 'https://spatie.be')
            ->assertSee('real content');
    }

    /** @test */
    public function it_throws_error_on_an_invalid_request_with_wildcard_content()
    {
        config()->set('cors.default_profile.allow_origins', [
            'https://*.spatie.be',
            'https://*.com',
        ]);

        $this
            ->sendRequest('POST', 'https://spatie.be')
            ->assertStatus(403)
            ->assertSee('Forbidden (cors).');
    }

    /** @test */
    public function it_adds_the_allowed_expose_headers_in_the_cors_headers_on_a_valid_request()
    {
        config()->set('cors.default_profile.expose_headers', [
            'Authorization',
            'X-Foo-Header',
        ]);

        $this
            ->sendRequest('POST', 'https://spatie.be')
            ->assertSuccessful()
            ->assertHeader('Access-Control-Expose-Headers', 'Authorization, X-Foo-Header')
            ->assertHeader('Access-Control-Allow-Origin', '*')
            ->assertSee('real content');
    }

    /** @test */
    public function it_will_send_a_403_for_invalid_requests()
    {
        config()->set('cors.default_profile.allow_origins', ['https://spatie.be']);

        $this
            ->sendRequest('POST', 'https://laravel.com')
            ->assertStatus(403)
            ->assertSee('Forbidden (cors).');
    }

    /** @test */
    public function it_sends_the_custom_forbidden_response_for_invalid_requests()
    {
        $forbiddenMessage = 'Custom forbidden message';
        $forbiddenStatus = 400;

        config()->set('cors.default_profile.allow_origins', ['https://spatie.be']);
        config()->set('cors.default_profile.forbidden_response.message', $forbiddenMessage);
        config()->set('cors.default_profile.forbidden_response.status', $forbiddenStatus);

        $this
            ->sendRequest('POST', 'https://laravel.com')
            ->assertStatus($forbiddenStatus)
            ->assertSee($forbiddenMessage);
    }

    /** @test */
    public function it_will_be_a_valid_profile_if_expose_header_is_not_set()
    {
        config()->set('cors.default_profile.expose_headers', null);

        $this
            ->sendRequest('POST', 'https://spatie.be')
            ->assertSuccessful()
            ->assertHeader('Access-Control-Expose-Headers', '')
            ->assertHeader('Access-Control-Allow-Origin', '*')
            ->assertSee('real content');
    }

    /** @test */
    public function it_adds_the_cors_headers_on_a_valid_stream_request()
    {
        $this
            ->sendRequest('POST', 'https://spatie.be', 'test-cors-stream')
            ->assertSuccessful()
            ->assertHeader('Access-Control-Allow-Origin', '*');
    }

    public function sendRequest(string $method, string $origin, string $uri = 'test-cors')
    {
        $headers = [
            'Origin' => $origin,
        ];

        $server = $this->transformHeadersToServerVars($headers);

        return $this->call($method, $uri, [], [], [], $server);
    }
}
