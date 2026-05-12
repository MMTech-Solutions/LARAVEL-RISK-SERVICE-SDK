<?php

declare(strict_types=1);

namespace MmtRiskSdk\Domains\Brokers\ObjectResponses;

use MmtRiskSdk\WireHydration\Attributes\WireMapped;

#[WireMapped]
final class BrokerIntegrationResolvedItem
{
    public BrokerIntegrationSdkResolvedItem $sdk;

    public BrokerIntegrationKafkaResolvedItem $kafka;

    public BrokerIntegrationIngressResolvedItem $ingress;
}
