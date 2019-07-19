<?php

namespace Spatie\Cors;

use Closure;
use Spatie\Cors\CorsProfile\CorsProfile;

class Cors
{
    /** @var \Spatie\Cors\CorsProfile\CorsProfile */
    protected $corsProfile;

    public function __construct(CorsProfile $corsProfile)
    {
        $this->corsProfile = $corsProfile;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (! $this->isCorsRequest($request)) {
            return $next($request);
        }

        $this->corsProfile->setRequest($request);

        if (! $this->corsProfile->isAllowed()) {
            return $this->forbiddenResponse();
        }

        if ($this->isPreflightRequest($request)) {
            return $this->handlePreflightRequest();
        }

        $response = $next($request);

        return $this->corsProfile->addCorsHeaders($response);
    }

    protected function isCorsRequest($request): bool
    {
        if (! $request->headers->has('Origin')) {
            return false;
        }

        return $request->headers->get('Origin') !== $request->getSchemeAndHttpHost();
    }

    protected function isPreflightRequest($request): bool
    {
        return $request->getMethod() === 'OPTIONS';
    }

    protected function handlePreflightRequest()
    {
        if (! $this->corsProfile->isAllowed()) {
            return $this->forbiddenResponse();
        }

        return $this->corsProfile->addPreflightHeaders(response(null, 204));
    }

    protected function forbiddenResponse()
    {
        $message = config('cors.default_profile.forbidden_response.message');
        $status = config('cors.default_profile.forbidden_response.status');

        return response(
            $message ?? 'Forbidden (cors).',
            $status ?? 403
        );
    }
}
