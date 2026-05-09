<?php

declare(strict_types=1);

namespace MmtRiskSdk\Laravel\Facades;

use Illuminate\Support\Facades\Facade;
use MmtRiskSdk\RiskRestClient;

/**
 * @mixin RiskRestClient
 */
final class MmtRisk extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return RiskRestClient::class;
    }
}
