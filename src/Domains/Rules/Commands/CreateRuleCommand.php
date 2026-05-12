<?php

declare(strict_types=1);

namespace MmtRiskSdk\Domains\Rules\Commands;

use MmtRiskSdk\Contracts\CommandInterface;

final class CreateRuleCommand implements CommandInterface
{
    /**
     * @param  list<string>  $accountIds
     * @param  list<array<string, mixed>>  $conditions
     */
    public function __construct(
        public array $conditions,
        public array $accountIds = [],
        public ?string $name = null,
        public ?string $description = null,
        public bool $enabled = true,
        public int $notify_after_matches = 1,
        public ?string $cron_expression = null,
        public ?int $violation_interval = null,
    ) {}

    public function toArray(): array
    {
        return array_filter([
            'account_ids' => $this->accountIds,
            'name' => $this->name,
            'description' => $this->description,
            'enabled' => $this->enabled,
            'notify_after_matches' => $this->notify_after_matches,
            'cron_expression' => $this->cron_expression,
            'violation_interval' => $this->violation_interval,
            'conditions' => $this->conditions,
        ], static fn (mixed $v): bool => ! is_null($v));
    }
}
