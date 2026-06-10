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
        $config->default_transfer_asset_id = $b2Trader->default_transfer_asset_id;
        $config->dss_ws_base_url = $b2Trader->dss_ws_base_url;

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
            'default_transfer_asset_id' => $config->default_transfer_asset_id,
            'dss_ws_base_url' => $config->dss_ws_base_url,
        ], static fn (mixed $v): bool => $v !== null);
    }
}
