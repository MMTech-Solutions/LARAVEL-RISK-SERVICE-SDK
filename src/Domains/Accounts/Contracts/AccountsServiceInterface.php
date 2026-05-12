<?php

declare(strict_types=1);

namespace MmtRiskSdk\Domains\Accounts\Contracts;

use MmtRiskSdk\Contracts\CommandInterface;
use MmtRiskSdk\TransportDrivers\Contracts\ActionResultInterface;

interface AccountsServiceInterface
{
    public function listAccounts(?string $brokerId = null): ActionResultInterface;

    public function createAccount(CommandInterface $command): ActionResultInterface;

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

    public function listAccountMetricChanges(string $accountId, ?int $limit = null): ActionResultInterface;

    public function getAccountMetricsContext(string $accountId): ActionResultInterface;

    public function getAccountMetricsEnrichment(string $accountId, ?int $days = null): ActionResultInterface;

    public function getAccountMetricHistory(
        string $accountId,
        string $metricKey,
        string $fromUtc,
        string $toUtc,
        ?string $granularity,
        ?bool $onlyNonzeroDelta,
        ?float $minAbsDelta,
        ?string $sort,
        ?int $offset,
        ?int $limit,
    ): ActionResultInterface;

    public function getAccountMetricTradeTimeline(
        string $accountId,
        string $metricKey,
        string $fromUtc,
        string $toUtc,
        ?int $offset,
        ?int $limit,
    ): ActionResultInterface;

    public function listAccountRuleMemberships(string $accountId): ActionResultInterface;

    public function resetAccountRuleMatchStreak(string $accountId, string $ruleId): ActionResultInterface;

    public function patchAccountRuleMembership(string $accountId, string $ruleId, CommandInterface $command): ActionResultInterface;

    public function syncMt5OpenPositions(string $accountId): ActionResultInterface;

    public function listAccountOpenTrades(string $accountId): ActionResultInterface;
}
