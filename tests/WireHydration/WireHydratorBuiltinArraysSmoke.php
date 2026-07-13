<?php

declare(strict_types=1);

/**
 * Smoke verification for WireHydrator builtin vs object list parsing.
 * Run: php tests/WireHydration/WireHydratorBuiltinArraysSmoke.php
 */

require dirname(__DIR__, 2).'/vendor/autoload.php';

use MmtRiskSdk\Domains\Accounts\ObjectResponses\AccountProvisionResponseItem;
use MmtRiskSdk\Domains\MetricPhases\ObjectResponses\PhaseMetricsEnrichmentResponseItem;
use MmtRiskSdk\WireHydration\Attributes\WireMapped;
use MmtRiskSdk\WireHydration\WireHydrator;

#[WireMapped]
final class SmokeScalarListDto
{
    /** @var string[] */
    public array $tags = [];
}

#[WireMapped]
final class SmokeNestedItemDto
{
    public int $x;
}

#[WireMapped]
final class SmokeObjectListDto
{
    /** @var SmokeNestedItemDto[] */
    public array $items = [];
}

$hydrator = new WireHydrator;
$failures = 0;

function assertTrue(bool $cond, string $msg): void
{
    global $failures;
    if (! $cond) {
        fwrite(STDERR, "FAIL: {$msg}\n");
        $failures++;

        return;
    }
    echo "OK: {$msg}\n";
}

// 1) @var string[] + scalar list
$scalar = $hydrator->hydrate(['tags' => ['a', 'b']], SmokeScalarListDto::class);
assertTrue($scalar instanceof SmokeScalarListDto, 'SmokeScalarListDto hydrates');
assertTrue($scalar->tags === ['a', 'b'], '@var string[] keeps scalar list');

// 2) @var SomeDto[] + object-shaped rows
$objects = $hydrator->hydrate(
    ['items' => [['x' => 1], ['x' => 2]]],
    SmokeObjectListDto::class
);
assertTrue($objects instanceof SmokeObjectListDto, 'SmokeObjectListDto hydrates');
assertTrue(
    count($objects->items) === 2
        && $objects->items[0] instanceof SmokeNestedItemDto
        && $objects->items[0]->x === 1
        && $objects->items[1]->x === 2,
    '@var SomeDto[] hydrates nested objects'
);

// 3) @var SomeDto[] + scalars must still fail
try {
    $hydrator->hydrate(['items' => ['a']], SmokeObjectListDto::class);
    assertTrue(false, '@var SomeDto[] with scalars should throw');
} catch (InvalidArgumentException $e) {
    assertTrue(
        str_contains($e->getMessage(), 'must be arrays'),
        '@var SomeDto[] with scalars throws expected error'
    );
}

// 4) PhaseMetricsEnrichmentResponseItem (issue #1 payload)
$enrichment = $hydrator->hydrate([
    'dates_utc' => ['2026-01-01', '2026-01-02', '2026-01-03'],
    'series' => ['balance' => [1000.0, 1010.5, 1005.0]],
    'daily_deltas' => [],
    'series_end_date_utc' => '2026-01-03',
    'phase_id' => 'phase-uuid',
    'phase_name' => 'broker',
], PhaseMetricsEnrichmentResponseItem::class);
assertTrue($enrichment instanceof PhaseMetricsEnrichmentResponseItem, 'PhaseMetricsEnrichmentResponseItem hydrates');
assertTrue(
    $enrichment->dates_utc === ['2026-01-01', '2026-01-02', '2026-01-03'],
    'dates_utc remains list<string>'
);

// 5) PropFirm provision path: AccountProvisionResponseItem + nested metric_phases/rules
$provision = $hydrator->hydrate([
    'account_id' => 'acc-1',
    'metric_phases' => [
        [
            'id' => 'phase-1',
            'rules' => ['rule-a', 'rule-b'],
        ],
    ],
], AccountProvisionResponseItem::class);
assertTrue($provision instanceof AccountProvisionResponseItem, 'AccountProvisionResponseItem hydrates');
assertTrue($provision->account_id === 'acc-1', 'account_id mapped');
assertTrue(count($provision->metric_phases) === 1, 'metric_phases object list mapped');
assertTrue($provision->metric_phases[0]->id === 'phase-1', 'metric phase id mapped');
assertTrue($provision->metric_phases[0]->rules === ['rule-a', 'rule-b'], 'metric phase rules stay string list');

if ($failures > 0) {
    fwrite(STDERR, "\n{$failures} assertion(s) failed.\n");
    exit(1);
}

echo "\nAll WireHydrator smoke checks passed.\n";
exit(0);
