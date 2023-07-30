<?php

declare(strict_types=1);

namespace Nostadt\Psr3LogContext\Tests;

use Nostadt\Psr3LogContext\LogContext;
use Nostadt\Psr3LogContext\ValueObject\LogData;
use PHPUnit\Framework\TestCase;

final class LogContextTest extends TestCase
{
    public function testToArrayReturnsAllPassedLogData(): void
    {
        $subject = new LogContext(
            new LogData('key1', 'value1'),
            new LogData('key2', 'value2'),
            new LogData('key3', 'value3'),
        );

        self::assertSame(
            [
                'key1' => 'value1',
                'key2' => 'value2',
                'key3' => 'value3',
            ],
            $subject->toArray()
        );
    }

    public function testCreateFromException(): void
    {
        $subject = LogContext::createFromException(new \Exception('The message', 123));

        self::assertSame(
            [
                'exception_message' => 'The message',
                'exception_code' => '123',
            ],
            $subject->toArray()
        );
    }

    public function testMergeLogContext(): void
    {
        $logContext = new LogContext(
            new LogData('key1', 'value1')
        );

        $subject = $logContext->mergeLogContext(
            new LogContext(
                new LogData('key2', 'value2')
            )
        );

        self::assertSame(
            [
                'key1' => 'value1',
                'key2' => 'value2',
            ],
            $subject->toArray()
        );
    }
}