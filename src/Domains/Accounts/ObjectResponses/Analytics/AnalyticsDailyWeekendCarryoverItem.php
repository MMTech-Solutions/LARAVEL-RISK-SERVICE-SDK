<?php

declare(strict_types=1);

namespace MmtRiskSdk\Domains\Accounts\ObjectResponses\Analytics;

use MmtRiskSdk\WireHydration\Attributes\WireMapped;

#[WireMapped]
final class AnalyticsDailyWeekendCarryoverItem
{
    public float $mon_after_red_fri;

    public float $mon_after_green_fri;

    public int $fri_red_count;

    public int $fri_green_count;
}
