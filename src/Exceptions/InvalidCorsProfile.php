<?php

namespace Spatie\CorsLite\Exceptions;

use Exception;
use Spatie\CorsLite\CorsProfile\DefaultProfile;

class InvalidCorsProfile extends Exception
{
    public static function profileDoesNotExtendDefaultProfile(string $className)
    {
        $defaultProfileClass = DefaultProfile::class;

        return new static("The configured cors profile in `{$className}` is invalid. A valid cors profile extends `{$defaultProfileClass}`");
    }
}
