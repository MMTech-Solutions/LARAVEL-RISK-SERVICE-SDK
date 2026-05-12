<?php

declare(strict_types=1);

namespace MmtRiskSdk\TransportDrivers\Contracts;

interface TransportInterface
{
    public function send(TransportPacket $packet): ActionResultInterface;
}
