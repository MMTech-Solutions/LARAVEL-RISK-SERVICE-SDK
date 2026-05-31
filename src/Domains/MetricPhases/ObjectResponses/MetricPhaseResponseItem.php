<?php

declare(strict_types=1);

namespace MmtRiskSdk\Domains\MetricPhases\ObjectResponses;

use MmtRiskSdk\WireHydration\Attributes\WireMapped;

#[WireMapped]
final class MetricPhaseResponseItem
{
    public string $id;

    public string $account_id;

    public string $name;

    public bool $is_active;

    public ?float $opening_equity = null;

    public ?float $opening_balance = null;

    public ?float $opening_credit = null;

    /** @var array<string, mixed>|null */
    public ?array $watermark_metrics = null;

    /** @var array<string, mixed>|null */
    public ?array $phase_metrics = null;

    public string $activated_at;

    public ?string $deactivated_at = null;

    public string $created_at;

    public string $updated_at;
}
