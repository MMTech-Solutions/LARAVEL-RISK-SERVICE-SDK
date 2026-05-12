<?php

declare(strict_types=1);

namespace MmtRiskSdk\Domains\Accounts\ObjectResponses;

use MmtRiskSdk\WireHydration\Attributes\WireMapped;

#[WireMapped]
final class MetricsEnrichmentResponseItem
{
    /**
     * @var list<string>
     */
    public array $dates_utc = [];

    /** @var array<string, list<float|null>> */
    public array $series = [];

    /** @var array<string, mixed> */
    public array $daily_deltas = [];

    public string $series_end_date_utc;
}
