<?php

declare(strict_types=1);

namespace MmtRiskSdk\Domains\Accounts\Commands;

use MmtRiskSdk\Contracts\CommandInterface;

final class MigrateAccountsCommand implements CommandInterface
{
    /** @param list<MigrateRuleCommand> $rules @param list<MigrateAccountCommand> $accounts */
    public function __construct(
        public string $broker_id,
        public string $phase_name,
        public ?string $risk_profile_id,
        public float $breakeven_band_pct,
        public array $rules,
        public array $accounts,
    ) {}

    public function toArray(): array
    {
        return [
            'broker_id' => $this->broker_id,
            'phase_name' => $this->phase_name,
            'risk_profile_id' => $this->risk_profile_id,
            'breakeven_band_pct' => $this->breakeven_band_pct,
            'rules' => array_map(static fn (MigrateRuleCommand $rule): array => $rule->toArray(), $this->rules),
            'accounts' => array_map(static fn (MigrateAccountCommand $account): array => $account->toArray(), $this->accounts),
        ];
    }
}
