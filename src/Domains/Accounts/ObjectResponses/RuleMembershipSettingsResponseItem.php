<?php

declare(strict_types=1);

namespace MmtRiskSdk\Domains\Accounts\ObjectResponses;

use MmtRiskSdk\WireHydration\Attributes\WireMapped;

#[WireMapped]
final class RuleMembershipSettingsResponseItem
{
    public bool $notify_on_match = true;

    public bool $reset_streak_on_match = false;

    public bool $unassign_on_match = false;
}
