<?php

declare(strict_types=1);

namespace MmtRiskSdk\Domains\Accounts\ObjectResponses;

use MmtRiskSdk\WireHydration\Attributes\WireMapped;

#[WireMapped]
final class AccountListPageResponseItem
{
    /**
     * @var AccountResponseItem[]
     */
    public array $items = [];

    public int $total_filtered;

    public AccountBrokerScopeTotalsItem $broker_totals;
}
