<?php

declare(strict_types=1);

namespace MmtRiskSdk\Domains\Accounts\ObjectResponses\Analytics;

use MmtRiskSdk\WireHydration\Attributes\WireMapped;

#[WireMapped]
final class AnalyticsDashboardResponseItem
{
    public string $generated_at;

    public AnalyticsFilterRangeItem $range;

    public ?AnalyticsOverviewSliceItem $overview = null;

    public ?AnalyticsEquityCurveSliceItem $equity_curve = null;

    public ?AnalyticsDrawdownsSliceItem $drawdowns = null;

    public ?AnalyticsDailySliceItem $daily = null;

    public ?AnalyticsBehaviorSliceItem $behavior = null;

    public ?AnalyticsPnlDistributionSliceItem $pnl_distribution = null;

    public ?AnalyticsSessionsSliceItem $sessions = null;

    public ?AnalyticsProfitabilitySliceItem $profitability = null;

    public ?AnalyticsSymbolsSliceItem $symbols = null;

    public ?AnalyticsTimeHeatmapSliceItem $time_heatmap = null;

    public ?AnalyticsDurationScatterSliceItem $duration_scatter = null;

    public ?AnalyticsPhasesSliceItem $phases = null;
}
