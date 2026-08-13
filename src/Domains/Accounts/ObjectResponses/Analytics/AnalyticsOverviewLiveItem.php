<?php

declare(strict_types=1);

namespace MmtRiskSdk\Domains\Accounts\ObjectResponses\Analytics;

use MmtRiskSdk\WireHydration\Attributes\WireMapped;

#[WireMapped]
final class AnalyticsOverviewLiveItem
{
    public float $equity;

    public float $balance;

    public float $margin_used;

    public float $margin_free;

    public float $margin_level_pct;

    public float $floating_pnl;

    public int $open_positions;

    public ?string $snapshot_at = null;
}
