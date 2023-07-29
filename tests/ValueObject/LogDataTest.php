<?php

declare(strict_types=1);

namespace Nostadt\Psr3LogContext\Tests\ValueObject;

use Nostadt\Psr3LogContext\ValueObject\LogData;
use PHPUnit\Framework\TestCase;

final class LogDataTest extends TestCase
{
    public function testLogDataCreation(): void
    {
        $subject = new LogData('dummy-key', 'dummy-value');

        self::assertSame('dummy-key', $subject->key);
        self::assertSame('dummy-value', $subject->value);
    }
}
