<?php

declare(strict_types=1);

namespace MmtRiskSdk\Domains\Brokers\ObjectResponses;

use MmtRiskSdk\WireHydration\Attributes\WireMapped;

#[WireMapped]
final class BrokerIntegrationInputItem
{
    public ?BrokerSdkConfigInputItem $sdk = null;

    public ?BrokerKafkaConfigInputItem $kafka = null;
}
