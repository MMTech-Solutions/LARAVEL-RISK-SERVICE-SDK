<?php

declare(strict_types=1);

namespace MmtRiskSdk\Domains\Accounts\ObjectResponses;

use MmtRiskSdk\WireHydration\Attributes\WireMapped;

#[WireMapped]
final class MetricHistoryPointItem
{
    public string $at;

    public ?float $value = null;

    public ?float $delta_from_prev = null;
}
