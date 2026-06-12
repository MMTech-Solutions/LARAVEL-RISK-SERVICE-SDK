<?php

declare(strict_types=1);

namespace MmtRiskSdk\Domains\Accounts\ObjectResponses;

use MmtRiskSdk\WireHydration\Attributes\WireMapped;

/**
 * Success envelope payload (`data`) for POST /accounts/provision (OpenAPI: AccountProvisionResponse).
 */
#[WireMapped]
final class AccountProvisionResponseItem
{
    public string $account_id;

    /**
     * @var ProvisionMetricPhaseIdResponseItem[]
     */
    public array $metric_phases = [];
}
