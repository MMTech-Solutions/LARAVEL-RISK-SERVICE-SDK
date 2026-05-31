<?php

declare(strict_types=1);

namespace MmtRiskSdk\Domains\MetricPhases\ObjectResponses;

use MmtRiskSdk\Domains\Accounts\ObjectResponses\MetricChangeEntryItem;
use MmtRiskSdk\WireHydration\Attributes\WireMapped;

#[WireMapped]
final class PhaseMetricChangeLogItem
{
    public string $id;

    public ?string $source_event_id = null;

    /** @var MetricChangeEntryItem[] */
    public array $changes = [];

    public string $created_at;

    public string $phase_id;

    public string $phase_name;
}
