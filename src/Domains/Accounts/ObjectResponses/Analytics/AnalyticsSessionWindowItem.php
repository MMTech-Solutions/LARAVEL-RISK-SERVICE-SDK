<?php

declare(strict_types=1);

namespace MmtRiskSdk\Domains\Accounts\ObjectResponses\Analytics;

use MmtRiskSdk\WireHydration\Attributes\WireMapped;

#[WireMapped]
final class AnalyticsSessionWindowItem
{
    public string $session;

    public int $start_hour_utc;

    public int $end_hour_utc;
}
