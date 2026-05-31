<?php

declare(strict_types=1);

namespace MmtRiskSdk\Domains\Brokers\Platforms\Shared;

use MmtRiskSdk\Domains\Brokers\ObjectResponses\BrokerIntegrationInputItem;

/**
 * Serializes broker integration input for create/update API payloads.
 */
final class BrokerIntegrationPayloadBuilder
{
    /**
     * @return array<string, mixed>
     */
    public static function toPayload(BrokerIntegrationInputItem $integration): array
    {
        $out = [];

        if ($integration->sdk !== null) {
            $out['sdk'] = BrokerSdkConfigAssembler::toPayload($integration->sdk);
        }

        if ($integration->kafka !== null) {
            $out['kafka'] = array_filter([
                'bootstrap_servers' => $integration->kafka->bootstrap_servers,
                'topic_risk_events' => $integration->kafka->topic_risk_events,
                'topic_risk_notifications' => $integration->kafka->topic_risk_notifications,
            ], static fn (mixed $v): bool => $v !== null);
        }

        return $out;
    }
}
