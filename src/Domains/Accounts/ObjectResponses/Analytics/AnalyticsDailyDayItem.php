<?php

declare(strict_types=1);

namespace MmtRiskSdk\Domains\Accounts\ObjectResponses\Analytics;

use MmtRiskSdk\WireHydration\Attributes\WireMapped;

#[WireMapped]
final class AnalyticsDailyDayItem
{
    public string $date_utc;

    public float $pnl;

    public int $trades;

    public int $wins;

    public int $losses;

    public float $win_rate;
}
