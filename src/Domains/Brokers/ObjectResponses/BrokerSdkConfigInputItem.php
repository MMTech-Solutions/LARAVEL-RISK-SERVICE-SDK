<?php

declare(strict_types=1);

namespace MmtRiskSdk\Domains\Brokers\ObjectResponses;

use MmtRiskSdk\WireHydration\Attributes\WireMapped;

/**
 * Flat SDK config as returned by the Risk API (MT5 + B2Trader fields; use platform-specific builders to compose).
 */
#[WireMapped]
final class BrokerSdkConfigInputItem
{
    public ?string $trading_service_base_url = null;

    public ?string $connection_id = null;

    public ?string $connection_name = null;

    public ?string $mt5_server = null;

    public ?int $mt5_port = null;

    public ?string $mt5_login = null;

    public ?string $keycloak_url = null;

    public ?string $bbp_client_id = null;

    public ?string $bbp_client_secret = null;

    public ?string $history_base_url = null;

    public ?string $default_transfer_asset_id = null;

    public ?string $dss_ws_base_url = null;
}
