<?php

declare(strict_types=1);

namespace MmtRiskSdk\Domains\Rules;

/**
 * Serializes typed rule actions for Risk API request bodies.
 */
final class RuleActionPayloadBuilder
{
    /**
     * @param  list<RuleActionInputItem>  $actions
     * @return list<array{type: string, duration_ms?: int}>
     */
    public static function toPayloadList(array $actions): array
    {
        return array_map(
            static fn (RuleActionInputItem $action): array => $action->toArray(),
            $actions,
        );
    }
}
