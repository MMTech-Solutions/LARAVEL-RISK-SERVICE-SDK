<?php

declare(strict_types=1);

namespace MmtRiskSdk\Domains\Ingress\Contracts;

use MmtRiskSdk\Contracts\CommandInterface;
use MmtRiskSdk\TransportDrivers\Contracts\ActionResultInterface;

interface IngressServiceInterface
{
    public function postEvent(CommandInterface $command): ActionResultInterface;
}
