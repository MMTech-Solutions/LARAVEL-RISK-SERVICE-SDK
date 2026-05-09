<?php

declare(strict_types=1);

namespace MmtRiskSdk;

use RuntimeException;
use Throwable;

/**
 * API or HTTP error (envelope code != OK or transport failure).
 */
final class RiskApiError extends RuntimeException
{
    public function __construct(
        string $message,
        public readonly ?int $httpStatus = null,
        public readonly ?string $apiCode = null,
        public readonly mixed $payload = null,
        ?Throwable $previous = null,
    ) {
        parent::__construct($message, 0, $previous);
    }
}
