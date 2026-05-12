<?php

declare(strict_types=1);

namespace MmtRiskSdk\Domains\Accounts\Commands;

use MmtRiskSdk\Contracts\CommandInterface;

final class CreateAccountCommand implements CommandInterface
{
    public function __construct(
        public string $login,
        public string $broker_id,
        public ?string $risk_profile_id = null,
        public ?float $daily_loss_limit_pct = null,
        public float $breakeven_band_pct = 0.0,
    ) {}

    public function toArray(): array
    {
        return array_filter([
            'login' => $this->login,
            'broker_id' => $this->broker_id,
            'risk_profile_id' => $this->risk_profile_id,
            'daily_loss_limit_pct' => $this->daily_loss_limit_pct,
            'breakeven_band_pct' => $this->breakeven_band_pct,
        ], static fn (mixed $v): bool => ! is_null($v));
    }
}
