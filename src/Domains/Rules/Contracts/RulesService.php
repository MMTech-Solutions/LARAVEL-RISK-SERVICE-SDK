<?php

declare(strict_types=1);

namespace MmtRiskSdk\Domains\Rules\Contracts;

use InvalidArgumentException;
use MmtRiskSdk\Contracts\CommandInterface;
use MmtRiskSdk\Domains\Rules\Commands\CreateRuleCommand;
use MmtRiskSdk\Domains\Rules\Commands\UpdateRuleCommand;
use MmtRiskSdk\TransportDrivers\Contracts\ActionResultInterface;
use MmtRiskSdk\TransportDrivers\Contracts\TransportInterface;
use MmtRiskSdk\TransportDrivers\Contracts\TransportPacket;

final class RulesService implements RulesServiceInterface
{
    private string $baseUrl = '/rules';

    public function __construct(
        private readonly TransportInterface $transport,
    ) {}

    public function listRules(?bool $activeOnly = null): ActionResultInterface
    {
        $query = [];
        if ($activeOnly !== null) {
            $query['active_only'] = $activeOnly;
        }

        return $this->sendPacket('get', $this->baseUrl, $query);
    }

    public function createRule(CommandInterface $command): ActionResultInterface
    {
        if (! $command instanceof CreateRuleCommand) {
            throw new InvalidArgumentException('Expected '.CreateRuleCommand::class);
        }

        return $this->sendPacket('post', $this->baseUrl, $command->toArray());
    }

    public function listActiveRules(): ActionResultInterface
    {
        return $this->sendPacket('get', $this->baseUrl.'/active');
    }

    public function getRule(string $ruleId): ActionResultInterface
    {
        $url = $this->baseUrl.'/'.$this->encodePathSegment($ruleId);

        return $this->sendPacket('get', $url);
    }

    public function updateRule(string $ruleId, CommandInterface $command): ActionResultInterface
    {
        if (! $command instanceof UpdateRuleCommand) {
            throw new InvalidArgumentException('Expected '.UpdateRuleCommand::class);
        }

        $url = $this->baseUrl.'/'.$this->encodePathSegment($ruleId);

        return $this->sendPacket('patch', $url, $command->toArray());
    }

    public function deleteRule(string $ruleId): ActionResultInterface
    {
        $url = $this->baseUrl.'/'.$this->encodePathSegment($ruleId);

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
