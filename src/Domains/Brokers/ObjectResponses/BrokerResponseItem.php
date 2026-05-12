<?php

declare(strict_types=1);

namespace MmtRiskSdk\Domains\Brokers\ObjectResponses;

use MmtRiskSdk\WireHydration\Attributes\WireMapped;

#[WireMapped]
final class BrokerResponseItem
{
    public string $id;

    public string $name;

    public string $broker_type;

    public BrokerIntegrationResolvedItem $integration;

    /** @var array<string, mixed>|null */
    public ?array $integration_overrides = null;

    public ?string $credentials = null;

    public string $created_at;

    public string $updated_at;

    public ?string $sdk_live_status = null;

    public ?string $sdk_live_message = null;
}
