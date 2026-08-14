<?php

declare(strict_types=1);

require __DIR__.'/../../../../../vendor/autoload.php';

use MmtRiskSdk\Domains\Accounts\ObjectResponses\Analytics\AnalyticsOverviewSliceItem;
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
    'kpis' => [
        'trades' => 12,
        'wins' => 7,
        'losses' => 5,
        'breakeven' => 0,
        'win_rate' => 0.58,
        'profit_factor' => 1.4,
        'expectancy' => 12.5,
        'payoff_ratio' => 1.2,
        'avg_win' => 40.0,
        'avg_loss' => -20.0,
        'best_trade' => 100.0,
        'worst_trade' => -50.0,
        'avg_duration_sec' => 90.0,
        'volume_sum' => 3.0,
    ],
    'costs' => [
        'gross_pnl' => 80,
        'gross_profit' => 280,
        'gross_loss' => -200,
        'commission_sum' => 4,
        'swap_sum' => 1,
        'net_pnl' => 75,
        'cost_drag_pct' => 0.06,
    ],
    'equity' => ['equity_first' => 10000, 'equity_last' => 10075, 'return_pct' => 0.75],
    'risk' => [
        'max_drawdown_abs' => 120,
        'max_drawdown_pct' => 1.2,
        'ulcer_index' => 0.4,
        'recovery_factor' => 0.6,
        'underwater_now_pct' => 0,
    ],
    'days' => [
        'days_traded' => 10,
        'days_positive' => 6,
        'days_negative' => 4,
        'max_consecutive_positive' => 3,
        'max_consecutive_negative' => 2,
        'current_consecutive_positive' => 1,
        'consistency_largest_day_pct' => 22.5,
    ],
    'streaks' => [
        'current_win_streak' => 2,
        'current_loss_streak' => 0,
        'max_win_streak' => 4,
        'max_loss_streak' => 3,
    ],
    'live' => [
        'equity' => 10075,
        'balance' => 10075,
        'margin_used' => 0,
        'margin_free' => 10075,
        'margin_level_pct' => 0,
        'floating_pnl' => 0,
        'open_positions' => 0,
        'snapshot_at' => null,
    ],
    'health' => ['score' => 100, 'level' => 'good'],
];

$obj = $h->hydrate($data, AnalyticsOverviewSliceItem::class);

echo 'ok generated_at='.$obj->generated_at
    .' trades='.$obj->kpis->trades
    .' net_pnl='.$obj->costs->net_pnl
    .' health='.$obj->health->level
    .PHP_EOL;
