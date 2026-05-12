<?php

declare(strict_types=1);

namespace MmtRiskSdk\Domains\Accounts\Commands;

use MmtRiskSdk\Contracts\CommandInterface;

final class UpdateAccountCommand implements CommandInterface
{
    public function __construct(
        public ?string $login = null,
        public ?string $broker_id = null,
        public ?string $risk_profile_id = null,
        public ?float $opening_equity = null,
        public ?float $opening_balance = null,
        public ?float $daily_loss_limit_pct = null,
        public ?float $breakeven_band_pct = null,
    ) {}

    public function toArray(): array
    {
        return array_filter([
            'login' => $this->login,
            'broker_id' => $this->broker_id,
            'risk_profile_id' => $this->risk_profile_id,
            'opening_equity' => $this->opening_equity,
            'opening_balance' => $this->opening_balance,
            'daily_loss_limit_pct' => $this->daily_loss_limit_pct,
            'breakeven_band_pct' => $this->breakeven_band_pct,
        ], static fn (mixed $v): bool => ! is_null($v));
    }
}
