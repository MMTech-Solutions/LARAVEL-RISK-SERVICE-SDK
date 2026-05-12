<?php

declare(strict_types=1);

namespace MmtRiskSdk\TransportDrivers\Drivers\Http;

use MmtRiskSdk\TransportDrivers\Contracts\ActionResultInterface;
use MmtRiskSdk\WireHydration\WireHydrator;

class ResponseResult implements ActionResultInterface
{
    public function __construct(
        private readonly string $code,
        private readonly bool $success,
        private readonly mixed $data = null,
        private readonly ?string $message = null,
        private readonly mixed $errorDetails = null,
        private readonly string $rawResponse = ''
    ) {}

    public function getCode(): string
    {
        return $this->code;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function getData(?string $castToFqcn = null): mixed
    {
        if ($castToFqcn && isset($this->data)) {
            if (is_array($this->data) && array_is_list($this->data)) {
                return array_map(fn ($item) => new $castToFqcn(...$item), $this->data);
            }

            if (is_array($this->data)) {
                return new $castToFqcn(...$this->data);
            }
        }

        return $this->data;
    }

    public function getMappedData(?string $castToFqcn = null): mixed
    {
        if ($castToFqcn === null) {
            return $this->data;
        }

        if ($this->data === null) {
            return null;
        }

        return (new WireHydrator)->hydrate($this->data, $castToFqcn);
    }

    public function isSuccess(): bool
    {
        return $this->success;
    }

    public function isFailure(): bool
    {
        return ! $this->success;
    }

    public function isError(): bool
    {
        return ! $this->success;
    }

    public function getErrorDetails(): mixed
    {
        return $this->errorDetails;
    }

    public function getRawResponse(): string
    {
        return $this->rawResponse;
    }

    public static function fromSuccessResponse(string $rawResponse): ActionResultInterface
    {
        $decoded = json_decode($rawResponse, true, 512, JSON_THROW_ON_ERROR);
        $code = (string) ($decoded['code'] ?? '');
        $success = $code === 'OK';

        return new self(
            code: $code,
            message: $decoded['message'] ?? null,
            data: $decoded['data'] ?? null,
            success: $success,
            errorDetails: $success ? null : ($decoded['data'] ?? $decoded['detail'] ?? null),
            rawResponse: $rawResponse,
        );
    }

    public static function fromErrorResponse(string $rawResponse): ActionResultInterface
    {
        $decoded = json_decode($rawResponse, true, 512, JSON_THROW_ON_ERROR);

        return new self(
            code: $decoded['code'] ?? 'NO_CODE',
            message: $decoded['message'] ?? null,
            success: false,
            errorDetails: $decoded['detail'] ?? $decoded['data'] ?? null,
            rawResponse: $rawResponse,
        );
    }

    public static function fromFatalError(string $message): ActionResultInterface
    {
        return new self(
            code: 'FATAL_ERROR',
            message: $message,
            success: false
        );
    }

    /**
     * Plain JSON success (no API envelope), used by {@see \MmtRiskSdk\RiskService::health()}.
     *
     * @return ActionResultInterface&static
     */
    public static function fromPlainJsonSuccess(string $rawResponse): ActionResultInterface
    {
        $decoded = json_decode($rawResponse, true, 512, JSON_THROW_ON_ERROR);

        return new self(
            code: 'OK',
            message: null,
            data: $decoded,
            success: true,
            rawResponse: $rawResponse,
        );
    }
}
