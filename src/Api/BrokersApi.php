<?php

declare(strict_types=1);

namespace MmtRiskSdk\Api;

use MmtRiskSdk\RiskRestClient;
use MmtRiskSdk\Support\UriHelper;

/**
 * /brokers CRUD.
 */
final class BrokersApi
{
    public function __construct(
        private readonly RiskRestClient $client,
    ) {
    }

    public function listBrokers(): mixed
    {
        return $this->client->envelopeRequest('GET', '/brokers');
    }

    /**
     * @param  array<string, mixed>  $body
     */
    public function createBroker(array $body): mixed
    {
        return $this->client->envelopeRequest('POST', '/brokers', null, $body);
    }

    public function getBroker(string $brokerId): mixed
    {
        $id = UriHelper::pathSegment($brokerId);

        return $this->client->envelopeRequest('GET', "/brokers/{$id}");
    }

    /**
     * @param  array<string, mixed>  $body
     */
    public function updateBroker(string $brokerId, array $body): mixed
    {
        $id = UriHelper::pathSegment($brokerId);

        return $this->client->envelopeRequest('PATCH', "/brokers/{$id}", null, $body);
    }

    public function deleteBroker(string $brokerId): mixed
    {
        $id = UriHelper::pathSegment($brokerId);

        return $this->client->envelopeRequest('DELETE', "/brokers/{$id}");
    }
}
