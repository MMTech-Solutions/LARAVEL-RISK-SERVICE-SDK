<?php

declare(strict_types=1);

namespace MmtRiskSdk\Domains\Accounts\ObjectResponses\Analytics;

use MmtRiskSdk\WireHydration\Attributes\WireMapped;

#[WireMapped]
final class AnalyticsDailyDayBehaviorItem
{
    public AnalyticsDailyTransitionMatrixItem $transition_matrix;

    /** @var AnalyticsDailyStreakSegmentItem[] */
    public array $streak_segments = [];

    public float $daily_pnl_std;

    public AnalyticsDailyNearBreakevenItem $near_breakeven;

    public AnalyticsDailyPostLossItem $post_loss;

    public AnalyticsDailyOffDaysItem $off_days;

    public AnalyticsDailyWeekendCarryoverItem $weekend_carryover;

    public float $avg_trades_pos_days;

    public float $avg_trades_neg_days;

    public float $avg_trades_per_day;

    public float $day_win_rate;
}
