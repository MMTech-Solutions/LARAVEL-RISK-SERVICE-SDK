<?php

declare(strict_types=1);

namespace MmtRiskSdk\Domains\Brokers\ObjectResponses;

use MmtRiskSdk\WireHydration\Attributes\WireMapped;

#[WireMapped]
final class BrokerKafkaConfigInputItem
{
    public ?string $bootstrap_servers = null;

    public ?string $topic_risk_notifications = null;
}
