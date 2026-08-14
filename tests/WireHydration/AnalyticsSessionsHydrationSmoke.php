<?php

declare(strict_types=1);

require __DIR__.'/../../../../../vendor/autoload.php';

use MmtRiskSdk\Domains\Accounts\ObjectResponses\Analytics\AnalyticsSessionsSliceItem;
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
    'sessions' => [[
        'session' => 'london',
        'trades' => 8,
        'win_rate' => 0.5,
        'net_pnl' => 40.0,
        'pnl_per_trade' => 5.0,
    ]],
    'session_windows' => [[
        'session' => 'london',
        'start_hour_utc' => 7,
        'end_hour_utc' => 16,
    ]],
];

$obj = $h->hydrate($data, AnalyticsSessionsSliceItem::class);

echo 'ok generated_at='.$obj->generated_at
    .' sessions='.count($obj->sessions)
    .' windows='.count($obj->session_windows)
    .' session='.$obj->sessions[0]->session
    .' start='.$obj->session_windows[0]->start_hour_utc
    .PHP_EOL;
