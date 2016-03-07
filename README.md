# Forex

A Laravel package providing live currency exchange rates, with caching.


## Install

Via Composer

``` bash
$ composer require viewflex/forex
```

## Usage

``` php
$server = new \Viewflex\Forex\Forex();
echo $server->getRate('USD', 'CAD');
```

## Testing

``` bash
$ phpunit
```

## License

The MIT License (MIT).