<?php

declare(strict_types=1);

namespace MmtRiskSdk\Domains\Accounts\ObjectResponses;

use MmtRiskSdk\WireHydration\Attributes\WireMapped;

#[WireMapped]
final class MetricHistorySummaryItem
{
    public int $rows_in_range;

    public int $rows_returned;

    public int $offset;

    public int $limit;

    public bool $truncated;

    public ?string $first_at = null;

    public ?string $last_at = null;

    public ?float $min_value = null;

    public ?float $max_value = null;

    public ?float $net_change_first_to_last = null;

    public ?float $max_abs_delta = null;
}
