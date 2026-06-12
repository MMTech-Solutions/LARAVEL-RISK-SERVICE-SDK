<?php

declare(strict_types=1);

namespace MmtRiskSdk\Domains\Accounts\ObjectResponses;

use MmtRiskSdk\WireHydration\Attributes\WireMapped;

/**
 * Created metric phase summary from POST /accounts/provision (OpenAPI: ProvisionMetricPhaseIdResponse).
 */
#[WireMapped]
final class ProvisionMetricPhaseIdResponseItem
{
    public string $id;

    /**
     * @var list<string>
     */
    public array $rules = [];
}
