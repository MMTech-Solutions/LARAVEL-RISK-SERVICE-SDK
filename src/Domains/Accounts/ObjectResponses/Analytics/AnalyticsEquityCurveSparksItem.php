<?php

declare(strict_types=1);

namespace MmtRiskSdk\Domains\Accounts\ObjectResponses\Analytics;

use MmtRiskSdk\WireHydration\Attributes\WireMapped;

#[WireMapped]
final class AnalyticsEquityCurveSparksItem
{
    /** @var list<float|null> */
    public array $win_rate = [];

    /** @var list<float|null> */
    public array $profit_factor = [];

    /** @var list<float|null> */
    public array $expectancy = [];

    /** @var list<float|null> */
    public array $return_pct = [];
}
