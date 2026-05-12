<?php

declare(strict_types=1);

namespace MmtRiskSdk\Domains\Accounts\ObjectResponses;

use MmtRiskSdk\WireHydration\Attributes\WireMapped;

#[WireMapped]
final class BoundRuleEvaluationPayloadItem
{
    /**
     * @var list<array<string, mixed>>
     */
    public array $conditions = [];

    public int $notify_after_matches = 1;

    public ?string $cron_expression = null;

    public ?int $violation_interval = null;

    /** @var array<string, mixed>|null */
    public ?array $watermark_metrics = null;

    public bool $frozen_at_assignment = false;
}
