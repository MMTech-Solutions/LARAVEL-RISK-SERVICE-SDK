<?php

declare(strict_types=1);

require __DIR__.'/../../../../../vendor/autoload.php';

use MmtRiskSdk\Domains\Accounts\ObjectResponses\Analytics\AnalyticsDurationScatterSliceItem;
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
        'duration_sec' => 3600.0,
        'pnl' => 25.5,
        'symbol' => 'EURUSD',
    ]],
    'avg_duration_sec' => 5400.0,
];

/** @var AnalyticsDurationScatterSliceItem $obj */
$obj = $h->hydrate($data, AnalyticsDurationScatterSliceItem::class);

echo 'ok generated_at='.$obj->generated_at
    .' points='.count($obj->points)
    .' duration='.$obj->points[0]->duration_sec
    .' pnl='.$obj->points[0]->pnl
    .' avg='.$obj->avg_duration_sec
    .PHP_EOL;
