<?php

declare(strict_types=1);

namespace MmtRiskSdk\Support;

use MmtRiskSdk\RiskApiError;

/**
 * Parse JSON envelope `{ code, message, data }`; return data when code == OK.
 */
final class Envelope
{
    /**
     * @param  array<string, mixed>|mixed  $body
     * @return mixed
     */
    public static function dataOrRaise(
        mixed $body,
        bool $httpOk,
        int $httpStatus,
        string $url,
        string $reason = '',
    ): mixed {
        if (! is_array($body)) {
            if (! $httpOk) {
                self::raiseTransport($url, $httpStatus, $reason, $body);
            }
            throw new RiskApiError(sprintf('Expected JSON object from %s, got %s', $url, get_debug_type($body)));
        }

        $code = $body['code'] ?? null;
        if ($code !== 'OK') {
            $rawMsg = $body['message'] ?? null;
            $errText = is_string($rawMsg) && trim($rawMsg) !== ''
                ? trim($rawMsg)
                : (is_string($code) || is_int($code) ? (string) $code : 'API error');
            throw new RiskApiError(
                $errText,
                httpStatus: $httpStatus,
                apiCode: is_string($code) || is_int($code) ? (string) $code : null,
                payload: $body['data'] ?? null,
            );
        }

        if (! $httpOk) {
            self::raiseTransport($url, $httpStatus, $reason, $body);
        }

        return $body['data'] ?? null;
    }

    private static function raiseTransport(string $url, int $httpStatus, string $reason, mixed $detail): never
    {
        $msg = sprintf('%d %s for %s: %s', $httpStatus, $reason, $url, json_encode($detail, JSON_UNESCAPED_UNICODE));
        throw new RiskApiError($msg, httpStatus: $httpStatus, payload: $detail);
    }
}
