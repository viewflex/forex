# Forex

[![GitHub license](https://img.shields.io/github/license/mashape/apistatus.svg?maxAge=2592000)](LICENSE.md)

Providing live currency exchange rates in Laravel and Lumen, with configurable provider and caching.

## Overview

For live currency exchange rates, your choice of provider depends on various factors, including pricing, features, and reliability, which sometimes change. The purpose of this package is to provide a fixed exchange rate endpoint for your application, which can be configured to use one of the various popular providers, and re-configured at any time to use a different provider if necessary.

## Installation

Via Composer:

``` bash
$ composer require viewflex/forex
```

## Configuration

Configuration values must be specified in the application's `.env` file, or in your environment's `$_ENV` or `$_SERVER` arrays -  all of these options are supported transparently. Below are sample configurations for the currently supported providers. You can also customize the cache refresh interval as described below.

### Supported Providers

#### Currency Converter API (free)

```
FOREX_PROVIDER=CurrencyConverterApiFree
FOREX_PROVIDER_URL=http://free.currencyconverterapi.com/api/v6/convert
```

#### Currency Converter API (paid)

```
FOREX_PROVIDER=CurrencyConverterApi
FOREX_PROVIDER_URL=https://api.currencyconverterapi.com/api/v6/convert
FOREX_PROVIDER_KEY=<your-provider-key>
```

#### Fixer (free and paid)

```
FOREX_PROVIDER=Fixer
FOREX_PROVIDER_URL=https://data.fixer.io/api/latest
FOREX_PROVIDER_KEY=<your-provider-key>
```

**The free subscription supports only EUR as the base currency.*

#### Open Exchange Rates (free and paid)

```
FOREX_PROVIDER=OpenExchangeRates
FOREX_PROVIDER_URL=https://openexchangerates.org/api/latest.json
FOREX_PROVIDER_KEY=<your-provider-key>
```

**The free subscription supports only USD as the base currency.*

### Caching

Use the `FOREX_CACHE_MINUTES` environment variable to specify how long a rate is cached.  Unless specified, the default of 720 (12 hours) is used. Setting this variable to 0 disables caching of rates. Using a longer cache refresh interval can help you to avoid interruption of service (or extra charges if using a paid service).

## Usage

``` php
$server = new \Viewflex\Forex\Forex();
echo $server->getRate('USD', 'CAD');
```

## Tests

Tests can be run as described in the [test documentation](./tests/README.md).

## License

This software is offered for use under the [MIT License](LICENSE.md).

## Changelog

Release versions are tracked in the [Changelog](CHANGELOG.md).