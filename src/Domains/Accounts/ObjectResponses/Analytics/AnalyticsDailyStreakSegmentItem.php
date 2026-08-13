<?php

declare(strict_types=1);

namespace MmtRiskSdk\Domains\Accounts\ObjectResponses\Analytics;

use MmtRiskSdk\WireHydration\Attributes\WireMapped;

#[WireMapped]
final class AnalyticsDailyStreakSegmentItem
{
    public string $sign;

    public int $length;

    public string $start_date;

    public string $end_date;
}
