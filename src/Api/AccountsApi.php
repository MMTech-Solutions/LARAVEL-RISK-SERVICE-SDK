<?php

declare(strict_types=1);

namespace MmtRiskSdk\Api;

use MmtRiskSdk\RiskRestClient;
use MmtRiskSdk\Support\QueryHelper;
use MmtRiskSdk\Support\UriHelper;

/**
 * /accounts* endpoints (accounts, metrics, trades, rule memberships).
 */
final class AccountsApi
{
    public function __construct(
        private readonly RiskRestClient $client,
    ) {
    }

    public function listAccounts(?string $brokerId = null): mixed
    {
        $q = QueryHelper::omitNull(['broker_id' => $brokerId]);

        return $this->client->envelopeRequest('GET', '/accounts', $q !== [] ? $q : null);
    }

    /**
     * @param  array<string, mixed>  $body
     */
    public function createAccount(array $body): mixed
    {
        return $this->client->envelopeRequest('POST', '/accounts', null, $body);
    }

    public function getAccountByLogin(string $login): mixed
    {
        $seg = UriHelper::pathSegment($login);

        return $this->client->envelopeRequest('GET', "/accounts/by-login/{$seg}");
    }

    /**
     * @param  array<string, mixed>  $body
     */
    public function evaluationHistoryRange(array $body): mixed
    {
        return $this->client->envelopeRequest('POST', '/accounts/evaluation-history/range', null, $body);
    }

    /**
     * @param  array<string, mixed>  $body
     */
    public function evaluationHistoryRecent(array $body): mixed
    {
        return $this->client->envelopeRequest('POST', '/accounts/evaluation-history/recent', null, $body);
    }

    public function listAccountsPage(
        ?string $brokerId = null,
        ?string $q = null,
        ?bool $isBlocked = null,
        ?string $sort = null,
        ?int $skip = null,
        ?int $take = null,
    ): mixed {
        $query = QueryHelper::omitNull([
            'broker_id' => $brokerId,
            'q' => $q,
            'is_blocked' => $isBlocked,
            'sort' => $sort,
            'skip' => $skip,
            'take' => $take,
        ]);

        return $this->client->envelopeRequest('GET', '/accounts/page', $query !== [] ? $query : null);
    }

    public function accountStats(?string $brokerId = null): mixed
    {
        $q = QueryHelper::omitNull(['broker_id' => $brokerId]);

        return $this->client->envelopeRequest('GET', '/accounts/stats', $q !== [] ? $q : null);
    }

    public function getAccountById(string $accountId): mixed
    {
        $id = UriHelper::pathSegment($accountId);

        return $this->client->envelopeRequest('GET', "/accounts/{$id}");
    }

    /**
     * @param  array<string, mixed>  $body
     */
    public function updateAccount(string $accountId, array $body): mixed
    {
        $id = UriHelper::pathSegment($accountId);

        return $this->client->envelopeRequest('PATCH', "/accounts/{$id}", null, $body);
    }

    public function deleteAccount(string $accountId): mixed
    {
        $id = UriHelper::pathSegment($accountId);

        return $this->client->envelopeRequest('DELETE', "/accounts/{$id}");
    }

    public function listAccountMetricChanges(string $accountId, ?int $limit = null): mixed
    {
        $id = UriHelper::pathSegment($accountId);
        $q = QueryHelper::omitNull(['limit' => $limit]);

        return $this->client->envelopeRequest(
            'GET',
            "/accounts/{$id}/metric-changes",
            $q !== [] ? $q : null,
        );
    }

    public function getAccountMetricsContext(string $accountId): mixed
    {
        $id = UriHelper::pathSegment($accountId);

        return $this->client->envelopeRequest('GET', "/accounts/{$id}/metrics-context");
    }

    public function getAccountMetricsEnrichment(string $accountId, ?int $days = null): mixed
    {
        $id = UriHelper::pathSegment($accountId);
        $q = QueryHelper::omitNull(['days' => $days]);

        return $this->client->envelopeRequest(
            'GET',
            "/accounts/{$id}/metrics-enrichment",
            $q !== [] ? $q : null,
        );
    }

    public function getAccountMetricHistory(
        string $accountId,
        string $metricKey,
        string $fromUtc,
        string $toUtc,
        ?string $granularity = null,
        ?bool $onlyNonzeroDelta = null,
        ?float $minAbsDelta = null,
        ?string $sort = null,
        ?int $offset = null,
        ?int $limit = null,
    ): mixed {
        $id = UriHelper::pathSegment($accountId);
        $query = QueryHelper::omitNull([
            'metric_key' => $metricKey,
            'from_utc' => $fromUtc,
            'to_utc' => $toUtc,
            'granularity' => $granularity,
            'only_nonzero_delta' => $onlyNonzeroDelta,
            'min_abs_delta' => $minAbsDelta,
            'sort' => $sort,
            'offset' => $offset,
            'limit' => $limit,
        ]);

        return $this->client->envelopeRequest('GET', "/accounts/{$id}/metrics/history", $query);
    }

    public function getAccountMetricTradeTimeline(
        string $accountId,
        string $metricKey,
        string $fromUtc,
        string $toUtc,
        ?int $offset = null,
        ?int $limit = null,
    ): mixed {
        $id = UriHelper::pathSegment($accountId);
        $query = QueryHelper::omitNull([
            'metric_key' => $metricKey,
            'from_utc' => $fromUtc,
            'to_utc' => $toUtc,
            'offset' => $offset,
            'limit' => $limit,
        ]);

        return $this->client->envelopeRequest('GET', "/accounts/{$id}/metrics/trade-timeline", $query);
    }

    public function listAccountRuleMemberships(string $accountId): mixed
    {
        $id = UriHelper::pathSegment($accountId);

        return $this->client->envelopeRequest('GET', "/accounts/{$id}/rule-memberships");
    }

    public function resetAccountRuleMatchStreak(string $accountId, string $ruleId): mixed
    {
        $aid = UriHelper::pathSegment($accountId);
        $rid = UriHelper::pathSegment($ruleId);

        return $this->client->envelopeRequest(
            'POST',
            "/accounts/{$aid}/rules/{$rid}/match-streak/reset",
        );
    }

    /**
     * @param  array<string, mixed>  $body
     */
    public function patchAccountRuleMembership(string $accountId, string $ruleId, array $body): mixed
    {
        $aid = UriHelper::pathSegment($accountId);
        $rid = UriHelper::pathSegment($ruleId);

        return $this->client->envelopeRequest(
            'PATCH',
            "/accounts/{$aid}/rules/{$rid}/membership",
            null,
            $body,
        );
    }

    public function syncMt5OpenPositions(string $accountId): mixed
    {
        $id = UriHelper::pathSegment($accountId);

        return $this->client->envelopeRequest('POST', "/accounts/{$id}/sync-mt5-open-positions");
    }

    public function listAccountOpenTrades(string $accountId): mixed
    {
        $id = UriHelper::pathSegment($accountId);

        return $this->client->envelopeRequest('GET', "/accounts/{$id}/trades/open");
    }
}
