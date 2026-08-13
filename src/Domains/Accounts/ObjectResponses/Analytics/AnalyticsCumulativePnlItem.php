<?php

declare(strict_types=1);

namespace MmtRiskSdk\Domains\Accounts\ObjectResponses\Analytics;

use MmtRiskSdk\WireHydration\Attributes\WireMapped;

#[WireMapped]
final class AnalyticsCumulativePnlItem
{
    /** @var AnalyticsCumulativePnlPointItem[] */
    public array $day = [];

    /** @var AnalyticsCumulativePnlPointItem[] */
    public array $week = [];

    /** @var AnalyticsCumulativePnlPointItem[] */
    public array $month = [];
}
