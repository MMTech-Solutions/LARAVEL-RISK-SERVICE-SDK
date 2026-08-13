<?php

declare(strict_types=1);

namespace MmtRiskSdk\Domains\Accounts\ObjectResponses\Analytics;

use MmtRiskSdk\WireHydration\Attributes\WireMapped;

#[WireMapped]
final class AnalyticsOverviewCostsItem
{
    public float $gross_pnl;

    public float $gross_profit;

    public float $gross_loss;

    public float $commission_sum;

    public float $swap_sum;

    public float $net_pnl;

    public float $cost_drag_pct;
}
