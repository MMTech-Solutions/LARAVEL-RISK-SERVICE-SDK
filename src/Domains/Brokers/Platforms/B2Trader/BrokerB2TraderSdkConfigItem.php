<?php

declare(strict_types=1);

namespace MmtRiskSdk\Domains\Brokers\Platforms\B2Trader;

/**
 * B2Trader-specific SDK connection overrides for broker integration.
 */
final class BrokerB2TraderSdkConfigItem
{
    public function __construct(
        public ?string $keycloak_url = null,
        public ?string $bbp_client_id = null,
        public ?string $bbp_client_secret = null,
        public ?string $history_base_url = null,
        public ?string $frontoffice_base_url = null,
        public ?string $frontoffice_api_key = null,
        public ?string $default_transfer_asset_id = null,
        public ?string $kafka_bootstrap_servers = null,
        public ?string $kafka_username = null,
        public ?string $kafka_password = null,
        public ?string $kafka_topic_external_events = null,
        public ?string $kafka_group_id = null,
        public ?string $kafka_group_id_prefix = null,
    ) {}
}
