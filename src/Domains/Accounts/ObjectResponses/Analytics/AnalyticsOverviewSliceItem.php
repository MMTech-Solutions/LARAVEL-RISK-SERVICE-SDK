<?php

declare(strict_types=1);

namespace MmtRiskSdk\Domains\Accounts\ObjectResponses\Analytics;

use MmtRiskSdk\WireHydration\Attributes\WireMapped;

#[WireMapped]
final class AnalyticsOverviewSliceItem
{
    public string $generated_at;

    public AnalyticsFilterRangeItem $range;

    public AnalyticsOverviewKpisItem $kpis;

    public AnalyticsOverviewCostsItem $costs;

    public AnalyticsOverviewEquityItem $equity;

    public AnalyticsOverviewRiskItem $risk;

    public AnalyticsOverviewDaysItem $days;

    public AnalyticsOverviewStreaksItem $streaks;

    public AnalyticsOverviewLiveItem $live;

    public AnalyticsOverviewHealthItem $health;
}
