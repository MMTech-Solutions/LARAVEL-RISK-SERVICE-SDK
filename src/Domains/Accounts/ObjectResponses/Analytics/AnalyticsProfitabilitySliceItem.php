<?php

declare(strict_types=1);

namespace MmtRiskSdk\Domains\Accounts\ObjectResponses\Analytics;

use MmtRiskSdk\WireHydration\Attributes\WireMapped;

#[WireMapped]
final class AnalyticsProfitabilitySliceItem
{
    public string $generated_at;

    public AnalyticsFilterRangeItem $range;

    public AnalyticsProfitabilityEdgeItem $edge;

    public AnalyticsProfitabilityExpectancyItem $expectancy;

    public AnalyticsProfitabilityRMultipleItem $r_multiple;

    /** @var AnalyticsSideStatsItem[] */
    public array $sides = [];

    public AnalyticsProfitabilityConcentrationItem $concentration;

    public float $net_pnl;

    public float $volume_sum;

    public float $pnl_per_lot;
}
