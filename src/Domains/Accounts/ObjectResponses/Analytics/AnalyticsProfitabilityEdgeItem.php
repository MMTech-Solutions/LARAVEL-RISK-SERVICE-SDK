<?php

declare(strict_types=1);

namespace MmtRiskSdk\Domains\Accounts\ObjectResponses\Analytics;

use MmtRiskSdk\WireHydration\Attributes\WireMapped;

#[WireMapped]
final class AnalyticsProfitabilityEdgeItem
{
    public float $win_rate;

    public float $payoff_ratio;

    public float $breakeven_win_rate;

    public float $edge_pct;

    public float $kelly_pct;
}
