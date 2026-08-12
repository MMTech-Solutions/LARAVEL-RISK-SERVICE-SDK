<?php

declare(strict_types=1);

namespace MmtRiskSdk\Domains\Rules;

use InvalidArgumentException;
use MmtRiskSdk\Domains\Rules\Enums\RuleActionType;

/**
 * Single action in a rule create/update payload.
 */
final class RuleActionInputItem
{
    public function __construct(
        public RuleActionType $type,
        public ?int $duration_ms = null,
    ) {}

    public static function closeAllPositions(): self
    {
        return new self(RuleActionType::CloseAllPositions);
    }

    public static function disableTrading(int $durationMs): self
    {
        return new self(RuleActionType::DisableTrading, $durationMs);
    }

    /**
     * @return array{type: string, duration_ms?: int}
     */
    public function toArray(): array
    {
        $payload = ['type' => $this->type->value];

        if ($this->type === RuleActionType::DisableTrading) {
            if ($this->duration_ms === null) {
                throw new InvalidArgumentException('disable_trading action requires duration_ms.');
            }

            $payload['duration_ms'] = $this->duration_ms;
        }

        return $payload;
    }
}
