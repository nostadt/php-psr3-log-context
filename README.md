# PSR-3 Log-Context - Structured PSR-3 Logging

[![CI Process](https://github.com/nostadt/php-psr3-log-context/actions/workflows/default.yml/badge.svg)](https://github.com/nostadt/php-psr3-log-context/actions/workflows/default.yml)
[![packagist.org](https://img.shields.io/packagist/v/nostadt/psr3-log-context)](https://packagist.org/packages/nostadt/psr3-log-context)

## Preamble

Especially for larger projects logging is essential. You do want to have all the data you need when looking in the past.
Nothing is worse than having a serious issue on the production system but lacking of helpful context information.
This small package makes it easy to log in a structured manner without cluttering your code.

## Available classes and interface

| Class                                | Description                                                                    |
|--------------------------------------|--------------------------------------------------------------------------------|
| `LogData`                            | Key=>Value combo to forbid bad values                                          |
| `LogContext`                         | Storage for `LogData` and `toArray` method that will be passed as 2nd argument |
| `LogContextConvertibleInterface`     | Make any custom class LogContext compatible.                                   |

**Further link/s**
- https://www.php-fig.org/psr/psr-3/

## Examples

**Use LogContext::createFromException**

This is the recommended way when dealing with Exceptions, unless they implement `LogContextConvertibleInterface`.

```php
<?php

use \Nostadt\Psr3LogContext\LogContext;

try {
    doSomething();
} catch (\Exception $exception) {
    $this->logger->warning(
        $exception->getMessage(),
        LogContext::createFromException($exception)->toArray()
    );
}
```

**Use LogContextConvertibleInterface**

This is the recommend way, because it truly simplifies creating the log-context array.

```php
<?php

class User implements \Nostadt\Psr3LogContext\LogContextConvertibleInterface
{
    public int $id;
    public bool $activated;
    public string $name;

    public function asLogContext(): \Nostadt\Psr3LogContext\LogContext
    {
        return new \Nostadt\Psr3LogContext\LogContext(
            new \Nostadt\Psr3LogContext\ValueObject\LogData('user_id', (string)$this->id),
            new \Nostadt\Psr3LogContext\ValueObject\LogData('user_activated', $this->activated ? 'true' : 'false'),
            new \Nostadt\Psr3LogContext\ValueObject\LogData('user_name', $this->name),
        );
    }
}

$user = new User();
$user->id = 1;
$user->activated = true;
$user->name = 'John Doe';

$logger->warning('My Message', $user->asLogContext()->toArray());
```

**Merge multiple LogContexts**

With previous `User`-class in mind we can merge LogContext-objects.

```php
<?php

use \Nostadt\Psr3LogContext\LogContext;

try {
    registerUser($user);
} catch (\Exception $exception) {
    $this->logger->warning(
        $exception->getMessage(),
        LogContext::createFromException($exception)->mergeLogContext($user->asLogContext())->toArray()
    );
}
```

**Create a LogContext from the scratch**

This can be used in situations in which `LogContext` is not available.

```php
<?php

$logContext = new \Nostadt\Psr3LogContext\LogContext(
    new \Nostadt\Psr3LogContext\ValueObject\LogData('user_uid', '1'),
    new \Nostadt\Psr3LogContext\ValueObject\LogData('user_activated', 'true'),
    new \Nostadt\Psr3LogContext\ValueObject\LogData('user_name', 'John Doe'),
);

$logger->warning('My Message', $logContext->toArray());
```

## Development

**Requirements:**

- [Docker](https://www.docker.com/)
- [Make](https://www.selflinux.org/selflinux/html/make01.html)

If you start fresh, execute:
```bash
make init
make start
```

If you want to run code-quality checks, execute:
```bash
make test
make lint
```

If you are done working, execute:
```bash
make stop
```

If you want to continue working, execute:
```bash
make start
```

If you want to clean up the system, execute:
```bash
make clean
```
