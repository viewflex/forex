# Tests

## Setup

Tests extend the `TestCase` class installed with Laravel. When using Laravel 5.4 or greater, this line is necessary in tests. With prior Laravel versions, this line must be commented out before trying to run tests:

```php
use Tests\TestCase;
```

## Running Tests

Run the `phpunit` executable from the `forex/tests` directory. You may have installed phpunit elsewhere, but to run the copy installed with Laravel, execute this command:

```bash
./../../../../vendor/bin/phpunit
```
