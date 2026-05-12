<?php

declare(strict_types=1);

namespace MmtRiskSdk\Domains\Ingress\Contracts;

use InvalidArgumentException;
use MmtRiskSdk\Contracts\CommandInterface;
use MmtRiskSdk\Domains\Ingress\Commands\IngressEventCommand;
use MmtRiskSdk\TransportDrivers\Contracts\ActionResultInterface;
use MmtRiskSdk\TransportDrivers\Contracts\TransportInterface;
use MmtRiskSdk\TransportDrivers\Contracts\TransportPacket;

final class IngressService implements IngressServiceInterface
{
    private string $baseUrl = '/internal/ingress';

    public function __construct(
        private readonly TransportInterface $transport,
    ) {}

    public function postEvent(CommandInterface $command): ActionResultInterface
    {
        if (! $command instanceof IngressEventCommand) {
            throw new InvalidArgumentException('Expected '.IngressEventCommand::class);
        }

        return $this->sendPacket('post', $this->baseUrl.'/events', $command->toArray());
    }

    /**
     * @param  array<string, mixed>  $payload
     * @param  array<string, mixed>  $metadata
     */
    private function sendPacket(string $method, string $url, array $payload = [], array $metadata = []): ActionResultInterface
    {
        $packet = new TransportPacket(
            endpoint: $url,
            payload: $payload,
            metadata: array_merge(['method' => $method], $metadata),
        );

        return $this->transport->send($packet);
    }
}
