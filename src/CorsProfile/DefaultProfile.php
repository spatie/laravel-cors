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
        return config('cors-lite.default_profile.maxAge');
    }

    public function isAllowed(): bool
    {
        if (! in_array($this->request->method(), $this->allowMethods())) {
            return false;
        }

        if (in_array('*', $this->allowOrigins())) {
            return true;
        }

        return in_array($request->header('Origin'), $this->allowOrigins());
    }
}
