<?php

declare(strict_types=1);

namespace MmtRiskSdk\Domains\Accounts\ObjectResponses;

use MmtRiskSdk\WireHydration\Attributes\WireMapped;

#[WireMapped]
final class MetricTradeTimelineResponseItem
{
    public string $metric_key;

    public string $time_kind;

    public int $total_in_range;

    public int $offset;

    public int $limit;

    /**
     * @var MetricTradeTimelineRowItem[]
     */
    public array $rows = [];
}
