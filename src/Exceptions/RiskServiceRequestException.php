<?php

declare(strict_types=1);

namespace MmtRiskSdk\Exceptions;

use GuzzleHttp\Exception\GuzzleException;
use RuntimeException;

final class RiskServiceRequestException extends RuntimeException
{
    public static function fromGuzzle(GuzzleException $e): self
    {
        return new self(
            message: 'RiskService HTTP request failed: '.$e->getMessage(),
            code: $e->getCode(),
            previous: $e,
        );
    }
}
