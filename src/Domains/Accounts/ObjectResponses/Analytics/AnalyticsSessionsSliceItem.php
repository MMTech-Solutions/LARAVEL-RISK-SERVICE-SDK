<?php

declare(strict_types=1);

namespace MmtRiskSdk\Domains\Accounts\ObjectResponses\Analytics;

use MmtRiskSdk\WireHydration\Attributes\WireMapped;

#[WireMapped]
final class AnalyticsSessionsSliceItem
{
    public string $generated_at;

    public AnalyticsFilterRangeItem $range;

    /** @var AnalyticsSessionStatsItem[] */
    public array $sessions = [];

    /** @var AnalyticsSessionWindowItem[] */
    public array $session_windows = [];
}
