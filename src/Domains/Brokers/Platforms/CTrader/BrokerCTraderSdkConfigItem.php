<?php

declare(strict_types=1);

namespace MmtRiskSdk\Domains\Brokers\Platforms\CTrader;

/**
 * CTrader platform connection hints for broker integration.sdk (Risk {@see BrokerSdkConfigInput}).
 */
final class BrokerCTraderSdkConfigItem
{
    public function __construct(
        public ?string $platform_server = null,
        public ?int $platform_port = null,
        public ?string $platform_login = null,
        public ?string $broker_name = null,
        public ?string $manager_proxy_host = null,
        public ?int $manager_proxy_port = null,
        public ?string $manager_plant_id = null,
        public ?string $manager_environment_name = null,
    ) {}
}
