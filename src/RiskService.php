<?php

declare(strict_types=1);

namespace MmtRiskSdk;

use MmtRiskSdk\Domains\Accounts\Contracts\AccountsServiceInterface;
use MmtRiskSdk\Domains\Brokers\Contracts\BrokersServiceInterface;
use MmtRiskSdk\Domains\Ingress\Contracts\IngressServiceInterface;
use MmtRiskSdk\Domains\Rules\Contracts\RulesServiceInterface;
use MmtRiskSdk\TransportDrivers\Contracts\TransportInterface;
use MmtRiskSdk\TransportDrivers\Contracts\TransportPacket;

class RiskService
{
    public function __construct(
        private readonly TransportInterface $transport,
    ) {}

    public function accounts(): AccountsServiceInterface
    {
        return resolve(AccountsServiceInterface::class);
    }

    public function brokers(): BrokersServiceInterface
    {
        return resolve(BrokersServiceInterface::class);
    }

    public function rules(): RulesServiceInterface
    {
        return resolve(RulesServiceInterface::class);
    }

    public function ingress(): IngressServiceInterface
    {
        return resolve(IngressServiceInterface::class);
    }

    /**
     * GET /health — plain JSON, no API envelope.
     *
     * @return array<string, mixed>
     */
    public function health(): array
    {
        $packet = new TransportPacket(
            endpoint: '/health',
            payload: [],
            metadata: [
                'method' => 'get',
                'raw' => true,
            ],
        );

        $result = $this->transport->send($packet);

        if ($result->isFailure()) {
            return [];
        }

        $data = $result->getData();

        return is_array($data) ? $data : [];
    }
}
