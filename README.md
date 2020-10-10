## Laravel-Portugal API

![Run tests](https://github.com/laravel-portugal/api/workflows/Run%20tests/badge.svg)
[![Coverage Status](https://coveralls.io/repos/github/laravel-portugal/api/badge.svg?branch=master)](https://coveralls.io/github/laravel-portugal/api?branch=master)

## Installation

**Requirements**

- PHP >= 7.4
- MySQL >= 8, MariaDB >= 10 or PostgreSQL >= 11

**Steps to activate this project**

1. Clone this repository.
2. Run `composer install` to install all dependencies (add `--no-dev` if you're using this in production).
3. Run `cp .env.example .env` to create an `.env` file based on the distributed `.env.example` file.
4. Update the `.env` file with a new `APP_KEY` and the connection details for the database.
5. Run `php artisan migrate` to create the database schema.

## Testing

This project is fully tested. We have an [automatic pipeline](https://github.com/laravel-portugal/api/actions) and an [automatic code quality analysis](https://coveralls.io/github/laravel-portugal/api) tool set up to continuously test and assert the quality of all code published in this repository, but you can execute the test suite yourself by running the following command:

``` bash
vendor/bin/phpunit
```

_Note: This assumes you've run `composer install` (without the `--no-dev` option)._

**We aim to keep the master branch always deployable.** Exceptions may happen, but they should be extremely rare.

## Documentation

Please see the [public documentation site](https://laravel-portugal.stoplight.io/docs/api/docs/1.Introduction.md).

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security

Please see [SECURITY](SECURITY.md) for details.

## Credits

- [José Postiga](https://github.com/josepostiga)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
