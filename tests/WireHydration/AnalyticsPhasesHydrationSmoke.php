<?php

declare(strict_types=1);

require __DIR__.'/../../../../../vendor/autoload.php';

use MmtRiskSdk\Domains\Accounts\ObjectResponses\Analytics\AnalyticsPhasesSliceItem;
use MmtRiskSdk\WireHydration\WireHydrator;

$h = new WireHydrator;
$data = [
    'generated_at' => '2026-08-14T00:00:00Z',
    'phases' => [[
        'id' => '11111111-1111-1111-1111-111111111111',
        'name' => 'Phase 1',
        'is_active' => true,
        'activated_at' => '2026-08-01T00:00:00Z',
        'deactivated_at' => null,
    ]],
];

/** @var AnalyticsPhasesSliceItem $obj */
$obj = $h->hydrate($data, AnalyticsPhasesSliceItem::class);

echo 'ok generated_at='.$obj->generated_at
    .' phases='.count($obj->phases)
    .' active='.var_export($obj->phases[0]->is_active, true)
    .' name='.$obj->phases[0]->name
    .PHP_EOL;
