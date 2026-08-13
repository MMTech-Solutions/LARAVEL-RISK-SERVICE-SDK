<?php

declare(strict_types=1);

require __DIR__.'/../../../../../vendor/autoload.php';

use MmtRiskSdk\Domains\Accounts\ObjectResponses\Analytics\AnalyticsDashboardResponseItem;
use MmtRiskSdk\WireHydration\WireHydrator;

$h = new WireHydrator;
$data = [
    'generated_at' => '2026-08-12T00:00:00Z',
    'range' => [
        'from_utc' => 'a',
        'to_utc' => 'b',
        'symbol' => null,
        'side' => null,
        'session' => null,
        'granularity' => 'day',
    ],
    'overview' => [
        'generated_at' => 't',
        'range' => ['from_utc' => 'a', 'to_utc' => 'b'],
        'kpis' => [
            'trades' => 1,
            'wins' => 1,
            'losses' => 0,
            'breakeven' => 0,
            'win_rate' => 1.0,
            'profit_factor' => 2.0,
            'expectancy' => 1.0,
            'payoff_ratio' => 1.0,
            'avg_win' => 1.0,
            'avg_loss' => 0.0,
            'best_trade' => 1.0,
            'worst_trade' => 0.0,
            'avg_duration_sec' => 10.0,
            'volume_sum' => 1.0,
        ],
        'costs' => [
            'gross_pnl' => 1,
            'gross_profit' => 1,
            'gross_loss' => 0,
            'commission_sum' => 0,
            'swap_sum' => 0,
            'net_pnl' => 1,
            'cost_drag_pct' => 0,
        ],
        'equity' => ['equity_first' => 100, 'equity_last' => 101, 'return_pct' => 1],
        'risk' => [
            'max_drawdown_abs' => 0,
            'max_drawdown_pct' => 0,
            'ulcer_index' => 0,
            'recovery_factor' => 0,
            'underwater_now_pct' => 0,
        ],
        'days' => [
            'days_traded' => 1,
            'days_positive' => 1,
            'days_negative' => 0,
            'max_consecutive_positive' => 1,
            'max_consecutive_negative' => 0,
            'current_consecutive_positive' => 1,
            'consistency_largest_day_pct' => 100,
        ],
        'streaks' => [
            'current_win_streak' => 1,
            'current_loss_streak' => 0,
            'max_win_streak' => 1,
            'max_loss_streak' => 0,
        ],
        'live' => [
            'equity' => 101,
            'balance' => 101,
            'margin_used' => 0,
            'margin_free' => 101,
            'margin_level_pct' => 0,
            'floating_pnl' => 0,
            'open_positions' => 0,
            'snapshot_at' => null,
        ],
        'health' => ['score' => 100, 'level' => 'good'],
    ],
    'equity_curve' => [
        'generated_at' => 't',
        'range' => ['from_utc' => 'a', 'to_utc' => 'b'],
        'points' => [[
            'date_utc' => '2026-01-01',
            'equity' => 100,
            'equity_adj' => 100,
            'peak_adj' => 100,
            'drawdown_pct' => 0,
            'is_live' => false,
        ]],
        'trade_markers' => [],
        'sparks' => [
            'win_rate' => [1.0, null],
            'profit_factor' => [2.0, null],
            'expectancy' => [1.0, null],
            'return_pct' => [1.0, null],
        ],
        'best_trade_marker_index' => null,
        'worst_trade_marker_index' => null,
        'max_drawdown_point_index' => 0,
    ],
    'phases' => [
        'generated_at' => 't',
        'phases' => [[
            'id' => 'p1',
            'name' => 'Phase 1',
            'is_active' => true,
            'activated_at' => 't',
            'deactivated_at' => null,
        ]],
    ],
];

$obj = $h->hydrate($data, AnalyticsDashboardResponseItem::class);

echo 'ok generated_at='.$obj->generated_at
    .' overview_trades='.$obj->overview->kpis->trades
    .' points='.count($obj->equity_curve->points)
    .' phases='.count($obj->phases->phases)
    .' spark0='.var_export($obj->equity_curve->sparks->win_rate[0], true)
    .PHP_EOL;
