<?php

declare(strict_types=1);

namespace MmtRiskSdk\Domains\Accounts\ObjectResponses\Analytics;

use MmtRiskSdk\WireHydration\Attributes\WireMapped;

#[WireMapped]
final class AnalyticsOverviewDaysItem
{
    public int $days_traded;

    public int $days_positive;

    public int $days_negative;

    public int $max_consecutive_positive;

    public int $max_consecutive_negative;

    public int $current_consecutive_positive;

    public float $consistency_largest_day_pct;
}
