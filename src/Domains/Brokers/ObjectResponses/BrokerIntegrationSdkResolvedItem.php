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

    public ?string $platform_server = null;

    public ?int $platform_port = null;

    public ?string $platform_login = null;

    public ?string $keycloak_url = null;

    public ?string $bbp_client_id = null;

    public ?string $bbp_client_secret = null;

    public ?string $history_base_url = null;

    public ?string $default_transfer_asset_id = null;

    public ?string $kafka_bootstrap_servers = null;

    public ?string $kafka_security_protocol = null;

    public ?string $kafka_sasl_mechanism = null;

    public ?string $kafka_username = null;

    public ?string $kafka_password = null;

    public ?string $kafka_external_events_topic = null;

    public ?string $kafka_consumer_group_id_prefix = null;

    public ?string $kafka_auto_offset_reset = null;
}
