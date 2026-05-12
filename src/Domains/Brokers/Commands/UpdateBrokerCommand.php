<?php

declare(strict_types=1);

namespace MmtRiskSdk\Domains\Brokers\Commands;

use MmtRiskSdk\Contracts\CommandInterface;
use MmtRiskSdk\Domains\Brokers\ObjectResponses\BrokerIntegrationInputItem;

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
            $payload['integration'] = $this->integrationPayload($this->integration);
        }

        return array_filter($payload, static fn (mixed $v): bool => ! is_null($v));
    }

    /**
     * @return array<string, mixed>
     */
    private function integrationPayload(BrokerIntegrationInputItem $integration): array
    {
        $out = [];
        if ($integration->sdk !== null) {
            $out['sdk'] = array_filter([
                'trading_service_base_url' => $integration->sdk->trading_service_base_url,
                'connection_id' => $integration->sdk->connection_id,
                'mt5_server' => $integration->sdk->mt5_server,
                'mt5_port' => $integration->sdk->mt5_port,
                'mt5_login' => $integration->sdk->mt5_login,
                'connection_name' => $integration->sdk->connection_name,
            ], static fn (mixed $v): bool => ! is_null($v));
        }
        if ($integration->kafka !== null) {
            $out['kafka'] = array_filter([
                'bootstrap_servers' => $integration->kafka->bootstrap_servers,
                'topic_risk_notifications' => $integration->kafka->topic_risk_notifications,
            ], static fn (mixed $v): bool => ! is_null($v));
        }

        return $out;
    }
}
