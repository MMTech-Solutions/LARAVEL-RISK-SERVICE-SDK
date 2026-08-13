<?php

declare(strict_types=1);

namespace MmtRiskSdk\Domains\Accounts\ObjectResponses\Analytics;

use MmtRiskSdk\WireHydration\Attributes\WireMapped;

#[WireMapped]
final class AnalyticsSessionStatsItem
{
    public string $session;

    public int $trades;

    public float $win_rate;

    public float $net_pnl;

    public float $pnl_per_trade;
}
