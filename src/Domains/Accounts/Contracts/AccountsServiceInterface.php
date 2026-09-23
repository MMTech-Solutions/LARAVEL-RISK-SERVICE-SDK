<?php

declare(strict_types=1);

namespace MmtRiskSdk\Domains\Accounts\Contracts;

use MmtRiskSdk\Contracts\CommandInterface;
use MmtRiskSdk\Domains\Accounts\ObjectResponses\Analytics\AnalyticsBehaviorSliceItem;
use MmtRiskSdk\Domains\Accounts\ObjectResponses\Analytics\AnalyticsDailyDayTradesResponseItem;
use MmtRiskSdk\Domains\Accounts\ObjectResponses\Analytics\AnalyticsDailySliceItem;
use MmtRiskSdk\Domains\Accounts\ObjectResponses\Analytics\AnalyticsDashboardResponseItem;
use MmtRiskSdk\Domains\Accounts\ObjectResponses\Analytics\AnalyticsDrawdownsSliceItem;
use MmtRiskSdk\Domains\Accounts\ObjectResponses\Analytics\AnalyticsDurationScatterSliceItem;
use MmtRiskSdk\Domains\Accounts\ObjectResponses\Analytics\AnalyticsEquityCurveSliceItem;
use MmtRiskSdk\Domains\Accounts\ObjectResponses\Analytics\AnalyticsOverviewSliceItem;
use MmtRiskSdk\Domains\Accounts\ObjectResponses\Analytics\AnalyticsPhasesSliceItem;
use MmtRiskSdk\Domains\Accounts\ObjectResponses\Analytics\AnalyticsPnlDistributionSliceItem;
use MmtRiskSdk\Domains\Accounts\ObjectResponses\Analytics\AnalyticsProfitabilitySliceItem;
use MmtRiskSdk\Domains\Accounts\ObjectResponses\Analytics\AnalyticsSessionsSliceItem;
use MmtRiskSdk\Domains\Accounts\ObjectResponses\Analytics\AnalyticsSymbolsSliceItem;
use MmtRiskSdk\Domains\Accounts\ObjectResponses\Analytics\AnalyticsTimeHeatmapSliceItem;
use MmtRiskSdk\TransportDrivers\Contracts\ActionResultInterface;

interface AccountsServiceInterface
{
    public function listAccounts(?string $brokerId = null): ActionResultInterface;

    public function createAccount(CommandInterface $command): ActionResultInterface;

    public function provisionAccount(CommandInterface $command): ActionResultInterface;

    public function migrateAccounts(CommandInterface $command): ActionResultInterface;

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
     * Map success `data` with {@see AnalyticsDashboardResponseItem}.
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
     * Map success `data` with {@see AnalyticsOverviewSliceItem}.
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

    /**
     * GET /accounts/{account_id}/analytics/equity-curve
     *
     * Map success `data` with {@see AnalyticsEquityCurveSliceItem}.
     */
    public function getAnalyticsEquityCurve(
        string $accountId,
        ?string $fromUtc = null,
        ?string $toUtc = null,
        ?string $symbol = null,
        ?string $side = null,
        ?string $session = null,
        ?string $phaseId = null,
        ?int $markerLimit = null,
    ): ActionResultInterface;

    /**
     * GET /accounts/{account_id}/analytics/profitability
     *
     * Map success `data` with {@see AnalyticsProfitabilitySliceItem}.
     */
    public function getAnalyticsProfitability(
        string $accountId,
        ?string $fromUtc = null,
        ?string $toUtc = null,
        ?string $symbol = null,
        ?string $side = null,
        ?string $session = null,
        ?string $phaseId = null,
    ): ActionResultInterface;

    /**
     * GET /accounts/{account_id}/analytics/daily
     *
     * Map success `data` with {@see AnalyticsDailySliceItem}.
     */
    public function getAnalyticsDaily(
        string $accountId,
        ?string $fromUtc = null,
        ?string $toUtc = null,
        ?string $symbol = null,
        ?string $side = null,
        ?string $session = null,
        ?string $phaseId = null,
    ): ActionResultInterface;

