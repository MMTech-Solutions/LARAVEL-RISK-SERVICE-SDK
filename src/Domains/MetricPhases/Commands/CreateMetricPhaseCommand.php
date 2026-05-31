<?php

declare(strict_types=1);

namespace MmtRiskSdk\Domains\MetricPhases\Commands;

use MmtRiskSdk\Contracts\CommandInterface;

final class CreateMetricPhaseCommand implements CommandInterface
{
    public function __construct(
        public string $name,
    ) {}

    public function toArray(): array
    {
        return [
            'name' => $this->name,
        ];
    }
}
