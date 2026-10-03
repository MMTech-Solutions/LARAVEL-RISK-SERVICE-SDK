<?php

declare(strict_types=1);

namespace MmtRiskSdk\Domains\Accounts\ObjectResponses;

use MmtRiskSdk\WireHydration\Attributes\WireMapped;

#[WireMapped]
final class MigrateAccountsResponseItem
{
    /** @var MigrateAccountCreatedResponseItem[] */
    public array $created = [];

    /** @var MigrateAccountFailedResponseItem[] */
    public array $failed = [];
}
