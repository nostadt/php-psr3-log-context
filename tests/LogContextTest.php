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

    public function testCreateFromExceptionWithoutPreviousException(): void
    {
        $subject = LogContext::createFromException(new \Exception('The message', 123));

        self::assertSame('The message', $subject->toArray()['exception_message']);
        self::assertSame('123', $subject->toArray()['exception_code']);
        self::assertTrue(array_key_exists('exception_trace', $subject->toArray()));
    }

    public function testCreateFromExceptionWithPreviousException(): void
    {
        $subject = LogContext::createFromException(
            new \Exception(
                'The message', 123,
                new \Exception( 'Previous message', 456),
            )
        );

        // Main
        self::assertSame('The message', $subject->toArray()['exception_message']);
        self::assertSame('123', $subject->toArray()['exception_code']);
        self::assertTrue(array_key_exists('exception_trace', $subject->toArray()));

        // Previous
        self::assertSame('Previous message', $subject->toArray()['previous_exception_message']);
        self::assertSame('456', $subject->toArray()['previous_exception_code']);
        self::assertTrue(array_key_exists('previous_exception_trace', $subject->toArray()));
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