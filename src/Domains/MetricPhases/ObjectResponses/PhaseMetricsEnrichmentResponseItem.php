<?php

declare(strict_types=1);

namespace MmtRiskSdk\Domains\MetricPhases\ObjectResponses;

use MmtRiskSdk\Domains\Accounts\ObjectResponses\DailyDeltaItem;
use MmtRiskSdk\WireHydration\Attributes\WireMapped;

#[WireMapped]
final class PhaseMetricsEnrichmentResponseItem
{
    /** @var list<string> */
    public array $dates_utc = [];

    /** @var array<string, array<int, float|null>> */
    public array $series = [];

    /** @var array<string, DailyDeltaItem> */
    public array $daily_deltas = [];

    public string $series_end_date_utc;

    public string $phase_id;

    public string $phase_name;
}
