<?php

declare(strict_types=1);

namespace MmtRiskSdk\Domains\Accounts\ObjectResponses\Analytics;

use MmtRiskSdk\WireHydration\Attributes\WireMapped;

#[WireMapped]
final class AnalyticsDailySliceItem
{
    public string $generated_at;

    public AnalyticsFilterRangeItem $range;

    /** @var AnalyticsDailyDayItem[] */
    public array $days = [];

    public AnalyticsDailyStatsItem $stats;

    public AnalyticsDailyDayBehaviorItem $day_behavior;

    public AnalyticsCumulativePnlItem $cumulative_pnl;

    /** @var AnalyticsMonthTotalItem[] */
    public array $month_totals = [];
}
