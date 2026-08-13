<?php

declare(strict_types=1);

namespace MmtRiskSdk\Domains\Accounts\ObjectResponses\Analytics;

use MmtRiskSdk\WireHydration\Attributes\WireMapped;

#[WireMapped]
final class AnalyticsValueBucketItem
{
    public string $label;

    public float $from_value;

    public float $to_value;

    public int $count;
}
