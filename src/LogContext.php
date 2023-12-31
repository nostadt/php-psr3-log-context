<?php

declare(strict_types=1);

namespace Nostadt\Psr3LogContext;

use Nostadt\Psr3LogContext\ValueObject\LogData;

final class LogContext
{
    /**
     * @var array<int,LogData>
     */
    private array $logData;

    public function __construct(LogData ...$logData)
    {
        $this->logData = array_values($logData);
    }

    public static function createFromException(\Throwable $exception): self
    {
        $logData = [
            new LogData('exception_message', $exception->getMessage()),
            new LogData('exception_code', (string)$exception->getCode()),
            new LogData('exception_trace', $exception->getTraceAsString()),
        ];

        if ($exception->getPrevious() instanceof \Throwable) {
            $previous = $exception->getPrevious();
            $logData[] = new LogData('previous_exception_message', $previous->getMessage());
            $logData[] = new LogData('previous_exception_code', (string)$previous->getCode());
            $logData[] = new LogData('previous_exception_trace', $previous->getTraceAsString());
        }

        return new self(...$logData);
    }

    public function mergeLogContext(self $logContext): self
    {
        return new self(
            ...array_merge($this->logData, $logContext->getLogData())
        );
    }

    /**
     * @return array<string,string>
     */
    public function toArray(): array
    {
        $array = [];
        foreach ($this->logData as $logData) {
            $array[$logData->key] = $logData->value;
        }
        return $array;
    }

    /**
     * @return array<int,LogData>
     */
    private function getLogData(): array
    {
        return $this->logData;
    }
}