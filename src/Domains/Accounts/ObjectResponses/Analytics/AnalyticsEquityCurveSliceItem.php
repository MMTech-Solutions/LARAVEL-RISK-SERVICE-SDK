<?php

declare(strict_types=1);

namespace MmtRiskSdk\Domains\Accounts\ObjectResponses\Analytics;

use MmtRiskSdk\WireHydration\Attributes\WireMapped;

#[WireMapped]
final class AnalyticsEquityCurveSliceItem
{
    public string $generated_at;

    public AnalyticsFilterRangeItem $range;

    /** @var AnalyticsEquityCurvePointItem[] */
    public array $points = [];

    /** @var AnalyticsTradeMarkerItem[] */
    public array $trade_markers = [];

    public AnalyticsEquityCurveSparksItem $sparks;

    public ?int $best_trade_marker_index = null;

    public ?int $worst_trade_marker_index = null;

    public ?int $max_drawdown_point_index = null;
}
