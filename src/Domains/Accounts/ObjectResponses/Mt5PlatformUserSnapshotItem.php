<?php

declare(strict_types=1);

namespace MmtRiskSdk\Domains\Accounts\ObjectResponses;

use MmtRiskSdk\WireHydration\Attributes\WireMapped;

#[WireMapped]
final class Mt5PlatformUserSnapshotItem
{
    public string $login = '';

    public string $group = '';

    public string $name = '';

    public string $first_name = '';

    public string $last_name = '';

    public string $company = '';

    public string $language = '';

    public string $country = '';

    public string $city = '';

    public string $state = '';

    public string $zip_code = '';

    public string $address = '';

    public string $phone = '';

    public string $email = '';

    public string $comment = '';

    public int $leverage = 0;
}
