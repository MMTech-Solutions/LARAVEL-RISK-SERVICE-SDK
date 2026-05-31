<?php

declare(strict_types=1);

namespace MmtRiskSdk\Domains\Brokers\Commands;

use MmtRiskSdk\Contracts\CommandInterface;
use MmtRiskSdk\Domains\Brokers\ObjectResponses\BrokerIntegrationInputItem;
use MmtRiskSdk\Domains\Brokers\Platforms\Shared\BrokerIntegrationPayloadBuilder;

final class UpdateBrokerCommand implements CommandInterface
{
    public function __construct(
        public ?string $name = null,
        public ?string $broker_type = null,
        public ?string $credentials = null,
        public ?BrokerIntegrationInputItem $integration = null,
    ) {}

    public function toArray(): array
    {
        $payload = [
            'name' => $this->name,
            'broker_type' => $this->broker_type,
            'credentials' => $this->credentials,
            'integration' => null,
        ];

        if ($this->integration !== null) {
            $payload['integration'] = BrokerIntegrationPayloadBuilder::toPayload($this->integration);
        }

        return array_filter($payload, static fn (mixed $v): bool => ! is_null($v));
    }
}
