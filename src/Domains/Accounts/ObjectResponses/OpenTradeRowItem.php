<?php

declare(strict_types=1);

namespace MmtRiskSdk\Domains\Accounts\ObjectResponses;

use MmtRiskSdk\WireHydration\Attributes\WireMapped;

#[WireMapped]
final class OpenTradeRowItem
{
    public string $external_trade_id;

    public string $symbol = '';

    public string $side = '';

    public float $volume = 0.0;

    public string $opened_at;
}
