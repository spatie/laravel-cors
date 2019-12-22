<?php

namespace Spatie\Cors\CorsProfile;

class DefaultProfile implements CorsProfile
{
    /** @var \Illuminate\Http\Request */
    protected $request;

    public function setRequest($request)
    {
        $this->request = $request;
    }

    public function allowCredentials(): bool
    {
        return config('cors.default_profile.allow_credentials') ?? false;
    }

    public function allowOrigins(): array
    {
        return config('cors.default_profile.allow_origins');
    }

    public function allowMethods(): array
    {
        return config('cors.default_profile.allow_methods');
    }

    public function allowHeaders(): array
    {
        return config('cors.default_profile.allow_headers');
    }

    public function exposeHeaders(): array
    {
        return config('cors.default_profile.expose_headers') ?? [];
    }

    public function maxAge(): int
    {
        return config('cors.default_profile.max_age');
    }

    public function addCorsHeaders($response)
    {
        if ($this->allowCredentials()) {
            $response->headers->set('Access-Control-Allow-Credentials', 'true');
        }

        $response->headers->set('Access-Control-Allow-Origin', $this->allowedOriginsToString());
        $response->headers->set('Access-Control-Expose-Headers', $this->toString($this->exposeHeaders()));

        return $response;
    }

    public function addPreflightHeaders($response)
    {
        if ($this->allowCredentials()) {
            $response->headers->set('Access-Control-Allow-Credentials', 'true');
        }

        $response->headers->set('Access-Control-Allow-Methods', $this->toString($this->allowMethods()));
        $response->headers->set('Access-Control-Allow-Headers', $this->toString($this->allowHeaders()));
        $response->headers->set('Access-Control-Allow-Origin', $this->allowedOriginsToString());
        $response->headers->set('Access-Control-Max-Age', $this->maxAge());

        return $response;
    }

    public function isAllowed(): bool
    {
        if (! in_array($this->request->method(), $this->allowMethods())) {
            return false;
        }

        if (in_array('*', $this->allowOrigins())) {
            return true;
        }

        $matches = collect($this->allowOrigins())->filter(function ($allowedOrigin) {
            return fnmatch($allowedOrigin, $this->request->header('Origin'));
        });

        return $matches->count() > 0;
    }

    protected function toString(array $array): string
    {
        return implode(', ', $array);
    }

    protected function allowedOriginsToString(): string
    {
        if (! $this->isAllowed()) {
            return '';
        }

        if (in_array('*', $this->allowOrigins()) && ! $this->allowCredentials()) {
            return '*';
        }

        return $this->request->header('Origin');
    }
}
