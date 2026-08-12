<?php

declare(strict_types=1);

namespace MmtRiskSdk\Domains\Accounts\Commands;

use MmtRiskSdk\Contracts\CommandInterface;
use MmtRiskSdk\Domains\Rules\RuleActionInputItem;
use MmtRiskSdk\Domains\Rules\RuleActionPayloadBuilder;

/**
 * Rule template for account provisioning (OpenAPI: ProvisionRuleCreate).
 */
final class ProvisionRuleCommand implements CommandInterface
{
    /**
     * @param  list<array<string, mixed>>  $conditions
     * @param  list<RuleActionInputItem>  $actions
     */
    public function __construct(
        public array $conditions,
        public ?string $name = null,
        public ?string $description = null,
        public bool $enabled = true,
        public int $notify_after_matches = 1,
        public ?string $cron_expression = null,
        public ?int $violation_interval = null,
        public array $actions = [],
    ) {}

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        $payload = [
            'name' => $this->name,
            'description' => $this->description,
            'enabled' => $this->enabled,
            'notify_after_matches' => $this->notify_after_matches,
            'cron_expression' => $this->cron_expression,
            'violation_interval' => $this->violation_interval,
            'conditions' => $this->conditions,
        ];

        if ($this->actions !== []) {
            $payload['actions'] = RuleActionPayloadBuilder::toPayloadList($this->actions);
        }

        return array_filter($payload, static fn (mixed $v): bool => ! is_null($v));
    }
}
