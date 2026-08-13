<?php

declare(strict_types=1);

namespace MmtRiskSdk\Domains\Accounts\ObjectResponses\Analytics;

use MmtRiskSdk\WireHydration\Attributes\WireMapped;

#[WireMapped]
final class AnalyticsSymbolStatsItem
{
    public string $symbol;

    public int $trades;

    public float $win_rate;

    public float $profit_factor;

    public float $volume_sum;

    public float $net_pnl;

    public float $expectancy;

    public float $avg_win;

    public float $avg_loss;

    public float $payoff_ratio;

    public float $pnl_per_lot;

    public float $commission_sum;

    public float $swap_sum;

    public float $costs_total;

    public float $cost_per_trade;

    public float $cost_drag_pct;

    public float $gross_pnl;

    public float $pnl_adjusted;

    public float $pnl_share_pct;

    public float $trades_share_pct;

    public float $volume_share_pct;

    /** @var AnalyticsSymbolSideStatsItem[] */
    public array $sides = [];
}
