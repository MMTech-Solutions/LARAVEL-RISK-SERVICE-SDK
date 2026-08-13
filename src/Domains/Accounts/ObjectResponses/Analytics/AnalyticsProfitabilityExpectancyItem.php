<?php

declare(strict_types=1);

namespace MmtRiskSdk\Domains\Accounts\ObjectResponses\Analytics;

use MmtRiskSdk\WireHydration\Attributes\WireMapped;

#[WireMapped]
final class AnalyticsProfitabilityExpectancyItem
{
    public float $win_rate;

    public float $loss_rate;

    public float $avg_win;

    public float $avg_loss;

    public float $win_contribution;

    public float $loss_contribution;

    public float $expectancy;
}
