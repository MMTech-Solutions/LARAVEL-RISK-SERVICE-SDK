<?php

declare(strict_types=1);

require __DIR__.'/../../../../../vendor/autoload.php';

use MmtRiskSdk\Domains\Accounts\ObjectResponses\Analytics\AnalyticsDailyDayTradesResponseItem;
use MmtRiskSdk\WireHydration\WireHydrator;

$h = new WireHydrator;
$data = [
    'generated_at' => '2026-08-15T00:00:00Z',
    'date_utc' => '2026-01-15',
    'pnl' => 42.5,
    'trades' => 2,
    'wins' => 1,
    'win_rate' => 0.5,
    'volume' => 0.2,
    'rows' => [[
        'side' => 'buy',
        'symbol' => 'EURUSD',
        'volume' => 0.1,
        'opened_at' => '2026-01-15T10:00:00Z',
        'closed_at' => '2026-01-15T12:00:00Z',
        'price_open' => 1.1,
        'price_close' => 1.105,
        'sl_price' => 1.09,
        'tp_price' => null,
        'commission' => 0.5,
        'swap' => 0.0,
        'net_pnl' => 42.5,
    ]],
];

$obj = $h->hydrate($data, AnalyticsDailyDayTradesResponseItem::class);

echo 'ok date='.$obj->date_utc
    .' trades='.$obj->trades
    .' rows='.count($obj->rows)
    .' symbol='.$obj->rows[0]->symbol
    .' net_pnl='.$obj->rows[0]->net_pnl
    .PHP_EOL;
