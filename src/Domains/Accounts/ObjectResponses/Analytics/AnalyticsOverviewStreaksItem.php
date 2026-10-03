<?php

declare(strict_types=1);

namespace MmtRiskSdk\Domains\Accounts\ObjectResponses\Analytics;

use MmtRiskSdk\WireHydration\Attributes\WireMapped;

#[WireMapped]
final class AnalyticsOverviewStreaksItem
{
    public int $win_streak_current;

    public int $loss_streak_current;

    public int $win_streak_max;

    public int $loss_streak_max;
}
