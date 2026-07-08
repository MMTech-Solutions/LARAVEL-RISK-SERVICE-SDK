<?php

declare(strict_types=1);

namespace MmtRiskSdk\Domains\Brokers\Platforms\MT5;

/**
 * MT5/MT4 platform connection hints for broker integration.sdk (Risk {@see BrokerSdkConfigInput}).
 */
final class BrokerMt5SdkConfigItem
{
    public function __construct(
        public ?string $platform_server = null,
        public ?int $platform_port = null,
        public ?string $platform_login = null,
    ) {}
}
