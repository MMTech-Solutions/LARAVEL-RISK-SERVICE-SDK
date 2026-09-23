<?php

declare(strict_types=1);

namespace MmtRiskSdk\Domains\Accounts\Commands;

use MmtRiskSdk\Contracts\CommandInterface;

final class MigrateAccountCommand implements CommandInterface
{
    public function __construct(
        public string $login,
        public string $started_at,
        public float $start_balance,
        public int $trading_days_count,
        public float $opening_credit,
        public float $equity_peak,
        public float $day_equity_start,
        public float $day_balance_start,
    ) {}

    public function toArray(): array
    {
        return get_object_vars($this);
    }
}
