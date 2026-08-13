<?php

declare(strict_types=1);

namespace MmtRiskSdk\Domains\Accounts\ObjectResponses\Analytics;

use MmtRiskSdk\WireHydration\Attributes\WireMapped;

#[WireMapped]
final class AnalyticsOverviewStreaksItem
{
    public int $current_win_streak;

    public int $current_loss_streak;

    public int $max_win_streak;

    public int $max_loss_streak;
}
