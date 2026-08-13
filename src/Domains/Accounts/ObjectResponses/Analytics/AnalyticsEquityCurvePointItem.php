<?php

declare(strict_types=1);

namespace MmtRiskSdk\Domains\Accounts\ObjectResponses\Analytics;

use MmtRiskSdk\WireHydration\Attributes\WireMapped;

#[WireMapped]
final class AnalyticsEquityCurvePointItem
{
    public string $date_utc;

    public float $equity;

    public float $equity_adj;

    public float $peak_adj;

    public float $drawdown_pct;

    public bool $is_live;
}
