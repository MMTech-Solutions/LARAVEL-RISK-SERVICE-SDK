<?php

declare(strict_types=1);

require __DIR__.'/../../../../../vendor/autoload.php';

use MmtRiskSdk\Domains\Accounts\ObjectResponses\Analytics\AnalyticsBehaviorSliceItem;
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
    'trades_total' => 20,
    'max_consecutive_losses' => 3,
    'max_consecutive_wins' => 4,
    'volume_std_lots' => 0.18,
    'revenge_trades' => 2,
    'revenge_trades_pct' => 10.0,
    'avg_gap_sec' => 5400.0,
    'gap_histogram' => [[
        'label' => '0-1h',
        'count' => 8,
    ]],
    'first_trade_hours' => [[
        'hour' => 9,
        'days' => 5,
        'day_pnl_avg' => 12.5,
        'first_trade_win_rate' => 0.6,
    ]],
    'first_trade_predicts_day_pct' => 62.5,
    'win_loss_sequence' => 'WWLLW',
    'recent_trade_win_rate' => 0.7,
    'baseline_trade_win_rate' => 0.55,
    'trade_win_rate_series' => [
        'cumulative' => [0.5, null],
        'rolling_10' => [0.7, null],
    ],
];

/** @var AnalyticsBehaviorSliceItem $obj */
$obj = $h->hydrate($data, AnalyticsBehaviorSliceItem::class);

echo 'ok generated_at='.$obj->generated_at
    .' trades='.$obj->trades_total
    .' gap_bucket='.$obj->gap_histogram[0]->label
    .' first_hour='.$obj->first_trade_hours[0]->hour
    .' recent='.var_export($obj->trade_win_rate_series->rolling_10[0], true)
    .PHP_EOL;
