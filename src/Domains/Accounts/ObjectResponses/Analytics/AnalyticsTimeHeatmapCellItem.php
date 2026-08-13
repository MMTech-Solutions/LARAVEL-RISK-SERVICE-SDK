<?php

declare(strict_types=1);

namespace MmtRiskSdk\Domains\Accounts\ObjectResponses\Analytics;

use MmtRiskSdk\WireHydration\Attributes\WireMapped;

#[WireMapped]
final class AnalyticsTimeHeatmapCellItem
{
    public int $weekday;

    public int $hour_block;

    public int $trades;

    public float $win_rate;

    public float $net_pnl;
}
