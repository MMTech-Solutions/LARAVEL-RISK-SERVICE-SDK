<?php

declare(strict_types=1);

namespace MmtRiskSdk\Domains\Accounts\ObjectResponses\Analytics;

use MmtRiskSdk\WireHydration\Attributes\WireMapped;

#[WireMapped]
final class AnalyticsFilterRangeItem
{
    public ?string $from_utc = null;

    public ?string $to_utc = null;

    public ?string $symbol = null;

    public ?string $side = null;

    public ?string $session = null;

    public ?string $granularity = null;
}
