<?php

declare(strict_types=1);

namespace MmtRiskSdk\Domains\Brokers\Platforms\MT5;

/**
 * @deprecated Risk API no longer accepts mt5_server, mt5_port, or mt5_login in integration.sdk.
 *             Use {@see BrokerSdkConfigAssembler::forMt5()} with {@see BrokerSdkCommonConfigItem} only.
 */
final class BrokerMt5SdkConfigItem
{
    public function __construct(
        public ?string $mt5_server = null,
        public ?int $mt5_port = null,
        public ?string $mt5_login = null,
    ) {}
}
