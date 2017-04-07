# Jenga

[![Build Status](http://img.shields.io/travis/madewithlove/jenga.svg?style=flat-square)](https://travis-ci.org/madewithlove/jenga)

PSR-15 middleware stack builder with lazy resolving of middlewares from PSR-11 containers.

## Installation

```bash
composer require madewithlove/jenga
```

## Usage

```php
$middleware = [
    new RobotsMiddleware(),
    RouterMiddleware::class,
];

$stack = new Stack($psrContainer, $middlewares);

$psrResponse = $stack->call($psrServerRequest);
```

You can also plug in the `Stack` object into a different PSR-15 middleware chain,
because it implements the MiddlewareInterface.

```php
$psrResponse = $stack->process($psrServerRequest, $delegate);
```

### Example

You can run an example application, if you have cloned this repository, using:

```bash
php -S 0.0.0.0:8000 example/index.php
```

## Testing

After cloning this project, install its dependencies and run the test suite:

```bash
composer install
vendor/bin/phpunit
```

## License

[MIT](LICENSE)
