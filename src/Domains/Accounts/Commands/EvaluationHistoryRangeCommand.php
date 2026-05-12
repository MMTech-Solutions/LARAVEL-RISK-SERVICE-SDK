<?php

declare(strict_types=1);

namespace MmtRiskSdk\Domains\Accounts\Commands;

use MmtRiskSdk\Contracts\CommandInterface;

final class EvaluationHistoryRangeCommand implements CommandInterface
{
    /**
     * @param  list<string>  $logins
     */
    public function __construct(
        public array $logins,
        public string $from,
        public string $to,
    ) {}

    public function toArray(): array
    {
        return [
            'logins' => $this->logins,
            'from' => $this->from,
            'to' => $this->to,
        ];
    }
}
