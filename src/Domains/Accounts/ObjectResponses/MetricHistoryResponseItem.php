<?php

declare(strict_types=1);

namespace MmtRiskSdk\Domains\Accounts\ObjectResponses;

use MmtRiskSdk\WireHydration\Attributes\WireMapped;

#[WireMapped]
final class MetricHistoryResponseItem
{
    public string $metric_key;

    public string $granularity;

    /**
     * @var MetricHistoryPointItem[]
     */
    public array $points = [];

    public MetricHistorySummaryItem $summary;
}
