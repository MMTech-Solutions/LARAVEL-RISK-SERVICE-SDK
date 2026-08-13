<?php

declare(strict_types=1);

namespace MmtRiskSdk\Domains\Accounts\ObjectResponses\Analytics;

use MmtRiskSdk\WireHydration\Attributes\WireMapped;

#[WireMapped]
final class AnalyticsPhasesSliceItem
{
    public string $generated_at;

    /** @var AnalyticsPhaseItem[] */
    public array $phases = [];
}
