<?php

declare(strict_types=1);

namespace MmtRiskSdk\Domains\Accounts\ObjectResponses\Analytics;

use MmtRiskSdk\WireHydration\Attributes\WireMapped;

#[WireMapped]
final class AnalyticsDailyPostLossItem
{
    public float $avg_trades_after_red;

    public float $avg_trades_overall;

    public int $samples;
}
