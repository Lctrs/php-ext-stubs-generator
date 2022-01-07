# php-ext-stubs-generator

[![Integrate](https://github.com/Lctrs/php-ext-stubs-generator/workflows/Integrate/badge.svg)](https://github.com/Lctrs/php-ext-stubs-generator/actions)
[![Release](https://github.com/Lctrs/php-ext-stubs-generator/workflows/Release/badge.svg)](https://github.com/Lctrs/php-ext-stubs-generator/actions)
[![Renew](https://github.com/Lctrs/php-ext-stubs-generator/workflows/Renew/badge.svg)](https://github.com/Lctrs/php-ext-stubs-generator/actions)

[![Mutation Score](https://img.shields.io/endpoint?url=https%3A%2F%2Fbadge-api.stryker-mutator.io%2Fgithub.com%2FLctrs%2Fphp-ext-stubs-generator%2Fmaster)](https://dashboard.stryker-mutator.io/reports/github.com/Lctrs/php-ext-stubs-generator/master)
[![Code Coverage](https://codecov.io/gh/Lctrs/php-ext-stubs-generator/branch/master/graph/badge.svg)](https://codecov.io/gh/Lctrs/php-ext-stubs-generator)
[![Type Coverage](https://shepherd.dev/github/Lctrs/php-ext-stubs-generator/coverage.svg)](https://shepherd.dev/github/Lctrs/php-ext-stubs-generator)

[![Latest Stable Version](https://img.shields.io/packagist/v/Lctrs/php-ext-stubs-generator?style=flat-square)](https://packagist.org/packages/Lctrs/php-ext-stubs-generator)
[![Total Downloads](https://img.shields.io/packagist/dt/Lctrs/php-ext-stubs-generator?style=flat-square)](https://packagist.org/packages/Lctrs/php-ext-stubs-generator)

## Installation

Run

```sh
composer require --dev lctrs/php-ext-stubs-generator
```

## Usage

```sh
php vendor/bin/generate-stubs-for-ext extension_name
```

By default, the command will places the generated stubs in a directory named `stubs/` at the root of the project. 
You can change the name of this directory by passing a `--target` option to the command as below (nested directories are supported) : 

```sh
php vendor/bin/generate-stubs-for-ext extension_name --target src/stubs/
```

## Changelog

Please have a look at [`CHANGELOG.md`](CHANGELOG.md).

## Contributing

Please have a look at [`CONTRIBUTING.md`](.github/CONTRIBUTING.md).

## License

This package is licensed using the MIT License.

Please have a look at [`LICENSE.md`](LICENSE.md).
