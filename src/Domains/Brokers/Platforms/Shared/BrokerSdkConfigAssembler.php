<?php

declare(strict_types=1);

namespace MmtRiskSdk\Domains\Brokers\Platforms\Shared;

use MmtRiskSdk\Domains\Brokers\ObjectResponses\BrokerSdkConfigInputItem;
use MmtRiskSdk\Domains\Brokers\Platforms\B2Trader\BrokerB2TraderSdkConfigItem;
use MmtRiskSdk\Domains\Brokers\Platforms\MT5\BrokerMt5SdkConfigItem;

/**
 * Builds flat broker SDK config payloads from shared + platform-specific parts.
 */
final class BrokerSdkConfigAssembler
{
    public static function forMt5(
        BrokerSdkCommonConfigItem $common,
        BrokerMt5SdkConfigItem $mt5,
    ): BrokerSdkConfigInputItem {
        $config = new BrokerSdkConfigInputItem;
        $config->trading_service_base_url = $common->trading_service_base_url;
        $config->connection_id = $common->connection_id;
        $config->connection_name = $common->connection_name;
        $config->mt5_server = $mt5->mt5_server;
        $config->mt5_port = $mt5->mt5_port;
        $config->mt5_login = $mt5->mt5_login;

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
        $config->keycloak_url = $b2Trader->keycloak_url;
        $config->bbp_client_id = $b2Trader->bbp_client_id;
        $config->bbp_client_secret = $b2Trader->bbp_client_secret;
        $config->history_base_url = $b2Trader->history_base_url;
        $config->frontoffice_base_url = $b2Trader->frontoffice_base_url;
        $config->frontoffice_api_key = $b2Trader->frontoffice_api_key;
        $config->default_transfer_asset_id = $b2Trader->default_transfer_asset_id;
        $config->kafka_bootstrap_servers = $b2Trader->kafka_bootstrap_servers;
        $config->kafka_username = $b2Trader->kafka_username;
        $config->kafka_password = $b2Trader->kafka_password;
        $config->kafka_topic_external_events = $b2Trader->kafka_topic_external_events;
        $config->kafka_group_id = $b2Trader->kafka_group_id;
        $config->kafka_group_id_prefix = $b2Trader->kafka_group_id_prefix;

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
            'keycloak_url' => $config->keycloak_url,
            'bbp_client_id' => $config->bbp_client_id,
            'bbp_client_secret' => $config->bbp_client_secret,
            'history_base_url' => $config->history_base_url,
            'frontoffice_base_url' => $config->frontoffice_base_url,
            'frontoffice_api_key' => $config->frontoffice_api_key,
            'default_transfer_asset_id' => $config->default_transfer_asset_id,
            'kafka_bootstrap_servers' => $config->kafka_bootstrap_servers,
            'kafka_username' => $config->kafka_username,
            'kafka_password' => $config->kafka_password,
            'kafka_topic_external_events' => $config->kafka_topic_external_events,
            'kafka_group_id' => $config->kafka_group_id,
            'kafka_group_id_prefix' => $config->kafka_group_id_prefix,
        ], static fn (mixed $v): bool => $v !== null);
    }
}
