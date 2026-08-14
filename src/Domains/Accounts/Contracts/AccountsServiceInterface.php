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

    /**
     * GET /accounts/{account_id}/analytics/dashboard
     *
     * Map success `data` with {@see \MmtRiskSdk\Domains\Accounts\ObjectResponses\Analytics\AnalyticsDashboardResponseItem}.
     */
    public function getAnalyticsDashboard(
        string $accountId,
        ?string $fromUtc = null,
        ?string $toUtc = null,
        ?string $symbol = null,
        ?string $side = null,
        ?string $session = null,
        ?string $phaseId = null,
        ?string $sections = null,
        ?int $markerLimit = null,
        ?int $scatterLimit = null,
        ?int $limit = null,
    ): ActionResultInterface;

    /**
     * GET /accounts/{account_id}/analytics/overview
     *
     * Map success `data` with {@see \MmtRiskSdk\Domains\Accounts\ObjectResponses\Analytics\AnalyticsOverviewSliceItem}.
     */
    public function getAnalyticsOverview(
        string $accountId,
        ?string $fromUtc = null,
        ?string $toUtc = null,
        ?string $symbol = null,
        ?string $side = null,
        ?string $session = null,
        ?string $phaseId = null,
    ): ActionResultInterface;
}
