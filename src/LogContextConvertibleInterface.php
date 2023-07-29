<?php

declare(strict_types=1);

namespace Nostadt\Psr3LogContext;

interface LogContextConvertibleInterface
{
    public function asLogContext(): LogContext;
}