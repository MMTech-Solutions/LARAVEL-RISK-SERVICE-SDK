<?php

declare(strict_types=1);

namespace MmtRiskSdk\Domains\Accounts\ObjectResponses\Analytics;

use MmtRiskSdk\WireHydration\Attributes\WireMapped;

#[WireMapped]
final class AnalyticsProfitabilityRMultipleItem
{
    public float $coverage_pct;

    public int $trades_with_r;

    public float $avg_r;

    public float $expectancy_r;

    /** @var AnalyticsValueBucketItem[] */
    public array $buckets = [];
}