    /**
     * GET /accounts/{account_id}/analytics/pnl-distribution
     *
     * Map success `data` with {@see AnalyticsPnlDistributionSliceItem}.
     */
    public function getAnalyticsPnlDistribution(
        string $accountId,
        ?string $fromUtc = null,
        ?string $toUtc = null,
        ?string $symbol = null,
        ?string $side = null,
        ?string $session = null,
        ?string $phaseId = null,
    ): ActionResultInterface;

    /**
     * GET /accounts/{account_id}/analytics/sessions
     *
     * Map success `data` with {@see AnalyticsSessionsSliceItem}.
     */
    public function getAnalyticsSessions(
        string $accountId,
        ?string $fromUtc = null,
        ?string $toUtc = null,
        ?string $symbol = null,
        ?string $side = null,
        ?string $session = null,
        ?string $phaseId = null,
    ): ActionResultInterface;

    /**
     * GET /accounts/{account_id}/analytics/symbols
     *
     * Map success `data` with {@see AnalyticsSymbolsSliceItem}.
     */
    public function getAnalyticsSymbols(
        string $accountId,
        ?string $fromUtc = null,
        ?string $toUtc = null,
        ?string $symbol = null,
        ?string $side = null,
        ?string $session = null,
        ?string $phaseId = null,
    ): ActionResultInterface;

    /**
     * GET /accounts/{account_id}/analytics/time-heatmap
     *
     * Map success `data` with {@see AnalyticsTimeHeatmapSliceItem}.
     */
    public function getAnalyticsTimeHeatmap(
        string $accountId,
        ?string $fromUtc = null,
        ?string $toUtc = null,
        ?string $symbol = null,
        ?string $side = null,
        ?string $session = null,
        ?string $phaseId = null,
    ): ActionResultInterface;

    /**
     * GET /accounts/{account_id}/analytics/duration-scatter
     *
     * Map success `data` with {@see AnalyticsDurationScatterSliceItem}.
     */
    public function getAnalyticsDurationScatter(
        string $accountId,
        ?string $fromUtc = null,
        ?string $toUtc = null,
        ?string $symbol = null,
        ?string $side = null,
        ?string $session = null,
        ?string $phaseId = null,
        ?int $limit = null,
    ): ActionResultInterface;

    /**
     * GET /accounts/{account_id}/analytics/behavior
     *
     * Map success `data` with {@see AnalyticsBehaviorSliceItem}.
     */
    public function getAnalyticsBehavior(
        string $accountId,
        ?string $fromUtc = null,
        ?string $toUtc = null,
        ?string $symbol = null,
        ?string $side = null,
        ?string $session = null,
        ?string $phaseId = null,
    ): ActionResultInterface;

    /**
     * GET /accounts/{account_id}/analytics/drawdowns
     *
     * Map success `data` with {@see AnalyticsDrawdownsSliceItem}.
     */
    public function getAnalyticsDrawdowns(
        string $accountId,
        ?string $fromUtc = null,
        ?string $toUtc = null,
        ?string $symbol = null,
        ?string $side = null,
        ?string $session = null,
        ?string $phaseId = null,
    ): ActionResultInterface;

    /**
     * GET /accounts/{account_id}/analytics/phases
     *
     * Map success `data` with {@see AnalyticsPhasesSliceItem}.
     */
    public function getAnalyticsPhases(string $accountId): ActionResultInterface;

    /**
     * GET /accounts/{account_id}/analytics/daily/{date_utc}/trades
     *
     * Map success `data` with {@see AnalyticsDailyDayTradesResponseItem}.
     */
    public function getAnalyticsDailyDayTrades(
        string $accountId,
        string $dateUtc,
        ?string $fromUtc = null,
        ?string $toUtc = null,
        ?string $symbol = null,
        ?string $side = null,
        ?string $session = null,
        ?string $phaseId = null,
    ): ActionResultInterface;
}
