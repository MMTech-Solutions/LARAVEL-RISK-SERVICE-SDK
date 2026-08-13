<?php

declare(strict_types=1);

namespace MmtRiskSdk\Domains\Accounts\ObjectResponses\Analytics;

use MmtRiskSdk\WireHydration\Attributes\WireMapped;

#[WireMapped]
final class AnalyticsTimeHeatmapSliceItem
{
    public string $generated_at;

    public AnalyticsFilterRangeItem $range;

    /** @var AnalyticsTimeHeatmapCellItem[] */
    public array $cells = [];
}
