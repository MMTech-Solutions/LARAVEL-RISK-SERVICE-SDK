<?php

declare(strict_types=1);

namespace MmtRiskSdk\Domains\Rules\Contracts;

use MmtRiskSdk\Contracts\CommandInterface;
use MmtRiskSdk\TransportDrivers\Contracts\ActionResultInterface;

interface RulesServiceInterface
{
    public function listRules(?bool $activeOnly = null): ActionResultInterface;

    public function createRule(CommandInterface $command): ActionResultInterface;

    public function listActiveRules(): ActionResultInterface;

    public function getRule(string $ruleId): ActionResultInterface;

    public function updateRule(string $ruleId, CommandInterface $command): ActionResultInterface;

    public function deleteRule(string $ruleId): ActionResultInterface;
}
