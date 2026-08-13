<?php

declare(strict_types=1);

namespace MmtRiskSdk\Domains\Accounts\ObjectResponses\Analytics;

use MmtRiskSdk\WireHydration\Attributes\WireMapped;

#[WireMapped]
final class AnalyticsOverviewKpisItem
{
    public int $trades;

    public int $wins;

    public int $losses;

    public int $breakeven;

    public float $win_rate;

    public float $profit_factor;

    public float $expectancy;

    public float $payoff_ratio;

    public float $avg_win;

    public float $avg_loss;

    public float $best_trade;

    public float $worst_trade;

    public float $avg_duration_sec;

    public float $volume_sum;
}
