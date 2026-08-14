<?php

declare(strict_types=1);

require __DIR__.'/../../../../../vendor/autoload.php';

use MmtRiskSdk\Domains\Accounts\ObjectResponses\Analytics\AnalyticsSymbolsSliceItem;
use MmtRiskSdk\WireHydration\WireHydrator;

$h = new WireHydrator;
$symbolStats = [
    'symbol' => 'EURUSD',
    'trades' => 10,
    'win_rate' => 0.6,
    'profit_factor' => 1.5,
    'volume_sum' => 2.0,
    'net_pnl' => 120.0,
    'expectancy' => 12.0,
    'avg_win' => 40.0,
    'avg_loss' => -20.0,
    'payoff_ratio' => 2.0,
    'pnl_per_lot' => 60.0,
    'commission_sum' => 4.0,
    'swap_sum' => 1.0,
    'costs_total' => 5.0,
    'cost_per_trade' => 0.5,
    'cost_drag_pct' => 0.04,
    'gross_pnl' => 125.0,
    'pnl_adjusted' => 120.0,
    'pnl_share_pct' => 80.0,
    'trades_share_pct' => 70.0,
    'volume_share_pct' => 65.0,
    'sides' => [[
        'side' => 'buy',
        'trades' => 6,
        'win_rate' => 0.67,
        'net_pnl' => 90.0,
    ]],
];

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
    'symbols' => [$symbolStats],
    'best_symbol' => $symbolStats,
    'worst_symbol' => array_merge($symbolStats, [
        'symbol' => 'GBPUSD',
        'net_pnl' => -30.0,
    ]),
    'concentration' => [
        'top1_symbol' => 'EURUSD',
        'top1_pnl_abs_share_pct' => 80.0,
        'top3_pnl_abs_share_pct' => 95.0,
        'dependency_symbol' => 'EURUSD',
        'dependency_pct' => 80.0,
    ],
];

$obj = $h->hydrate($data, AnalyticsSymbolsSliceItem::class);

echo 'ok generated_at='.$obj->generated_at
    .' symbols='.count($obj->symbols)
    .' best='.$obj->best_symbol->symbol
    .' worst='.$obj->worst_symbol->symbol
    .' top1='.$obj->concentration->top1_symbol
    .' sides='.count($obj->symbols[0]->sides)
    .PHP_EOL;
