<?php

declare(strict_types=1);

namespace Nostadt\Psr3LogContext\ValueObject;

final class LogData
{
    public function __construct(
        public readonly string $key,
        public readonly string $value
    ){}
}