# DRAFT: PSR-3 Log-Context Made Easy

**Preamble**

Especially for larger projects logging is essential. You do want to have all the data you need when looking in the past.
Nothing is worse than having a serious issue on the production system but lacking of helpful context information.

The goal of this small package is to provide a structure that makes consistent logging easy and to reduce noise coming
from logging. For me consistent logging means always re-using keys.

In addition, when creating log context arrays on the fly you don't really know the format the data will be displayed.
In the worst case Type Errors or Exceptions will be thrown. You certainly want to avoid that.

**How is this package helping?**

This package provides classes and an interface to implement easy to understand log context.

- `LogData`: key=>value to forbid bad values.
- `LogContext`: Storage for `LogData` and a method to generate an `array`.
- `LogContextConvertibleInterface`: This interface enforces a method to convert the given object to a `LogContext`.


**Further link/s**
- https://www.php-fig.org/psr/psr-3/

## Examples

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

```bash
# Build the image
docker build -t psr3-log-context .

# Start the container
docker run -v $(pwd):/app -p 22:22 psr3-log-context

# SSH into the container (pw=test)
ssh test@localhost
cd /app
```

Stop the running container:
```bash
docker ps | grep psr3-log-context
docker stop {container_id}
```