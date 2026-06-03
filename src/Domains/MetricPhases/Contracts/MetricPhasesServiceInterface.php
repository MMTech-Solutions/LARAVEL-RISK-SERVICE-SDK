<?php

declare(strict_types=1);

namespace MmtRiskSdk\Domains\MetricPhases\Contracts;

use MmtRiskSdk\Contracts\CommandInterface;
use MmtRiskSdk\TransportDrivers\Contracts\ActionResultInterface;

interface MetricPhasesServiceInterface
{
    public function listMetricPhases(string $accountId): ActionResultInterface;

    public function createMetricPhase(string $accountId, CommandInterface $command): ActionResultInterface;

    public function deleteMetricPhase(string $accountId, string $phaseId): ActionResultInterface;

    public function disableMetricPhase(string $accountId, string $phaseId): ActionResultInterface;

    public function listPhaseMetricChanges(string $accountId, string $phaseId, ?int $limit = null): ActionResultInterface;

    public function getPhaseMetricsEnrichment(string $accountId, string $phaseId, ?int $days = null): ActionResultInterface;

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
    ): ActionResultInterface;

    public function getPhaseMetricTradeTimeline(
        string $accountId,
        string $phaseId,
        string $metricKey,
        string $fromUtc,
        string $toUtc,
        ?int $offset,
        ?int $limit,
    ): ActionResultInterface;

    public function listPhaseRuleMemberships(string $accountId, string $phaseId): ActionResultInterface;

    public function assignRuleToPhase(string $accountId, string $phaseId, CommandInterface $command): ActionResultInterface;

    public function unassignRuleFromPhase(string $accountId, string $phaseId, string $ruleId): ActionResultInterface;
}
