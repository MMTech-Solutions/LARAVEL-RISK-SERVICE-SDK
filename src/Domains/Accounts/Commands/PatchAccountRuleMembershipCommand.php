<?php

declare(strict_types=1);

namespace MmtRiskSdk\Domains\Accounts\Commands;

use MmtRiskSdk\Contracts\CommandInterface;

final class PatchAccountRuleMembershipCommand implements CommandInterface
{
    public function __construct(
        public ?bool $reset_streak_on_match = null,
        public ?bool $unassign_on_match = null,
    ) {}

    public function toArray(): array
    {
        return array_filter([
            'reset_streak_on_match' => $this->reset_streak_on_match,
            'unassign_on_match' => $this->unassign_on_match,
        ], static fn (mixed $v): bool => ! is_null($v));
    }
}
