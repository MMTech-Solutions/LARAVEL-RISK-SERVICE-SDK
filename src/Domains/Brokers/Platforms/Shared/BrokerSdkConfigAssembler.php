<?php

declare(strict_types=1);

namespace MmtRiskSdk\Domains\Brokers\Platforms\Shared;

use MmtRiskSdk\Domains\Brokers\ObjectResponses\BrokerSdkConfigInputItem;
use MmtRiskSdk\Domains\Brokers\Platforms\B2Trader\BrokerB2TraderSdkConfigItem;

/**
 * Builds flat broker SDK config payloads from shared + platform-specific parts.
 */
final class BrokerSdkConfigAssembler
{
    /**
     * MT5/MT4 SDK config for broker integration via trading-service session only.
     *
     * Risk API accepts {@see BrokerSdkCommonConfigItem} fields only; legacy {@code mt5_*} keys are rejected.
     */
    public static function forMt5(BrokerSdkCommonConfigItem $common): BrokerSdkConfigInputItem
    {
        $config = new BrokerSdkConfigInputItem;
        $config->trading_service_base_url = $common->trading_service_base_url;
        $config->connection_id = $common->connection_id;
        $config->connection_name = $common->connection_name;

        return $config;
    }

    public static function forB2Trader(
        BrokerSdkCommonConfigItem $common,
        BrokerB2TraderSdkConfigItem $b2Trader,
    ): BrokerSdkConfigInputItem {
        $config = new BrokerSdkConfigInputItem;
        $config->trading_service_base_url = $common->trading_service_base_url;
        $config->connection_id = $common->connection_id;
        $config->connection_name = $common->connection_name;
        $config->platform_server = $b2Trader->platform_server;
        $config->platform_port = $b2Trader->platform_port;
        $config->platform_login = $b2Trader->platform_login;
        $config->keycloak_url = $b2Trader->keycloak_url;
        $config->bbp_client_id = $b2Trader->bbp_client_id;
        $config->bbp_client_secret = $b2Trader->bbp_client_secret;
        $config->history_base_url = $b2Trader->history_base_url;
        $config->default_transfer_asset_id = $b2Trader->default_transfer_asset_id;
        $config->kafka_bootstrap_servers = $b2Trader->kafka_bootstrap_servers;
        $config->kafka_security_protocol = $b2Trader->kafka_security_protocol;
        $config->kafka_sasl_mechanism = $b2Trader->kafka_sasl_mechanism;
        $config->kafka_username = $b2Trader->kafka_username;
        $config->kafka_password = $b2Trader->kafka_password;
        $config->kafka_external_events_topic = $b2Trader->kafka_external_events_topic;
        $config->kafka_consumer_group_id_prefix = $b2Trader->kafka_consumer_group_id_prefix;
        $config->kafka_auto_offset_reset = $b2Trader->kafka_auto_offset_reset;

        return $config;
    }

    /**
     * @return array<string, mixed>
     */
    public static function toPayload(BrokerSdkConfigInputItem $config): array
    {
        return array_filter([
            'trading_service_base_url' => $config->trading_service_base_url,
            'connection_id' => $config->connection_id,
            'connection_name' => $config->connection_name,
            'mt5_server' => $config->mt5_server,
            'mt5_port' => $config->mt5_port,
            'mt5_login' => $config->mt5_login,
            'platform_server' => $config->platform_server,
            'platform_port' => $config->platform_port,
            'platform_login' => $config->platform_login,
            'keycloak_url' => $config->keycloak_url,
            'bbp_client_id' => $config->bbp_client_id,
            'bbp_client_secret' => $config->bbp_client_secret,
            'history_base_url' => $config->history_base_url,
            'default_transfer_asset_id' => $config->default_transfer_asset_id,
            'kafka_bootstrap_servers' => $config->kafka_bootstrap_servers,
            'kafka_security_protocol' => $config->kafka_security_protocol,
            'kafka_sasl_mechanism' => $config->kafka_sasl_mechanism,
            'kafka_username' => $config->kafka_username,
            'kafka_password' => $config->kafka_password,
            'kafka_external_events_topic' => $config->kafka_external_events_topic,
            'kafka_consumer_group_id_prefix' => $config->kafka_consumer_group_id_prefix,
            'kafka_auto_offset_reset' => $config->kafka_auto_offset_reset,
        ], static fn (mixed $v): bool => $v !== null);
    }
}
