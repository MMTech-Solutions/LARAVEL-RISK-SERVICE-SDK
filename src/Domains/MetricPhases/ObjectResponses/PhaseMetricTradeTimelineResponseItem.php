<?php

declare(strict_types=1);

namespace MmtRiskSdk\Domains\MetricPhases\ObjectResponses;

use MmtRiskSdk\Domains\Accounts\ObjectResponses\MetricTradeTimelineRowItem;
use MmtRiskSdk\WireHydration\Attributes\WireMapped;

#[WireMapped]
final class PhaseMetricTradeTimelineResponseItem
{
    public string $metric_key;

    public string $time_kind;

    public int $total_in_range;

    public int $offset;

    public int $limit;

    /** @var MetricTradeTimelineRowItem[] */
    public array $rows = [];

    public string $phase_id;

    public string $phase_name;
}
