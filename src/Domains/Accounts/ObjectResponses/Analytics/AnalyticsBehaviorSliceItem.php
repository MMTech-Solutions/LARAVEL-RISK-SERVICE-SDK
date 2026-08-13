<?php

declare(strict_types=1);

namespace MmtRiskSdk\Domains\Accounts\ObjectResponses\Analytics;

use MmtRiskSdk\WireHydration\Attributes\WireMapped;

#[WireMapped]
final class AnalyticsBehaviorSliceItem
{
    public string $generated_at;

    public AnalyticsFilterRangeItem $range;

    public int $trades_total;

    public int $max_consecutive_losses;

    public int $max_consecutive_wins;

    public float $volume_std_lots;

    public int $revenge_trades;

    public float $revenge_trades_pct;

    public float $avg_gap_sec;

    /** @var AnalyticsHistogramBucketItem[] */
    public array $gap_histogram = [];

    /** @var AnalyticsFirstTradeHourItem[] */
    public array $first_trade_hours = [];

    public float $first_trade_predicts_day_pct;

    public string $win_loss_sequence;

    public float $recent_trade_win_rate;

    public float $baseline_trade_win_rate;

    public AnalyticsTradeWinRateSeriesItem $trade_win_rate_series;
}
