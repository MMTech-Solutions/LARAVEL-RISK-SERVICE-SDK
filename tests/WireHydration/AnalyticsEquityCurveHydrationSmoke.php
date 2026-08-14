<?php

declare(strict_types=1);

require __DIR__.'/../../../../../vendor/autoload.php';

use MmtRiskSdk\Domains\Accounts\ObjectResponses\Analytics\AnalyticsEquityCurveSliceItem;
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
    'points' => [[
        'date_utc' => '2026-01-01',
        'equity' => 10000.0,
        'equity_adj' => 10000.0,
        'peak_adj' => 10000.0,
        'drawdown_pct' => 0.0,
        'is_live' => false,
    ]],
    'trade_markers' => [[
        'opened_at_utc' => '2026-01-01T10:00:00Z',
        'closed_at_utc' => '2026-01-01T12:00:00Z',
        'ticket' => '1001',
        'symbol' => 'EURUSD',
        'side' => 'buy',
        'volume' => 0.1,
        'pnl' => 12.5,
        'duration_sec' => 7200.0,
        'r_multiple' => 1.2,
    ]],
    'sparks' => [
        'win_rate' => [0.58, null],
        'profit_factor' => [1.4, null],
        'expectancy' => [12.5, null],
        'return_pct' => [0.75, null],
    ],
    'best_trade_marker_index' => 0,
    'worst_trade_marker_index' => 0,
    'max_drawdown_point_index' => 0,
];

$obj = $h->hydrate($data, AnalyticsEquityCurveSliceItem::class);

echo 'ok generated_at='.$obj->generated_at
    .' points='.count($obj->points)
    .' markers='.count($obj->trade_markers)
    .' ticket='.$obj->trade_markers[0]->ticket
    .' spark0='.var_export($obj->sparks->win_rate[0], true)
    .' max_dd='.var_export($obj->max_drawdown_point_index, true)
    .PHP_EOL;
