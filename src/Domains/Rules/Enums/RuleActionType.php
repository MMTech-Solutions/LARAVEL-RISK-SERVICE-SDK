<?php

declare(strict_types=1);

namespace MmtRiskSdk\Domains\Rules\Enums;

/**
 * Side effects executed when a rule matches (Risk API rule.actions).
 */
enum RuleActionType: string
{
    case CloseAllPositions = 'close_all_positions';
    case DisableTrading = 'disable_trading';
}
