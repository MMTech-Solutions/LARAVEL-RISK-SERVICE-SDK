<?php

declare(strict_types=1);

namespace MmtRiskSdk\Domains\MetricPhases\Commands;

use MmtRiskSdk\Contracts\CommandInterface;

/**
 * Request body for PATCH /accounts/{account_id}/metric-phases/{phase_id}/rules/{rule_id}/membership.
 */
final class PatchPhaseRuleMembershipCommand implements CommandInterface
{
    public function __construct(
        public ?bool $reset_streak_on_match = null,
        public ?bool $unassign_on_match = null,
        public ?string $reset_cron_expression = null,
    ) {}

    /**
     * @return array<string, bool|string>
     */
    public function toArray(): array
    {
        $payload = [
            'reset_streak_on_match' => $this->reset_streak_on_match,
            'unassign_on_match' => $this->unassign_on_match,
            'reset_cron_expression' => $this->reset_cron_expression,
        ];

        return array_filter(
            $payload,
            static fn (mixed $value): bool => $value !== null,
        );
    }
}
