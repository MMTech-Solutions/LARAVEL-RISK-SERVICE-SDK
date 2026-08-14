<?php

declare(strict_types=1);

require __DIR__.'/../../../../../vendor/autoload.php';

use MmtRiskSdk\Domains\Accounts\ObjectResponses\Analytics\AnalyticsDrawdownsSliceItem;
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
    'episodes_total' => 6,
    'avg_duration_days' => 2.5,
    'max_duration_days' => 7.0,
    'current_underwater_days' => 1.5,
    'underwater_now_pct' => 0.9,
    'histogram' => [[
        'label' => '0-3d',
        'count' => 4,
    ]],
    'best_trade' => 120.0,
    'worst_trade' => -55.0,
];

/** @var AnalyticsDrawdownsSliceItem $obj */
$obj = $h->hydrate($data, AnalyticsDrawdownsSliceItem::class);

echo 'ok generated_at='.$obj->generated_at
    .' episodes='.$obj->episodes_total
    .' bucket='.$obj->histogram[0]->label
    .' underwater='.$obj->underwater_now_pct
    .' worst='.$obj->worst_trade
    .PHP_EOL;
