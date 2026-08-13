<?php

declare(strict_types=1);

namespace MmtRiskSdk\Domains\Accounts\ObjectResponses\Analytics;

use MmtRiskSdk\WireHydration\Attributes\WireMapped;

#[WireMapped]
final class AnalyticsDrawdownsSliceItem
{
    public string $generated_at;

    public AnalyticsFilterRangeItem $range;

    public int $episodes_total;

    public float $avg_duration_days;

    public float $max_duration_days;

    public float $current_underwater_days;

    public float $underwater_now_pct;

    /** @var AnalyticsHistogramBucketItem[] */
    public array $histogram = [];

    public float $best_trade;

    public float $worst_trade;
}
