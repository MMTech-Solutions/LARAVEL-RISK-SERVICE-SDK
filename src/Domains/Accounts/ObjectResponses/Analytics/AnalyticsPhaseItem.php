<?php

declare(strict_types=1);

namespace MmtRiskSdk\Domains\Accounts\ObjectResponses\Analytics;

use MmtRiskSdk\WireHydration\Attributes\WireMapped;

#[WireMapped]
final class AnalyticsPhaseItem
{
    public string $id;

    public string $name;

    public bool $is_active;

    public ?string $activated_at = null;

    public ?string $deactivated_at = null;
}
