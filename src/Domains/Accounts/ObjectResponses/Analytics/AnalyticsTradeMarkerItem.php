<?php

declare(strict_types=1);

namespace MmtRiskSdk\Domains\Accounts\ObjectResponses\Analytics;

use MmtRiskSdk\WireHydration\Attributes\WireMapped;

#[WireMapped]
final class AnalyticsTradeMarkerItem
{
    public ?string $opened_at_utc = null;

    public ?string $closed_at_utc = null;

    public string $ticket;

    public string $symbol;

    public string $side;

    public float $volume;

    public float $pnl;

    public float $duration_sec;

    public float $r_multiple;
}
