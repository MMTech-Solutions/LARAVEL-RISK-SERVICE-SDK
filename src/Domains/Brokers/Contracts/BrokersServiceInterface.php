<?php

declare(strict_types=1);

namespace MmtRiskSdk\Domains\Brokers\Contracts;

use MmtRiskSdk\Contracts\CommandInterface;
use MmtRiskSdk\TransportDrivers\Contracts\ActionResultInterface;

interface BrokersServiceInterface
{
    public function listBrokers(): ActionResultInterface;

    public function createBroker(CommandInterface $command): ActionResultInterface;

    public function getBrokerById(string $brokerId): ActionResultInterface;

    public function updateBroker(string $brokerId, CommandInterface $command): ActionResultInterface;

    public function deleteBroker(string $brokerId): ActionResultInterface;
}
