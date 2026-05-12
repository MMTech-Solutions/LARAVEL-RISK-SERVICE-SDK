<?php

declare(strict_types=1);

namespace MmtRiskSdk\Domains\Ingress\Commands;

use MmtRiskSdk\Contracts\CommandInterface;

final class IngressEventCommand implements CommandInterface
{
    /**
     * @param  array<string, mixed>  $event
     */
    public function __construct(
        public array $event,
    ) {}

    public function toArray(): array
    {
        return $this->event;
    }
}
