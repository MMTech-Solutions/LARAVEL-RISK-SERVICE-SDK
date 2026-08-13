<?php

declare(strict_types=1);

namespace MmtRiskSdk\Domains\Accounts\ObjectResponses\Analytics;

use MmtRiskSdk\WireHydration\Attributes\WireMapped;

#[WireMapped]
final class AnalyticsDurationScatterPointItem
{
    public float $duration_sec;

    public float $pnl;

    public string $symbol;
}
