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
        public ?string $resume_cron = null,
    ) {}

    public static function closeAllPositions(): self
    {
        return new self(RuleActionType::CloseAllPositions);
    }

    /**
     * Disable trading for a fixed duration (milliseconds).
     */
    public static function disableTrading(int $durationMs): self
    {
        return new self(RuleActionType::DisableTrading, duration_ms: $durationMs);
    }

    /**
     * Disable trading until the next UTC five-field cron tick (Risk API resume_cron).
     */
    public static function disableTradingUntilCron(string $resumeCron): self
    {
        return new self(RuleActionType::DisableTrading, resume_cron: $resumeCron);
    }

    /**
     * @return array{type: string, duration_ms?: int, resume_cron?: string}
     */
    public function toArray(): array
    {
        $payload = ['type' => $this->type->value];

        if ($this->type === RuleActionType::DisableTrading) {
            $hasDuration = $this->duration_ms !== null;
            $hasResumeCron = $this->resume_cron !== null && $this->resume_cron !== '';

            if (! $hasDuration && ! $hasResumeCron) {
                throw new InvalidArgumentException(
                    'disable_trading action requires duration_ms or resume_cron.',
                );
            }

            if ($hasDuration) {
                $payload['duration_ms'] = $this->duration_ms;
            }

            if ($hasResumeCron) {
                $payload['resume_cron'] = $this->resume_cron;
            }
        }

        return $payload;
    }
}
