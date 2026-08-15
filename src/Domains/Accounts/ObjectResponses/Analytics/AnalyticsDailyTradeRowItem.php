<?php

declare(strict_types=1);

namespace MmtRiskSdk\Domains\Accounts\ObjectResponses\Analytics;

use MmtRiskSdk\WireHydration\Attributes\WireMapped;

#[WireMapped]
final class AnalyticsDailyTradeRowItem
{
    public string $side;

    public string $symbol;

    public float $volume;

    public ?string $opened_at = null;

    public ?string $closed_at = null;

    public float $price_open;

    public float $price_close;

    public ?float $sl_price = null;

    public ?float $tp_price = null;

    public float $commission;

    public float $swap;

    public float $net_pnl;
}
