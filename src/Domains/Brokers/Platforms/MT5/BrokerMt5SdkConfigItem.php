<?php

declare(strict_types=1);

namespace MmtRiskSdk\Domains\Brokers\Platforms\MT5;

/**
 * MT5-specific SDK connection overrides for broker integration.
 */
final class BrokerMt5SdkConfigItem
{
    public function __construct(
        public ?string $mt5_server = null,
        public ?int $mt5_port = null,
        public ?string $mt5_login = null,
    ) {}
}
