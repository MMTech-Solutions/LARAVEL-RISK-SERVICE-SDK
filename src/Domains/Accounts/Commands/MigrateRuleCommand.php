<?php

declare(strict_types=1);

namespace MmtRiskSdk\Domains\Accounts\Commands;

use MmtRiskSdk\Contracts\CommandInterface;

final class MigrateRuleCommand implements CommandInterface
{
    /** @param list<MigrateRuleConditionCommand> $conditions */
    public function __construct(
        public string $name,
        public bool $enabled,
        public int $notify_after_matches,
        public array $conditions,
    ) {}

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'enabled' => $this->enabled,
            'notify_after_matches' => $this->notify_after_matches,
            'conditions' => array_map(static fn (MigrateRuleConditionCommand $condition): array => $condition->toArray(), $this->conditions),
        ];
    }
}
