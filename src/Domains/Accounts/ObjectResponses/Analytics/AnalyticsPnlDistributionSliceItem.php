<?php

declare(strict_types=1);

namespace MmtRiskSdk\Domains\Accounts\ObjectResponses\Analytics;

use MmtRiskSdk\WireHydration\Attributes\WireMapped;

#[WireMapped]
final class AnalyticsPnlDistributionSliceItem
{
    public string $generated_at;

    public AnalyticsFilterRangeItem $range;

    public float $bucket_base;

    /** @var AnalyticsValueBucketItem[] */
    public array $buckets = [];
}
