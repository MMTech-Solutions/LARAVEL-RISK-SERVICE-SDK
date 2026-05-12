<?php

declare(strict_types=1);

namespace MmtRiskSdk\Domains\Brokers\Contracts;

use InvalidArgumentException;
use MmtRiskSdk\Contracts\CommandInterface;
use MmtRiskSdk\Domains\Brokers\Commands\CreateBrokerCommand;
use MmtRiskSdk\Domains\Brokers\Commands\UpdateBrokerCommand;
use MmtRiskSdk\TransportDrivers\Contracts\ActionResultInterface;
use MmtRiskSdk\TransportDrivers\Contracts\TransportInterface;
use MmtRiskSdk\TransportDrivers\Contracts\TransportPacket;

final class BrokersService implements BrokersServiceInterface
{
    private string $baseUrl = '/brokers';

    public function __construct(
        private readonly TransportInterface $transport,
    ) {}

    public function listBrokers(): ActionResultInterface
    {
        return $this->sendPacket('get', $this->baseUrl);
    }

    public function createBroker(CommandInterface $command): ActionResultInterface
    {
        if (! $command instanceof CreateBrokerCommand) {
            throw new InvalidArgumentException('Expected '.CreateBrokerCommand::class);
        }

        return $this->sendPacket('post', $this->baseUrl, $command->toArray());
    }

    public function getBrokerById(string $brokerId): ActionResultInterface
    {
        $url = $this->baseUrl.'/'.$this->encodePathSegment($brokerId);

        return $this->sendPacket('get', $url);
    }

    public function updateBroker(string $brokerId, CommandInterface $command): ActionResultInterface
    {
        if (! $command instanceof UpdateBrokerCommand) {
            throw new InvalidArgumentException('Expected '.UpdateBrokerCommand::class);
        }

        $url = $this->baseUrl.'/'.$this->encodePathSegment($brokerId);

        return $this->sendPacket('patch', $url, $command->toArray());
    }

    public function deleteBroker(string $brokerId): ActionResultInterface
    {
        $url = $this->baseUrl.'/'.$this->encodePathSegment($brokerId);

        return $this->sendPacket('delete', $url);
    }

    private function encodePathSegment(string $value): string
    {
        return rawurlencode($value);
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
