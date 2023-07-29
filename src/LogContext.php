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