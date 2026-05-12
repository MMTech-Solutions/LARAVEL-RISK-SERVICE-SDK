<?php

declare(strict_types=1);

namespace MmtRiskSdk\Domains\Brokers\ObjectResponses;

use MmtRiskSdk\WireHydration\Attributes\WireMapped;

#[WireMapped]
final class BrokerIntegrationKafkaResolvedItem
{
    public string $bootstrap_servers;

    public string $topic_risk_notifications;
}
