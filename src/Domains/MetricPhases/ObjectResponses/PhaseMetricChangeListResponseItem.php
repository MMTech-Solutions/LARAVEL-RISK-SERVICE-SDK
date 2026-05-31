<?php

declare(strict_types=1);

namespace MmtRiskSdk\Domains\MetricPhases\ObjectResponses;

use MmtRiskSdk\WireHydration\Attributes\WireMapped;

#[WireMapped]
final class PhaseMetricChangeListResponseItem
{
    public string $phase_id;

    public string $phase_name;

    /** @var PhaseMetricChangeLogItem[] */
    public array $items = [];
}
