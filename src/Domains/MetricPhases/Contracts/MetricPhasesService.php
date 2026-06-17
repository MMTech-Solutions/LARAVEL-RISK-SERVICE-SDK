<?php

declare(strict_types=1);

namespace MmtRiskSdk\Domains\MetricPhases\Contracts;

use InvalidArgumentException;
use MmtRiskSdk\Contracts\CommandInterface;
use MmtRiskSdk\Domains\MetricPhases\Commands\AssignPhaseRuleCommand;
use MmtRiskSdk\Domains\MetricPhases\Commands\CreateMetricPhaseCommand;
use MmtRiskSdk\TransportDrivers\Contracts\ActionResultInterface;
use MmtRiskSdk\TransportDrivers\Contracts\TransportInterface;
use MmtRiskSdk\TransportDrivers\Contracts\TransportPacket;

final class MetricPhasesService implements MetricPhasesServiceInterface
{
    private string $baseUrl = '/accounts';

    public function __construct(
        private readonly TransportInterface $transport,
    ) {}

    public function listMetricPhases(string $accountId): ActionResultInterface
    {
        $url = $this->phaseBasePath($accountId);

        return $this->sendPacket('get', $url);
    }

    public function createMetricPhase(string $accountId, CommandInterface $command): ActionResultInterface
    {
        if (! $command instanceof CreateMetricPhaseCommand) {
            throw new InvalidArgumentException('Expected '.CreateMetricPhaseCommand::class);
        }

        $url = $this->phaseBasePath($accountId);

        return $this->sendPacket('post', $url, $command->toArray());
    }

    public function deleteMetricPhase(string $accountId, string $phaseId): ActionResultInterface
    {
        $url = $this->phaseBasePath($accountId).'/'.$this->encodePathSegment($phaseId);

        return $this->sendPacket('delete', $url);
    }

    public function disableMetricPhase(string $accountId, string $phaseId): ActionResultInterface
    {
        $url = $this->phaseBasePath($accountId).'/'.$this->encodePathSegment($phaseId).'/disable';

        return $this->sendPacket('patch', $url);
    }

    public function resetMetricPhase(string $accountId, string $phaseId): ActionResultInterface
    {
        $url = $this->phaseBasePath($accountId).'/'.$this->encodePathSegment($phaseId).'/reset';

        return $this->sendPacket('post', $url);
    }

    public function listPhaseMetricChanges(string $accountId, string $phaseId, ?int $limit = null): ActionResultInterface
    {
        $url = $this->phaseBasePath($accountId).'/'.$this->encodePathSegment($phaseId).'/metric-changes';
        $query = $this->omitNull(['limit' => $limit]);

        return $this->sendPacket('get', $url, $query);
    }

    public function getPhaseMetricsEnrichment(string $accountId, string $phaseId, ?int $days = null): ActionResultInterface
    {
        $url = $this->phaseBasePath($accountId).'/'.$this->encodePathSegment($phaseId).'/metrics/enrichment';
        $query = $this->omitNull(['days' => $days]);

        return $this->sendPacket('get', $url, $query);
    }

    public function getPhaseMetricHistory(
        string $accountId,
        string $phaseId,
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
        $url = $this->phaseBasePath($accountId).'/'.$this->encodePathSegment($phaseId).'/metrics/history';
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

    public function getPhaseMetricTradeTimeline(
        string $accountId,
        string $phaseId,
        string $metricKey,
        string $fromUtc,
        string $toUtc,
        ?int $offset,
        ?int $limit,
    ): ActionResultInterface {
        $url = $this->phaseBasePath($accountId).'/'.$this->encodePathSegment($phaseId).'/metrics/trade-timeline';
        $query = $this->omitNull([
            'metric_key' => $metricKey,
            'from_utc' => $fromUtc,
            'to_utc' => $toUtc,
            'offset' => $offset,
            'limit' => $limit,
        ]);

        return $this->sendPacket('get', $url, $query);
    }

    public function listPhaseRuleMemberships(string $accountId, string $phaseId): ActionResultInterface
    {
        $url = $this->phaseBasePath($accountId).'/'.$this->encodePathSegment($phaseId).'/rule-memberships';

        return $this->sendPacket('get', $url);
    }

    public function assignRuleToPhase(string $accountId, string $phaseId, CommandInterface $command): ActionResultInterface
    {
        if (! $command instanceof AssignPhaseRuleCommand) {
            throw new InvalidArgumentException('Expected '.AssignPhaseRuleCommand::class);
        }

        $url = $this->phaseBasePath($accountId).'/'.$this->encodePathSegment($phaseId).'/rules';

        return $this->sendPacket('post', $url, $command->toArray());
    }

    public function unassignRuleFromPhase(string $accountId, string $phaseId, string $ruleId): ActionResultInterface
    {
        $url = $this->phaseBasePath($accountId)
            .'/'.$this->encodePathSegment($phaseId)
            .'/rules/'.$this->encodePathSegment($ruleId);

        return $this->sendPacket('delete', $url);
    }

    private function phaseBasePath(string $accountId): string
    {
        return $this->baseUrl.'/'.$this->encodePathSegment($accountId).'/metric-phases';
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
