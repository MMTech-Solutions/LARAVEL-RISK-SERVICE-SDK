<?php

declare(strict_types=1);

namespace MmtRiskSdk\Support;

use JsonException;
use MmtRiskSdk\TransportDrivers\Contracts\ActionResultInterface;

/**
 * Extracts a human-readable error message from a Risk service API result.
 */
final class RiskApiErrorMessageResolver
{
    private const DEFAULT_MESSAGE = 'Risk service request failed.';

    public static function resolve(
        ActionResultInterface $result,
        string $fallback = self::DEFAULT_MESSAGE,
    ): string {
        $message = trim((string) ($result->getMessage() ?? ''));
        if ($message !== '') {
            return $message;
        }

        $fromRaw = self::resolveFromRawResponse($result->getRawResponse());
        if ($fromRaw !== null) {
            return $fromRaw;
        }

        $fromDetails = self::resolveFromDetails($result->getErrorDetails());
        if ($fromDetails !== null) {
            return $fromDetails;
        }

        $code = trim($result->getCode());

        return $code !== '' && $code !== 'NO_CODE' ? $code : $fallback;
    }

    private static function resolveFromRawResponse(string $rawResponse): ?string
    {
        $rawResponse = trim($rawResponse);
        if ($rawResponse === '') {
            return null;
        }

        try {
            /** @var array<string, mixed> $decoded */
            $decoded = json_decode($rawResponse, true, 512, JSON_THROW_ON_ERROR);
        } catch (JsonException) {
            return null;
        }

        if (isset($decoded['detail']) && is_array($decoded['detail'])) {
            return self::formatValidationDetail($decoded['detail']);
        }

        foreach (['message', 'detail', 'error'] as $key) {
            if (! isset($decoded[$key])) {
                continue;
            }

            $extracted = self::stringifyDetailValue($decoded[$key]);
            if ($extracted !== null) {
                return $extracted;
            }
        }

        return null;
    }

    private static function resolveFromDetails(mixed $details): ?string
    {
        if (is_string($details)) {
            $trimmed = trim($details);

            return $trimmed !== '' ? $trimmed : null;
        }

        if (! is_array($details)) {
            return null;
        }

        if (array_is_list($details)) {
            return self::formatValidationDetail($details);
        }

        foreach (['message', 'detail', 'msg', 'error', 'description'] as $key) {
            if (! isset($details[$key])) {
                continue;
            }

            $extracted = self::stringifyDetailValue($details[$key]);
            if ($extracted !== null) {
                return $extracted;
            }
        }

        return null;
    }

    /**
     * @param  array<int, mixed>  $detail
     */
    private static function formatValidationDetail(array $detail): ?string
    {
        $parts = [];

        foreach ($detail as $item) {
            if (! is_array($item)) {
                continue;
            }

            $itemMessage = trim((string) ($item['msg'] ?? ''));
            if ($itemMessage === '') {
                continue;
            }

            $location = isset($item['loc']) && is_array($item['loc'])
                ? implode('.', $item['loc'])
                : '';

            $parts[] = $location !== '' ? "{$location}: {$itemMessage}" : $itemMessage;
        }

        return $parts !== [] ? implode('; ', $parts) : null;
    }

    private static function stringifyDetailValue(mixed $value): ?string
    {
        if (is_string($value)) {
            $trimmed = trim($value);

            return $trimmed !== '' ? $trimmed : null;
        }

        if (! is_array($value) || $value === []) {
            return null;
        }

        if (isset($value['msg']) && is_string($value['msg'])) {
            $trimmed = trim($value['msg']);

            return $trimmed !== '' ? $trimmed : null;
        }

        if (array_is_list($value)) {
            return self::formatValidationDetail($value);
        }

        return null;
    }
}
