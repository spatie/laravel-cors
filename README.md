**WORK IN PROGRESS, DO NOT USE YET**

# Send CORS headers in a Laravel application

[![Latest Version on Packagist](https://img.shields.io/packagist/v/spatie/laravel-cors.svg?style=flat-square)](https://packagist.org/packages/spatie/laravel-cors)
[![Build Status](https://img.shields.io/travis/spatie/laravel-cors/master.svg?style=flat-square)](https://travis-ci.org/spatie/laravel-cors)
[![SensioLabsInsight](https://img.shields.io/sensiolabs/i/e913c9eb-556b-4e2e-84b8-3913ed46a87a.svg?style=flat-square)](https://insight.sensiolabs.com/projects/e913c9eb-556b-4e2e-84b8-3913ed46a87a)
[![Quality Score](https://img.shields.io/scrutinizer/g/spatie/laravel-cors.svg?style=flat-square)](https://scrutinizer-ci.com/g/spatie/laravel-cors)
[![StyleCI](https://styleci.io/repos/113957368/shield?branch=master)](https://styleci.io/repos/113957368)
[![Total Downloads](https://img.shields.io/packagist/dt/spatie/laravel-cors.svg?style=flat-square)](https://packagist.org/packages/spatie/laravel-cors)

This package will add CORS headers to the reponses of your Laravel. Read [this excellent article](https://spring.io/understanding/CORS) on the subject if you want to understand what CORS is all about.

This package support preflight request and is easily configurable to fit your needs.

## Installation

You can install the package via composer:

```bash
composer require spatie/laravel-cors
```

The package will automatically register it's service provider.

The provided `Spatie\Cors\Cors` middleware can be registered in the api middleware group.

```php
// app/Http/Kernel.php

protected $middlewareGroups = [
    'api' => [
        ...
        \Spatie\Cors\Cors::class
    ],
];
```

Or you could opt to register it as global middleware.

```php
// app/Http/Kernel.php

protected $middleware = [
    ...
    \Spatie\Cors\Cors::class
];
```

Optionally you can publish the config file with:

```php
php artisan vendor:publish --provider="Spatie\Cors\CorsServiceProvider" --tag="config"
```

This is the default content of the config file published at `config/cors.php`:

```php
return [

    /*
     * A cors profile determines which orgins, methods, headers are allowed for
     * a given requests. The `DefaultProfile` reads its configuration from this
     * config file.
     *
     * You can easily create your own cors profile.
     * More info: https://github.com/spatie/laravel-cors/#creating-your-own-cors-profile
     */
    'cors_profile' => Spatie\Cors\CorsProfile\DefaultProfile::class,

    /*
     * These configuration is used by `DefaultProfile`.
     */
    'default_profile' => [

        'allow_origins' => [
            '*'
        ],

        'allow_methods' => [
            'POST',
            'GET',
            'OPTIONS',
            'PUT',
            'DELETE'
        ],

        'allow_headers' => [
            'Content-Type',
            'X-Auth-Token',
            'Origin',
            'Authorization',
        ],

        /*
         * Preflight request will respond with value for the max age header.
         */
        'max_age' => 60 * 60 * 24,
    ],
];
```

## Usage

With the middleware installed your api routes should now get apprioriate cors headers. Preflight requests will be handled as well. If a request comes in that is not allowed, Laravel will return a `403` response.

The default configuration of this package allows all requests from any origin. You probably want to at least specify some origins. If you want to allow requests to come in in from `https://spatie.be` and `https://laravel.com` add those domains to the config file:

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

### Creating your own cors profile

Imagine you want to specify allowed origins based on the user that is currently logged in. In that case the `DefaultProfile` which just reads the config file won't cut it. Fortunately it's very easy to write your own cors profile. A valid cors profile is any class that extends `Spatie\Cors\DefaultProfile`.

Here's a quick example where it is assumed that you've already added a `allowed_domains` column on your user model:

```php
namespace App\Services\Cors;

use Spatie\Cors\DefaultProfile;

class UserBasedCorsProfile extends DefaultProfile;
{
    public function allowOrigins(): array
    {
        return Auth::user()->allowed_domains;
    }
}
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

- [barryvdh/laravel-cors](barryvdh/laravel-cors): a tried and tested package. Our package is a modern rewrite of the basic features of Barry's excellent one. We created our own solution because we needed our configuration to be [very flexible](#creating-your-own-cors-profile).

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
