<?php

declare(strict_types=1);

namespace MmtRiskSdk\Domains\Accounts\ObjectResponses;

use MmtRiskSdk\WireHydration\Attributes\WireMapped;

#[WireMapped]
final class AccountRuleMembershipItem
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
