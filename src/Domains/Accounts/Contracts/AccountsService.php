<?php

declare(strict_types=1);

namespace MmtRiskSdk\Domains\Accounts\Contracts;

use InvalidArgumentException;
use MmtRiskSdk\Contracts\CommandInterface;
use MmtRiskSdk\Domains\Accounts\Commands\AttachAccountRuleCommand;
use MmtRiskSdk\Domains\Accounts\Commands\CreateAccountCommand;
use MmtRiskSdk\Domains\Accounts\Commands\EvaluationHistoryRangeCommand;
use MmtRiskSdk\Domains\Accounts\Commands\EvaluationHistoryRecentCommand;
use MmtRiskSdk\Domains\Accounts\Commands\PatchAccountRuleMembershipCommand;
use MmtRiskSdk\Domains\Accounts\Commands\UpdateAccountCommand;
use MmtRiskSdk\TransportDrivers\Contracts\ActionResultInterface;
use MmtRiskSdk\TransportDrivers\Contracts\TransportInterface;
use MmtRiskSdk\TransportDrivers\Contracts\TransportPacket;

final class AccountsService implements AccountsServiceInterface
{
    private string $baseUrl = '/accounts';

    public function __construct(
        private readonly TransportInterface $transport,
    ) {}

    public function listAccounts(?string $brokerId = null): ActionResultInterface
    {
        $query = $this->omitNull(['broker_id' => $brokerId]);

        return $this->sendPacket('get', $this->baseUrl, $query);
    }

    public function createAccount(CommandInterface $command): ActionResultInterface
    {
        if (! $command instanceof CreateAccountCommand) {
            throw new InvalidArgumentException('Expected '.CreateAccountCommand::class);
        }

        return $this->sendPacket('post', $this->baseUrl, $command->toArray());
    }

    public function getAccountByLogin(string $login): ActionResultInterface
    {
        $url = $this->baseUrl.'/by-login/'.$this->encodePathSegment($login);

        return $this->sendPacket('get', $url);
    }

    public function evaluationHistoryRange(CommandInterface $command): ActionResultInterface
    {
        if (! $command instanceof EvaluationHistoryRangeCommand) {
            throw new InvalidArgumentException('Expected '.EvaluationHistoryRangeCommand::class);
        }

        return $this->sendPacket('post', $this->baseUrl.'/evaluation-history/range', $command->toArray());
    }

    public function evaluationHistoryRecent(CommandInterface $command): ActionResultInterface
    {
        if (! $command instanceof EvaluationHistoryRecentCommand) {
            throw new InvalidArgumentException('Expected '.EvaluationHistoryRecentCommand::class);
        }

        return $this->sendPacket('post', $this->baseUrl.'/evaluation-history/recent', $command->toArray());
    }

    public function listAccountsPage(
        ?string $brokerId,
        ?string $q,
        ?bool $isBlocked,
        ?string $sort,
        ?int $skip,
        ?int $take,
    ): ActionResultInterface {
        $query = $this->omitNull([
            'broker_id' => $brokerId,
            'q' => $q,
            'is_blocked' => $isBlocked,
            'sort' => $sort,
            'skip' => $skip,
            'take' => $take,
        ]);

        return $this->sendPacket('get', $this->baseUrl.'/page', $query);
    }

    public function accountStats(?string $brokerId = null): ActionResultInterface
    {
        $query = $this->omitNull(['broker_id' => $brokerId]);

        return $this->sendPacket('get', $this->baseUrl.'/stats', $query);
    }

    public function getAccountById(string $accountId): ActionResultInterface
    {
        $url = $this->baseUrl.'/'.$this->encodePathSegment($accountId);

        return $this->sendPacket('get', $url);
    }

    public function updateAccount(string $accountId, CommandInterface $command): ActionResultInterface
    {
        if (! $command instanceof UpdateAccountCommand) {
            throw new InvalidArgumentException('Expected '.UpdateAccountCommand::class);
        }

        $url = $this->baseUrl.'/'.$this->encodePathSegment($accountId);

        return $this->sendPacket('patch', $url, $command->toArray());
    }

    public function deleteAccount(string $accountId): ActionResultInterface
    {
        $url = $this->baseUrl.'/'.$this->encodePathSegment($accountId);

        return $this->sendPacket('delete', $url);
    }

    public function listAccountMetricChanges(string $accountId, ?int $limit = null): ActionResultInterface
    {
        $url = $this->baseUrl.'/'.$this->encodePathSegment($accountId).'/metric-changes';
        $query = $this->omitNull(['limit' => $limit]);

        return $this->sendPacket('get', $url, $query);
    }

