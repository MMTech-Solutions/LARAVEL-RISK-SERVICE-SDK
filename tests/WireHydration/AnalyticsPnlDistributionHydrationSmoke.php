<?php

declare(strict_types=1);

require __DIR__.'/../../../../../vendor/autoload.php';

use MmtRiskSdk\Domains\Accounts\ObjectResponses\Analytics\AnalyticsPnlDistributionSliceItem;
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
    'bucket_base' => 25.0,
    'buckets' => [[
        'label' => '0 to 25',
        'from_value' => 0.0,
        'to_value' => 25.0,
        'count' => 4,
    ]],
];

$obj = $h->hydrate($data, AnalyticsPnlDistributionSliceItem::class);

echo 'ok generated_at='.$obj->generated_at
    .' bucket_base='.$obj->bucket_base
    .' buckets='.count($obj->buckets)
    .' label='.$obj->buckets[0]->label
    .PHP_EOL;
