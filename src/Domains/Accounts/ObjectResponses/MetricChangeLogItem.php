<?php

declare(strict_types=1);

namespace MmtRiskSdk\Domains\Accounts\ObjectResponses;

use MmtRiskSdk\WireHydration\Attributes\WireMapped;

#[WireMapped]
final class MetricChangeLogItem
{
    public string $id;

    public ?string $source_event_id = null;

    /**
     * @var MetricChangeEntryItem[]
     */
    public array $changes = [];

    public string $created_at;
}
