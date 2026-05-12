<?php

declare(strict_types=1);

namespace MmtRiskSdk\Domains\Rules\ObjectResponses;

use MmtRiskSdk\WireHydration\Attributes\WireMapped;

#[WireMapped]
final class RuleResponseItem
{
    public string $id;

    /**
     * @var list<string>
     */
    public array $account_ids = [];

    public ?string $name = null;

    public ?string $description = null;

    public bool $enabled;

    public int $notify_after_matches;

    public ?string $cron_expression = null;

    public ?int $violation_interval = null;

    /**
     * @var list<array<string, mixed>>
     */
    public array $conditions = [];

    /** @var array<string, mixed>|null */
    public ?array $watermark_metrics = null;

    public string $created_at;

    public string $updated_at;
}
