<?php

namespace Spatie\Cors\CorsProfile;

interface CorsProfile
{
    public function setRequest($request);

    public function allowOrigins(): array;

    public function allowMethods(): array;

    public function allowHeaders(): array;

    public function exposeHeaders(): array;

    public function addCorsHeaders($response);

    public function addPreflightHeaders($response);

    public function maxAge(): int;

    public function isAllowed(): bool;
}
