<?php

declare(strict_types=1);

namespace MmtRiskSdk\Domains\Accounts\Contracts;

use MmtRiskSdk\Contracts\CommandInterface;
use MmtRiskSdk\TransportDrivers\Contracts\ActionResultInterface;

interface AccountsServiceInterface
{
    public function listAccounts(?string $brokerId = null): ActionResultInterface;

    public function createAccount(CommandInterface $command): ActionResultInterface;

    public function provisionAccount(CommandInterface $command): ActionResultInterface;

    public function getAccountByLogin(string $login): ActionResultInterface;

    public function evaluationHistoryRange(CommandInterface $command): ActionResultInterface;

    public function evaluationHistoryRecent(CommandInterface $command): ActionResultInterface;

    public function listAccountsPage(
        ?string $brokerId,
        ?string $q,
        ?bool $isBlocked,
        ?string $sort,
        ?int $skip,
        ?int $take,
    ): ActionResultInterface;

    public function accountStats(?string $brokerId = null): ActionResultInterface;

    public function getAccountById(string $accountId): ActionResultInterface;

    public function updateAccount(string $accountId, CommandInterface $command): ActionResultInterface;

    public function deleteAccount(string $accountId): ActionResultInterface;

    public function listAccountRuleMemberships(string $accountId): ActionResultInterface;

    public function resetAccountRuleMatchStreak(string $accountId, string $ruleId): ActionResultInterface;

    public function patchAccountRuleMembership(string $accountId, string $ruleId, CommandInterface $command): ActionResultInterface;

    public function attachAccountRule(string $accountId, CommandInterface $command): ActionResultInterface;

    public function detachAccountRule(string $accountId, string $ruleId): ActionResultInterface;

    public function detachAllAccountRules(string $accountId): ActionResultInterface;

    /** MT5-only: backfill open positions as trade rows. */
    public function syncMt5OpenPositions(string $accountId): ActionResultInterface;

    public function listAccountOpenTrades(string $accountId): ActionResultInterface;
}
