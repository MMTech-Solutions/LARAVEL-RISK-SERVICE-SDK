<?php

declare(strict_types=1);

namespace MmtRiskSdk\Domains\Accounts\Commands;

use MmtRiskSdk\Contracts\CommandInterface;

final class EvaluationHistoryRecentCommand implements CommandInterface
{
    /**
     * @param  list<string>  $logins
     */
    public function __construct(
        public array $logins,
        public int $limit = 20,
    ) {}

    public function toArray(): array
    {
        return array_filter([
            'logins' => $this->logins,
            'limit' => $this->limit,
        ], static fn (mixed $v): bool => ! is_null($v));
    }
}
