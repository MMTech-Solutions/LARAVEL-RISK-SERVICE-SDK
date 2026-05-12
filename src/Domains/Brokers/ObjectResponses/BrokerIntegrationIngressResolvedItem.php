<?php

declare(strict_types=1);

namespace MmtRiskSdk\Domains\Brokers\ObjectResponses;

use MmtRiskSdk\WireHydration\Attributes\WireMapped;

#[WireMapped]
final class BrokerIntegrationIngressResolvedItem
{
    public string $events_http_path = '/internal/ingress/events';
}
