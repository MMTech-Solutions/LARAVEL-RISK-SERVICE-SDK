<?php

declare(strict_types=1);

require __DIR__.'/../../../../../vendor/autoload.php';

use MmtRiskSdk\Domains\Accounts\ObjectResponses\Analytics\AnalyticsProfitabilitySliceItem;
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
    'edge' => [
        'win_rate' => 0.58,
        'payoff_ratio' => 1.2,
        'breakeven_win_rate' => 0.45,
        'edge_pct' => 0.13,
        'kelly_pct' => 0.08,
    ],
    'expectancy' => [
        'win_rate' => 0.58,
        'loss_rate' => 0.42,
        'avg_win' => 40.0,
        'avg_loss' => -20.0,
        'win_contribution' => 23.2,
        'loss_contribution' => -8.4,
        'expectancy' => 14.8,
    ],
    'r_multiple' => [
        'coverage_pct' => 90.0,
        'trades_with_r' => 10,
        'avg_r' => 0.8,
        'expectancy_r' => 0.4,
        'buckets' => [[
            'label' => '0 to 1R',
            'from_value' => 0.0,
            'to_value' => 1.0,
            'count' => 4,
        ]],
    ],
    'sides' => [[
        'side' => 'buy',
        'trades' => 7,
        'win_rate' => 0.6,
        'profit_factor' => 1.5,
        'expectancy' => 12.0,
        'net_pnl' => 80.0,
    ]],
    'concentration' => [
        'total_winners' => 6,
        'best_trade' => 100.0,
        'gross_profit' => 280.0,
        'profit_factor' => 1.4,
        'profit_factor_ex_best' => 1.1,
        'pnl_ex_best' => 20.0,
        'top5_pct_of_gross' => 70.0,
        'winners_for_half' => 2,
    ],
    'net_pnl' => 75.0,
    'volume_sum' => 3.0,
    'pnl_per_lot' => 25.0,
];

$obj = $h->hydrate($data, AnalyticsProfitabilitySliceItem::class);

echo 'ok generated_at='.$obj->generated_at
    .' net_pnl='.$obj->net_pnl
    .' sides='.count($obj->sides)
    .' buckets='.count($obj->r_multiple->buckets)
    .' edge='.$obj->edge->edge_pct
    .PHP_EOL;
