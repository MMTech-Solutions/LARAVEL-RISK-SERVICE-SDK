<?php

declare(strict_types=1);

namespace MmtRiskSdk\Domains\Accounts\ObjectResponses;

use MmtRiskSdk\WireHydration\Attributes\WireMapped;

#[WireMapped]
final class RuleEvaluationHistoryItem
{
    public string $id;

    public string $rule_id;

    public ?string $account_id = null;

    public string $scope_key;

    public string $outcome;

    public mixed $metric_value = null;

    public ?string $event_id = null;

    public string $evaluated_at;

    public ?MatchStreakItem $match_streak = null;

    /**
     * @var list<array<string, mixed>>
     */
    public array $metrics = [];

    public ?RuleEvaluationSnapshotPayloadItem $rule_snapshot = null;

    /** @var array<string, mixed>|null */
    public ?array $payload = null;
}