    public function getAccountMetricsContext(string $accountId): ActionResultInterface
    {
        $url = $this->baseUrl.'/'.$this->encodePathSegment($accountId).'/metrics-context';

        return $this->sendPacket('get', $url);
    }

    public function getAccountMetricsEnrichment(string $accountId, ?int $days = null): ActionResultInterface
    {
        $url = $this->baseUrl.'/'.$this->encodePathSegment($accountId).'/metrics-enrichment';
        $query = $this->omitNull(['days' => $days]);

        return $this->sendPacket('get', $url, $query);
    }

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
    ): ActionResultInterface {
        $url = $this->baseUrl.'/'.$this->encodePathSegment($accountId).'/metrics/history';
        $query = $this->omitNull([
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

        return $this->sendPacket('get', $url, $query);
    }

    public function getAccountMetricTradeTimeline(
        string $accountId,
        string $metricKey,
        string $fromUtc,
        string $toUtc,
        ?int $offset,
        ?int $limit,
    ): ActionResultInterface {
        $url = $this->baseUrl.'/'.$this->encodePathSegment($accountId).'/metrics/trade-timeline';
        $query = $this->omitNull([
            'metric_key' => $metricKey,
            'from_utc' => $fromUtc,
            'to_utc' => $toUtc,
            'offset' => $offset,
            'limit' => $limit,
        ]);

        return $this->sendPacket('get', $url, $query);
    }

    public function listAccountRuleMemberships(string $accountId): ActionResultInterface
    {
        $url = $this->baseUrl.'/'.$this->encodePathSegment($accountId).'/rule-memberships';

        return $this->sendPacket('get', $url);
    }

    public function resetAccountRuleMatchStreak(string $accountId, string $ruleId): ActionResultInterface
    {
        $url = $this->baseUrl.'/'.$this->encodePathSegment($accountId).'/rules/'.$this->encodePathSegment($ruleId).'/match-streak/reset';

        return $this->sendPacket('post', $url);
    }

    public function patchAccountRuleMembership(string $accountId, string $ruleId, CommandInterface $command): ActionResultInterface
    {
        if (! $command instanceof PatchAccountRuleMembershipCommand) {
            throw new InvalidArgumentException('Expected '.PatchAccountRuleMembershipCommand::class);
        }

        $url = $this->baseUrl.'/'.$this->encodePathSegment($accountId).'/rules/'.$this->encodePathSegment($ruleId).'/membership';

        return $this->sendPacket('patch', $url, $command->toArray());
    }

    public function attachAccountRule(string $accountId, CommandInterface $command): ActionResultInterface
    {
        if (! $command instanceof AttachAccountRuleCommand) {
            throw new InvalidArgumentException('Expected '.AttachAccountRuleCommand::class);
        }

        $url = $this->baseUrl.'/'.$this->encodePathSegment($accountId).'/rules';

        return $this->sendPacket('post', $url, $command->toArray());
    }

    public function detachAccountRule(string $accountId, string $ruleId): ActionResultInterface
    {
        $url = $this->baseUrl.'/'.$this->encodePathSegment($accountId).'/rules/'.$this->encodePathSegment($ruleId);

        return $this->sendPacket('delete', $url);
    }

    public function detachAllAccountRules(string $accountId): ActionResultInterface
    {
        $url = $this->baseUrl.'/'.$this->encodePathSegment($accountId).'/rules';

        return $this->sendPacket('delete', $url);
    }

    public function syncMt5OpenPositions(string $accountId): ActionResultInterface
    {
        $url = $this->baseUrl.'/'.$this->encodePathSegment($accountId).'/sync-mt5-open-positions';

        return $this->sendPacket('post', $url);
    }

    public function listAccountOpenTrades(string $accountId): ActionResultInterface
    {
        $url = $this->baseUrl.'/'.$this->encodePathSegment($accountId).'/trades/open';

        return $this->sendPacket('get', $url);
    }

    private function encodePathSegment(string $value): string
    {
        return rawurlencode($value);
    }

    /**
     * @param  array<string, mixed>  $query
     * @return array<string, mixed>
     */
    private function omitNull(array $query): array
    {
        return array_filter($query, static fn (mixed $v): bool => $v !== null);
    }

    /**
     * @param  array<string, mixed>  $payload
     * @param  array<string, mixed>  $metadata
     */
    private function sendPacket(string $method, string $url, array $payload = [], array $metadata = []): ActionResultInterface
    {
        $packet = new TransportPacket(
            endpoint: $url,
            payload: $payload,
            metadata: array_merge(['method' => $method], $metadata),
        );

        return $this->transport->send($packet);
    }
}
