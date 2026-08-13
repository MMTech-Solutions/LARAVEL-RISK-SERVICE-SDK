<?php

declare(strict_types=1);

namespace MmtRiskSdk\Domains\Accounts\ObjectResponses\Analytics;

use MmtRiskSdk\WireHydration\Attributes\WireMapped;

#[WireMapped]
final class AnalyticsDailyOffDaysItem
{
    public int $break_count;

    public float $avg_pnl_after_break;

    public float $avg_pnl_after_trade_day;
}
