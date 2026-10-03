<?php

declare(strict_types=1);

namespace MmtRiskSdk\Domains\Accounts\ObjectResponses\Analytics;

use MmtRiskSdk\WireHydration\Attributes\WireMapped;

#[WireMapped]
final class AnalyticsOverviewRiskItem
{
    public float $max_drawdown_absolute;

    public float $max_drawdown_total_pct;

    public float $ulcer_index;

    public float $recovery_factor;

    public float $underwater_now_pct;
}
