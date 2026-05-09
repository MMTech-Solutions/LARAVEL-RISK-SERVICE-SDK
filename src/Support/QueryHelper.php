<?php

declare(strict_types=1);

namespace MmtRiskSdk\Support;

/**
 * Drop null-valued keys so optional OpenAPI query params are omitted entirely.
 *
 * @param  array<string, mixed>  $query
 * @return array<string, mixed>
 */
final class QueryHelper
{
    public static function omitNull(array $query): array
    {
        return array_filter($query, static fn (mixed $v): bool => $v !== null);
    }
}
