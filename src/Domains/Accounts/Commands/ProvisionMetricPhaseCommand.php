<?php

declare(strict_types=1);

namespace MmtRiskSdk\Domains\Accounts\Commands;

use MmtRiskSdk\Contracts\CommandInterface;

/**
 * Metric phase payload for account provisioning (OpenAPI: ProvisionMetricPhaseCreate).
 */
final class ProvisionMetricPhaseCommand implements CommandInterface
{
    /**
     * @param  list<ProvisionRuleCommand>  $rules
     */
    public function __construct(
        public string $name,
        public array $rules,
    ) {}

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'rules' => array_map(
                static fn (ProvisionRuleCommand $rule): array => $rule->toArray(),
                $this->rules,
            ),
        ];
    }
}
