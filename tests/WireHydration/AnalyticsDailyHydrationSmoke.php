<?php

declare(strict_types=1);

require __DIR__.'/../../../../../vendor/autoload.php';

use MmtRiskSdk\Domains\Accounts\ObjectResponses\Analytics\AnalyticsDailySliceItem;
use MmtRiskSdk\WireHydration\WireHydrator;

$h = new WireHydrator;
$data = [
    'generated_at' => '2026-08-14T00:00:00Z',
    'range' => [
        'from_utc' => 'a',
        'to_utc' => 'b',
        'symbol' => null,
        'side' => null,
        'session' => null,
        'granularity' => 'day',
    ],
    'days' => [[
        'date_utc' => '2026-01-01',
        'pnl' => 12.5,
        'trades' => 3,
        'wins' => 2,
        'losses' => 1,
        'win_rate' => 0.67,
    ]],
    'stats' => [
        'days_traded' => 1,
        'days_positive' => 1,
        'days_negative' => 0,
        'max_consecutive_positive' => 1,
        'max_consecutive_negative' => 0,
        'current_consecutive_positive' => 1,
        'consistency_largest_day_pct' => 100.0,
    ],
    'day_behavior' => [
        'transition_matrix' => [
            'gg' => 0,
            'gr' => 0,
            'rg' => 0,
            'rr' => 0,
            'total' => 0,
            'p_pos_after_pos' => 0.0,
            'p_neg_after_pos' => 0.0,
            'p_pos_after_neg' => 0.0,
            'p_neg_after_neg' => 0.0,
        ],
        'streak_segments' => [[
            'sign' => '+',
            'length' => 1,
            'start_date' => '2026-01-01',
            'end_date' => '2026-01-01',
        ]],
        'daily_pnl_std' => 0.0,
        'near_breakeven' => ['threshold' => 5.0, 'count' => 0, 'pct' => 0.0],
        'post_loss' => [
            'avg_trades_after_red' => 0.0,
            'avg_trades_overall' => 3.0,
            'samples' => 0,
        ],
        'off_days' => [
            'break_count' => 0,
            'avg_pnl_after_break' => 0.0,
            'avg_pnl_after_trade_day' => 12.5,
        ],
        'weekend_carryover' => [
            'mon_after_red_fri' => 0.0,
            'mon_after_green_fri' => 0.0,
            'fri_red_count' => 0,
            'fri_green_count' => 0,
        ],
        'avg_trades_pos_days' => 3.0,
        'avg_trades_neg_days' => 0.0,
        'avg_trades_per_day' => 3.0,
        'day_win_rate' => 1.0,
    ],
    'cumulative_pnl' => [
        'day' => [['date_utc' => '2026-01-01', 'pnl_cum' => 12.5]],
        'week' => [['date_utc' => '2026-01-01', 'pnl_cum' => 12.5]],
        'month' => [['date_utc' => '2026-01-01', 'pnl_cum' => 12.5]],
    ],
    'month_totals' => [[
        'month' => '2026-01',
        'pnl' => 12.5,
        'trades' => 3,
        'days_traded' => 1,
    ]],
];

$obj = $h->hydrate($data, AnalyticsDailySliceItem::class);

echo 'ok generated_at='.$obj->generated_at
    .' days='.count($obj->days)
    .' stats='.$obj->stats->days_traded
    .' streaks='.count($obj->day_behavior->streak_segments)
    .' cum_day='.count($obj->cumulative_pnl->day)
    .' months='.count($obj->month_totals)
    .PHP_EOL;
