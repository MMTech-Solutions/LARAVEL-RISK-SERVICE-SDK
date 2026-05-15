<?php

declare(strict_types=1);

namespace MmtRiskSdk\Domains\Accounts\ObjectResponses;

use MmtRiskSdk\WireHydration\Attributes\WireMapped;

/**
 * Success envelope payload (`data`) for DELETE /accounts/{account_id}/rules.
 *
 * OpenAPI schema: AccountRulesClearedResponse.
 */
#[WireMapped]
final class AccountRulesClearedResponseItem
{
    public int $removed;
}
