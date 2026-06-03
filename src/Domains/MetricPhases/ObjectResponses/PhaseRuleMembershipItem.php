<?php

declare(strict_types=1);

namespace MmtRiskSdk\Domains\MetricPhases\ObjectResponses;

use MmtRiskSdk\Domains\Accounts\ObjectResponses\BoundRuleEvaluationPayloadItem;
use MmtRiskSdk\Domains\Accounts\ObjectResponses\RuleMembershipSettingsResponseItem;
use MmtRiskSdk\WireHydration\Attributes\WireMapped;

#[WireMapped]
final class PhaseRuleMembershipItem
{
    public string $rule_id;

    public ?string $rule_name = null;

    public bool $rule_enabled;

    public int $notify_after_matches;

    public ?string $cron_expression = null;

    public string $assigned_at;

    public RuleMembershipSettingsResponseItem $settings;

    public BoundRuleEvaluationPayloadItem $bound_evaluation;

    /** @var array<string, float>|null */
    public ?array $since_assignment_ratios = null;
}
