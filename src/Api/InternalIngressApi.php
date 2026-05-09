<?php

declare(strict_types=1);

namespace MmtRiskSdk\Api;

use MmtRiskSdk\RiskRestClient;

/**
 * POST /internal/ingress/events
 */
final class InternalIngressApi
{
    public function __construct(
        private readonly RiskRestClient $client,
    ) {
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    public function postEvent(array $payload): mixed
    {
        return $this->client->envelopeRequest('POST', '/internal/ingress/events', null, $payload);
    }
}
