<?php

declare(strict_types=1);

namespace MmtRiskSdk\Domains\Accounts\ObjectResponses\Analytics;

use MmtRiskSdk\WireHydration\Attributes\WireMapped;

#[WireMapped]
final class AnalyticsTradeWinRateSeriesItem
{
    /** @var list<float|null> */
    public array $cumulative = [];

    /** @var list<float|null> */
    public array $rolling_10 = [];
}
