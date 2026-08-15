<?php

declare(strict_types=1);

namespace MmtRiskSdk\Domains\Accounts\ObjectResponses\Analytics;

use MmtRiskSdk\WireHydration\Attributes\WireMapped;

#[WireMapped]
final class AnalyticsDailyDayTradesResponseItem
{
    public string $generated_at;

    public string $date_utc;

    public float $pnl;

    public int $trades;

    public int $wins;

    public float $win_rate;

    public float $volume;

    /** @var AnalyticsDailyTradeRowItem[] */
    public array $rows = [];
}
