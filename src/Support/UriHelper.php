<?php

declare(strict_types=1);

namespace MmtRiskSdk\Support;

/**
 * Encode a single path segment (UUID, login, symbol).
 */
final class UriHelper
{
    public static function pathSegment(string|int $value): string
    {
        return rawurlencode((string) $value);
    }

    /**
     * Strip slashes; empty falls back to default (e.g. v1).
     */
    public static function normApiSegment(string $value, string $default): string
    {
        $s = trim($value, " \t\n\r\0\x0B/");

        return $s !== '' ? $s : $default;
    }
}
