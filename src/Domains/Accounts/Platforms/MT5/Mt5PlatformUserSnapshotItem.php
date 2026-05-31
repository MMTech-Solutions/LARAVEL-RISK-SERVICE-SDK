<?php

declare(strict_types=1);

namespace MmtRiskSdk\Domains\Accounts\Platforms\MT5;

use MmtRiskSdk\WireHydration\Attributes\WireMapped;

/**
 * Trader profile from Platform API / MT5 (GET /users/{login} via SDK).
 */
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
