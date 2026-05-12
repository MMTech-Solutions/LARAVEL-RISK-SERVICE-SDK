<?php

declare(strict_types=1);

namespace MmtRiskSdk\Domains\Accounts\ObjectResponses;

use MmtRiskSdk\WireHydration\Attributes\WireMapped;

#[WireMapped]
final class MetricChangeEntryItem
{
    public string $key;

    public mixed $old_value = null;

    public mixed $new_value = null;
}
