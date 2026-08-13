<?php

declare(strict_types=1);

namespace MmtRiskSdk\Domains\Accounts\ObjectResponses\Analytics;

use MmtRiskSdk\WireHydration\Attributes\WireMapped;

#[WireMapped]
final class AnalyticsSymbolSideStatsItem
{
    public string $side;

    public int $trades;

    public float $win_rate;

    public float $net_pnl;
}
