<?php

namespace Spatie\CorsLite\CorsProfile;

class DefaultProfile implements CorsProfile
{
    /** Illuminate\Http\Request */
    protected $request;

    public function setRequest($request)
    {
        $this->request = $request;
    }

    public function allowOrigins(): array
    {
        return config('cors-lite.default_profile.allow_origins');
    }

    public function allowMethods(): array
    {
        return config('cors-lite.default_profile.allow_methods');
    }

    public function allowHeaders(): array
    {
        return config('cors-lite.default_profile.allow_headers');
    }

    public function maxAge(): int
    {
        return config('cors-lite.default_profile.max_age');
    }

    public function addCorsHeaders($response)
    {
        return $response
            ->header('Access-Control-Allow-Methods', $this->toString($this->allowMethods()))
            ->header('Access-Control-Allow-Headers', $this->toString($this->allowHeaders()));
    }

    public function addPreflightHeaders($response)
    {
        return $response
            ->header('Access-Control-Allow-Methods', $this->toString($this->allowMethods()))
            ->header('Access-Control-Allow-Headers', $this->toString($this->allowHeaders()))
            ->header('Access-Control-Allow-Origin', $this->toString($this->allowOrigins()))
            ->header('Access-Control-Max-Age', $this->maxAge());
    }

    public function isAllowed(): bool
    {
        if (! in_array($this->request->method(), $this->allowMethods())) {
            return false;
        }

        if (in_array('*', $this->allowOrigins())) {
            return true;
        }

        return in_array($this->request->header('Origin'), $this->allowOrigins());
    }

    protected function toString(array $array): string
    {
        return implode(', ', $array);
    }
}
