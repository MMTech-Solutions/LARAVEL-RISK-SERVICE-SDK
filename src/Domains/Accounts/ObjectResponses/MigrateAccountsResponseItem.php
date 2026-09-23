<?php

declare(strict_types=1);

namespace MmtRiskSdk\Domains\Accounts\ObjectResponses;

use MmtRiskSdk\WireHydration\Attributes\WireMapped;

#[WireMapped]
final class MigrateAccountsResponseItem
{
    /** @var list<MigrateAccountCreatedResponseItem> */
    public array $created = [];

    /** @var list<MigrateAccountFailedResponseItem> */
    public array $failed = [];
}
