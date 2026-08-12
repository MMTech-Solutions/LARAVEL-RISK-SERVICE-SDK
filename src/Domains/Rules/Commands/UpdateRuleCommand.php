<?php

declare(strict_types=1);

namespace MmtRiskSdk\Domains\Rules\Commands;

use MmtRiskSdk\Contracts\CommandInterface;
use MmtRiskSdk\Domains\Rules\RuleActionInputItem;
use MmtRiskSdk\Domains\Rules\RuleActionPayloadBuilder;

final class UpdateRuleCommand implements CommandInterface
{
    /**
     * @param  list<string>|null  $phaseIds
     * @param  list<array<string, mixed>>|null  $conditions
     * @param  list<RuleActionInputItem>|null  $actions
     */
    public function __construct(
        public ?array $phaseIds = null,
        public ?string $name = null,
        public ?string $description = null,
        public ?bool $enabled = null,
        public ?int $notify_after_matches = null,
        public ?string $cron_expression = null,
        public ?int $violation_interval = null,
        public ?array $conditions = null,
        public ?array $actions = null,
    ) {}

    public function toArray(): array
    {
        $payload = [
            'phase_ids' => $this->phaseIds,
            'name' => $this->name,
            'description' => $this->description,
            'enabled' => $this->enabled,
            'notify_after_matches' => $this->notify_after_matches,
            'cron_expression' => $this->cron_expression,
            'violation_interval' => $this->violation_interval,
            'conditions' => $this->conditions,
        ];

        if ($this->actions !== null) {
            $payload['actions'] = RuleActionPayloadBuilder::toPayloadList($this->actions);
        }

        return array_filter($payload, static fn (mixed $v): bool => ! is_null($v));
    }
}
