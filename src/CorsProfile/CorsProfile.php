<?php

namespace Spatie\CorsLite\CorsProfile;

interface CorsProfile
{
    public function setRequest($request);

    public function allowOrigins(): array;

    public function allowMethods(): array;

    public function allowHeaders(): array;

    public function maxAge(): int;

    public function isAllowed(): bool;
}
