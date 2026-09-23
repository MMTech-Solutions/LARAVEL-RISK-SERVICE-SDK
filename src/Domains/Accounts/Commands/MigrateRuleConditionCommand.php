<?php

declare(strict_types=1);

namespace MmtRiskSdk\Domains\Accounts\Commands;

use MmtRiskSdk\Contracts\CommandInterface;

final class MigrateRuleConditionCommand implements CommandInterface
{
    public function __construct(
        public string $type,
        public string $metric,
        public string $operator,
        public float|int $value,
    ) {}

    public function toArray(): array
    {
        return get_object_vars($this);
    }
}
