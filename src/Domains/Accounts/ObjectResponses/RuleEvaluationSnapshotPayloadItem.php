<?php

declare(strict_types=1);

namespace MmtRiskSdk\Domains\Accounts\ObjectResponses;

use MmtRiskSdk\WireHydration\Attributes\WireMapped;

#[WireMapped]
final class RuleEvaluationSnapshotPayloadItem
{
    public ?string $rule_name = null;

    /**
     * @var list<mixed>
     */
    public array $conditions = [];

    public int $notify_after_matches = 1;

    public ?string $cron_expression = null;

    public ?int $violation_interval = null;

    /** @var array<string, mixed>|null */
    public ?array $watermark_metrics = null;

    public ?string $assigned_at = null;

    public bool $frozen_at_assignment = false;
}
