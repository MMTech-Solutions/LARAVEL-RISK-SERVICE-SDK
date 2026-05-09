<?php

declare(strict_types=1);

namespace MmtRiskSdk\Api;

use MmtRiskSdk\RiskRestClient;
use MmtRiskSdk\Support\QueryHelper;
use MmtRiskSdk\Support\UriHelper;

/**
 * /rules* endpoints.
 */
final class RulesApi
{
    public function __construct(
        private readonly RiskRestClient $client,
    ) {
    }

    public function listRules(?bool $activeOnly = null): mixed
    {
        $q = QueryHelper::omitNull(['active_only' => $activeOnly]);

        return $this->client->envelopeRequest('GET', '/rules', $q !== [] ? $q : null);
    }

    /**
     * @param  array<string, mixed>  $body
     */
    public function createRule(array $body): mixed
    {
        return $this->client->envelopeRequest('POST', '/rules', null, $body);
    }

    public function listActiveRules(): mixed
    {
        return $this->client->envelopeRequest('GET', '/rules/active');
    }

    public function getRule(string $ruleId): mixed
    {
        $id = UriHelper::pathSegment($ruleId);

        return $this->client->envelopeRequest('GET', "/rules/{$id}");
    }

    /**
     * @param  array<string, mixed>  $body
     */
    public function updateRule(string $ruleId, array $body): mixed
    {
        $id = UriHelper::pathSegment($ruleId);

        return $this->client->envelopeRequest('PATCH', "/rules/{$id}", null, $body);
    }

    public function deleteRule(string $ruleId): mixed
    {
        $id = UriHelper::pathSegment($ruleId);

        return $this->client->envelopeRequest('DELETE', "/rules/{$id}");
    }
}
