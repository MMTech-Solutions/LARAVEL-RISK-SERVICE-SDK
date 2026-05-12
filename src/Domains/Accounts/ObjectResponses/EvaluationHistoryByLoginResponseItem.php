<?php

declare(strict_types=1);

namespace MmtRiskSdk\Domains\Accounts\ObjectResponses;

use MmtRiskSdk\WireHydration\Attributes\WireMapped;

#[WireMapped]
final class EvaluationHistoryByLoginResponseItem
{
    /**
     * Map of login => list of evaluation rows (wire-shaped arrays).
     *
     * @var array<string, list<array<string, mixed>>>
     */
    public array $by_login = [];
}
