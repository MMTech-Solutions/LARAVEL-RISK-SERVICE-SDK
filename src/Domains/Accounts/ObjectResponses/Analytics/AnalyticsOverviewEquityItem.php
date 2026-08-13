<?php

declare(strict_types=1);

namespace MmtRiskSdk\Domains\Accounts\ObjectResponses\Analytics;

use MmtRiskSdk\WireHydration\Attributes\WireMapped;

#[WireMapped]
final class AnalyticsOverviewEquityItem
{
    public float $equity_first;

    public float $equity_last;

    public float $return_pct;
}
