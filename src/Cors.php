<?php

namespace Spatie\CorsLite;

use Closure;
use Spatie\CorsLite\CorsProfile\CorsProfile;
use Illuminate\Http\Response;

class Cors
{
    /** \Spatie\CorsLite\CorsProfile\CorsProfile */
    protected $corsProfile;

    /*
    public function __construct(CorsProfile $corsProfile)
    {
        $this->corsProfile = $corsProfile;
    }
    */

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $this->corsProfile = app(CorsProfile::class);

        if ($this->isPreflightRequest($request)) {
            return $this->handlePreflightRequest();
        }

        $response = $next($request);

        return $response
            ->header('Access-Control-Allow-Methods', $this->corsProfile->allowMethods())
            ->header('Access-Control-Allow-Headers', $this->corsProfile->allowHeaders());
    }

    protected function isPreflightRequest($request): bool
    {
        return $request->getMethod() === "OPTIONS";
    }

    protected function handlePreflightRequest()
    {
        if (! $this->corsProfile->isAllowed()) {
            return response('Forbidden.', 403);
        }

        return response('OK', 200, $headers)
            ->header('Access-Control-Allow-Methods', $this->corsProfile->allowMethods())
            ->header('Access-Control-Allow-Headers', $this->corsProfile->allowHeaders())
            ->header('Access-Control-Allow-Origin', $this->corsProfile->allowOrigins())
            ->header('Access-Control-Max-Age', $this->corsProfile->maxAge());
    }
}
