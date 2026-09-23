<?php

declare(strict_types=1);

namespace MmtRiskSdk\Domains\Accounts\ObjectResponses;

use MmtRiskSdk\WireHydration\Attributes\WireMapped;

#[WireMapped]
final class MigrateAccountCreatedResponseItem
{
    public string $login;

    public string $account_id;

    /** @var list<ProvisionMetricPhaseIdResponseItem> */
    public array $metric_phases = [];
}
