<?php

declare(strict_types=1);

namespace MmtRiskSdk\Domains\Brokers\ObjectResponses;

use MmtRiskSdk\WireHydration\Attributes\WireMapped;

#[WireMapped]
final class BrokerSdkConfigInputItem
{
    public ?string $trading_service_base_url = null;

    public ?string $connection_id = null;

    public ?string $mt5_server = null;

    public ?int $mt5_port = null;

    public ?string $mt5_login = null;

    public ?string $connection_name = null;
}
