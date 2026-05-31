<?php

declare(strict_types=1);

namespace MmtRiskSdk\Domains\Brokers\ObjectResponses;

use MmtRiskSdk\WireHydration\Attributes\WireMapped;

#[WireMapped]
final class BrokerIntegrationSdkResolvedItem
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

    public ?string $frontoffice_base_url = null;

    public ?string $frontoffice_api_key = null;

    public ?string $default_transfer_asset_id = null;

    public ?string $kafka_bootstrap_servers = null;

    public ?string $kafka_username = null;

    public ?string $kafka_password = null;

    public ?string $kafka_topic_external_events = null;

    public ?string $kafka_group_id = null;

    public ?string $kafka_group_id_prefix = null;
}
