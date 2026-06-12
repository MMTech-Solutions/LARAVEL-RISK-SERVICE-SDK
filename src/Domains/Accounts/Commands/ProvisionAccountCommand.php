<?php

declare(strict_types=1);

namespace MmtRiskSdk\Domains\Accounts\Commands;

use MmtRiskSdk\Contracts\CommandInterface;

/**
 * Atomic account + metric phase + rules provisioning (OpenAPI: AccountProvisionCreate).
 */
final class ProvisionAccountCommand implements CommandInterface
{
    /**
     * @param  list<ProvisionMetricPhaseCommand>  $metric_phases
     */
    public function __construct(
        public string $login,
        public string $broker_id,
        public array $metric_phases,
        public ?string $risk_profile_id = null,
        public ?float $daily_loss_limit_pct = null,
        public float $breakeven_band_pct = 0.0,
    ) {}

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return array_filter([
            'login' => $this->login,
            'broker_id' => $this->broker_id,
            'risk_profile_id' => $this->risk_profile_id,
            'daily_loss_limit_pct' => $this->daily_loss_limit_pct,
            'breakeven_band_pct' => $this->breakeven_band_pct,
            'metric_phases' => array_map(
                static fn (ProvisionMetricPhaseCommand $phase): array => $phase->toArray(),
                $this->metric_phases,
            ),
        ], static fn (mixed $v): bool => ! is_null($v));
    }
}
