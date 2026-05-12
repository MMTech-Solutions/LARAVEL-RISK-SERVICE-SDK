<?php

declare(strict_types=1);

namespace MmtRiskSdk\Domains\Accounts\ObjectResponses;

use MmtRiskSdk\WireHydration\Attributes\WireMapped;

#[WireMapped]
final class AccountBrokerScopeTotalsItem
{
    public int $total;

    public int $active;

    public int $blocked;
}
