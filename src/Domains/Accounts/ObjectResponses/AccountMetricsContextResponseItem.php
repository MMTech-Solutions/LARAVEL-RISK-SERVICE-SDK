<?php

declare(strict_types=1);

namespace MmtRiskSdk\Domains\Accounts\ObjectResponses;

use MmtRiskSdk\WireHydration\Attributes\WireMapped;

#[WireMapped]
final class AccountMetricsContextResponseItem
{
    /** @var array<string, mixed> */
    public array $metrics = [];

    public ?string $date_utc = null;

    /** @var array<string, string>|null */
    public ?array $metric_updated_at = null;

    public string $account_created_at;
}
