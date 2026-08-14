<?php

declare(strict_types=1);

require __DIR__.'/../../../../../vendor/autoload.php';

use MmtRiskSdk\Domains\Accounts\ObjectResponses\Analytics\AnalyticsTimeHeatmapSliceItem;
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
    'cells' => [[
        'weekday' => 1,
        'hour_block' => 8,
        'trades' => 5,
        'win_rate' => 0.6,
        'net_pnl' => 42.5,
    ]],
];

$obj = $h->hydrate($data, AnalyticsTimeHeatmapSliceItem::class);

echo 'ok generated_at='.$obj->generated_at
    .' cells='.count($obj->cells)
    .' weekday='.$obj->cells[0]->weekday
    .' hour_block='.$obj->cells[0]->hour_block
    .' trades='.$obj->cells[0]->trades
    .PHP_EOL;
