<?php

declare(strict_types=1);

namespace MmtRiskSdk\Domains\Accounts\ObjectResponses\Analytics;

use MmtRiskSdk\WireHydration\Attributes\WireMapped;

#[WireMapped]
final class AnalyticsSymbolsSliceItem
{
    public string $generated_at;

    public AnalyticsFilterRangeItem $range;

    /** @var AnalyticsSymbolStatsItem[] */
    public array $symbols = [];

    public ?AnalyticsSymbolStatsItem $best_symbol = null;

    public ?AnalyticsSymbolStatsItem $worst_symbol = null;

    public AnalyticsSymbolsConcentrationItem $concentration;
}
