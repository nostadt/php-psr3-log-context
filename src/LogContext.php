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
        return new self(
            new LogData('exception_message', $exception->getMessage()),
            new LogData('exception_code', (string)$exception->getCode()),
        );
    }

    /**
     * @return array<string,string|int|bool>
     */
    public function toArray(): array
    {
        $array = [];
        foreach ($this->logData as $logData) {
            $array[$logData->key] = $logData->value;
        }
        return $array;
    }
}