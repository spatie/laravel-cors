# Notice

We have abandoned this package because Laravel 7 introduced native support for CORS. Only use this package if you're on Laravel 6 or below.

# Send CORS headers in a Laravel application

[![Latest Version on Packagist](https://img.shields.io/packagist/v/spatie/laravel-cors.svg?style=flat-square)](https://packagist.org/packages/spatie/laravel-cors)
[![Build Status](https://img.shields.io/travis/spatie/laravel-cors/master.svg?style=flat-square)](https://travis-ci.org/spatie/laravel-cors)
[![Quality Score](https://img.shields.io/scrutinizer/g/spatie/laravel-cors.svg?style=flat-square)](https://scrutinizer-ci.com/g/spatie/laravel-cors)
[![StyleCI](https://styleci.io/repos/113957368/shield?branch=master)](https://styleci.io/repos/113957368)
[![Total Downloads](https://img.shields.io/packagist/dt/spatie/laravel-cors.svg?style=flat-square)](https://packagist.org/packages/spatie/laravel-cors)

This package will add CORS headers to the responses of your Laravel or Lumen app. For more infomation about CORS, see the [Mozilla CORS documentation](https://developer.mozilla.org/en-US/docs/Web/HTTP/CORS).

This package supports preflight requests and is easily configurable to fit your needs.

## Installation

- [Laravel](#laravel)
- [Lumen](#lumen)

### Laravel

You can install the package via Composer:

```bash
composer require spatie/laravel-cors
```

The package will automatically register its service provider.

The provided `Spatie\Cors\Cors` middleware must be registered in the global middleware group.

```php
// app/Http/Kernel.php

protected $middleware = [
    ...
    \Spatie\Cors\Cors::class
];
```

```php
php artisan vendor:publish --provider="Spatie\Cors\CorsServiceProvider" --tag="config"
```

This is the default content of the config file published at `config/cors.php`:

```php
return [
    /*
     * A cors profile determines which origins, methods, headers are allowed for
     * a given requests. The `DefaultProfile` reads its configuration from this
     * config file.
     *
     * You can easily create your own cors profile.
     * More info: https://github.com/spatie/laravel-cors/#creating-your-own-cors-profile
     */
    'cors_profile' => Spatie\Cors\CorsProfile\DefaultProfile::class,

    /*
     * This configuration is used by `DefaultProfile`.
     */
    'default_profile' => [

        'allow_credentials' => false,

        'allow_origins' => [
            '*',
        ],

        'allow_methods' => [
            'POST',
            'GET',
            'OPTIONS',
            'PUT',
            'PATCH',
            'DELETE',
        ],

        'allow_headers' => [
            'Content-Type',
            'X-Auth-Token',
            'Origin',
            'Authorization',
        ],

        'expose_headers' => [
            'Cache-Control',
            'Content-Language',
            'Content-Type',
            'Expires',
            'Last-Modified',
            'Pragma',
        ],

        'forbidden_response' => [
            'message' => 'Forbidden (cors).',
            'status' => 403,
        ],

        /*
         * Preflight request will respond with value for the max age header.
         */
        'max_age' => 60 * 60 * 24,
    ],
];
```

### Lumen

You can install the package via Composer:

```bash
composer require spatie/laravel-cors
```

Copy the config file from the vendor directory:

```bash
cp vendor/spatie/laravel-cors/config/cors.php config/cors.php
```

Register the config file, the middleware and the service provider in `bootstrap/app.php`:

```php
$app->configure('cors');

$app->middleware([
    Spatie\Cors\Cors::class,
]);

$app->register(Spatie\Cors\CorsServiceProvider::class);
```

## Usage

With the middleware installed your API routes should now get appropriate CORS headers. Preflight requests will be handled as well. If a request comes in that is not allowed, Laravel will return a `403` response.

The default configuration of this package allows all requests from any origin (denoted as `'*'`). You probably want to at least specify some origins relevant to your project. If you want to allow requests to come in from `https://spatie.be` and `https://laravel.com` add those domains to the config file:

```php
// config/cors.php

    ...
    'default_profile' => [

    'allow_origins' => [
        'https://spatie.be',
        'https://laravel.com',
    ],
    ...
...
```

If you, for example, want to allow all subdomains from a specific domain, you can use the wildcard asterisk (`*`) and specifiy that:
```php
// config/cors.php

    ...
    'default_profile' => [

    'allow_origins' => [
        'https://spatie.be',
        'https://laravel.com',

        'https://*.spatie.be',
        'https://*.laravel.com',
    ],
    ...
...
```

### Creating your own CORS profile

Imagine you want to specify allowed origins based on the user that is currently logged in. In that case the `DefaultProfile` which just reads the config file won't cut it. Fortunately it's very easy to write your own CORS profile, which is simply a class that extends `Spatie\Cors\DefaultProfile`.

Here's a quick example where it is assumed that you've already added an `allowed_domains` column on your user model:

```php
namespace App\Services\Cors;

use Spatie\Cors\CorsProfile\DefaultProfile;

class UserBasedCorsProfile extends DefaultProfile
{
    public function allowOrigins(): array
    {
        return Auth::user()->allowed_domains;
    }
}
```

You can override the default HTTP status code and message returned when a request is forbidden by editing the `forbidden_response` array in your configuration file:

```php
'forbidden_response' => [
    'message' => 'Your request failed',
    'status' => 400,
],
```

Don't forget to register your profile in the config file.

```php
// config/cors.php

 ...
 'cors_profile' => App\Services\Cors\UserBasedCorsProfile::class,
 ...
```

In the example above we've overwritten the `allowOrigins` method, but of course you may choose to override any of the methods present in `DefaultProfile`.

## Testing

``` bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security

If you discover any security related issues, please email freek@spatie.be instead of using the issue tracker.

## Alternatives

- [barryvdh/laravel-cors](https://github.com/barryvdh/laravel-cors): a tried and tested package. Our package is a modern rewrite of the basic features of Barry's excellent one. We created our own solution because we needed our configuration to be [very flexible](#creating-your-own-cors-profile).

## Postcardware

You're free to use this package, but if it makes it to your production environment we highly appreciate you sending us a postcard from your hometown, mentioning which of our package(s) you are using.

Our address is: Spatie, Samberstraat 69D, 2060 Antwerp, Belgium.

We publish all received postcards [on our company website](https://spatie.be/en/opensource/postcards).

## Credits

- [Freek Van der Herten](https://github.com/freekmurze)
- [All Contributors](../../contributors)

## Support us

Spatie is a webdesign agency based in Antwerp, Belgium. You'll find an overview of all our open source projects [on our website](https://spatie.be/opensource).

Does your business depend on our contributions? Reach out and support us on [Patreon](https://www.patreon.com/spatie).
All pledges will be dedicated to allocating workforce on maintenance and new awesome stuff.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
