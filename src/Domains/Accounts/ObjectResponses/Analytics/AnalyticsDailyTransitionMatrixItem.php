<?php

declare(strict_types=1);

namespace MmtRiskSdk\Domains\Accounts\ObjectResponses\Analytics;

use MmtRiskSdk\WireHydration\Attributes\WireMapped;

#[WireMapped]
final class AnalyticsDailyTransitionMatrixItem
{
    public int $gg;

    public int $gr;

    public int $rg;

    public int $rr;

    public int $total;

    public float $p_pos_after_pos;

    public float $p_neg_after_pos;

    public float $p_pos_after_neg;

    public float $p_neg_after_neg;
}
