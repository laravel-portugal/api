## Laravel-Portugal API

[![Build Status](https://img.shields.io/travis/laravel-portugal/api/master.svg?style=flat-square)](https://travis-ci.org/laravel-portugal/api)
[![Coverage Status](https://coveralls.io/repos/github/laravel-portugal/api/badge.svg?branch=master)](https://coveralls.io/github/laravel-portugal/api?branch=master)

## Installation

**Requirements**

- PHP >= 7.4
- MySQL >= 8, MariaDB >= 10 or PostgreSQL >= 11

**Steps to activate this project**

1. Clone this repository.
2. Run `composer install` to install all dependencies (add `--no-dev` if you're using this in production).
3. Run `cp .env.example .env` to create an `.env` file based on the distributed `.env.example` file.
4. Run `php artisan key:generate` to generate a new application key.
5. Update the `.env` file with the connection details for the database.
6. Run `php artisan migrate` to create the database schema.

## Testing

This project is fully tested. We have an [automatic pipeline](https://travis-ci.org/laravel-portugal/api) and an [automatic code quality analysis](https://coveralls.io/github/laravel-portugal/api) tool set up to continuously test and assert the quality of all code published in this repository, but you can execute the test suite yourself by running the following command:

``` bash
vendor/bin/phpunit
```

_Note: This assumes you've run `composer install` (without the `--no-dev` option)._

**We aim to keep the master branch always deployable.** Exceptions may happen, but they should be extremely rare.

## Changelog

Please see [CHANGELOG](changelog.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](contributing.md) for details.

### Security

If you discover any security related issues, please [talk to us on Discord](https://laravel.pt) instead of using the issue tracker.

## Credits

- [José Postiga](https://github.com/josepostiga)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](license.md) for more information.
