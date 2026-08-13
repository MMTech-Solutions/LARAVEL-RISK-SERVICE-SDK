<?php

declare(strict_types=1);

namespace MmtRiskSdk\Domains\Accounts\ObjectResponses\Analytics;

use MmtRiskSdk\WireHydration\Attributes\WireMapped;

#[WireMapped]
final class AnalyticsSymbolsConcentrationItem
{
    public string $top1_symbol;

    public float $top1_pnl_abs_share_pct;

    public float $top3_pnl_abs_share_pct;

    public string $dependency_symbol;

    public float $dependency_pct;
}
