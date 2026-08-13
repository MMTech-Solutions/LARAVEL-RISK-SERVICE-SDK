<?php

declare(strict_types=1);

namespace MmtRiskSdk\Domains\Accounts\ObjectResponses\Analytics;

use MmtRiskSdk\WireHydration\Attributes\WireMapped;

#[WireMapped]
final class AnalyticsMonthTotalItem
{
    public string $month;

    public float $pnl;

    public int $trades;

    public int $days_traded;
}
