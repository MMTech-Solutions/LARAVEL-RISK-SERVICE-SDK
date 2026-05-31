<?php

declare(strict_types=1);

namespace MmtRiskSdk\Domains\Brokers\Platforms\Shared;

/**
 * Shared SDK connection fields for all broker platforms (MT5, B2Trader, etc.).
 */
final class BrokerSdkCommonConfigItem
{
    public function __construct(
        public ?string $trading_service_base_url = null,
        public ?string $connection_id = null,
        public ?string $connection_name = null,
    ) {}
}
