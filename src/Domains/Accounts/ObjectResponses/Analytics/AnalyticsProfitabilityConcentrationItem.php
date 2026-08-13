<?php

declare(strict_types=1);

namespace MmtRiskSdk\Domains\Accounts\ObjectResponses\Analytics;

use MmtRiskSdk\WireHydration\Attributes\WireMapped;

#[WireMapped]
final class AnalyticsProfitabilityConcentrationItem
{
    public int $total_winners;

    public float $best_trade;

    public float $gross_profit;

    public float $profit_factor;

    public float $profit_factor_ex_best;

    public float $pnl_ex_best;

    public float $top5_pct_of_gross;

    public int $winners_for_half;
}
