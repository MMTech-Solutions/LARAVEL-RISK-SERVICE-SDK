<?php

declare(strict_types=1);

namespace MmtRiskSdk\Domains\Accounts\ObjectResponses;

use MmtRiskSdk\WireHydration\Attributes\WireMapped;

#[WireMapped]
final class MigrateAccountFailedResponseItem
{
    public string $login;

    public string $code;

    public string $reason;
}
