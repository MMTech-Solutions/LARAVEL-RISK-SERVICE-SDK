<?php

declare(strict_types=1);

namespace MmtRiskSdk\Domains\Accounts\ObjectResponses\Analytics;

use MmtRiskSdk\WireHydration\Attributes\WireMapped;

#[WireMapped]
final class AnalyticsFirstTradeHourItem
{
    public int $hour;

    public int $days;

    public float $day_pnl_avg;

    public float $first_trade_win_rate;
}
