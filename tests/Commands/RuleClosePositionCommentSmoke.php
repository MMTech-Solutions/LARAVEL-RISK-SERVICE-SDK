<?php

declare(strict_types=1);

require __DIR__.'/../../../../../vendor/autoload.php';

use MmtRiskSdk\Domains\Rules\Commands\CreateRuleCommand;
use MmtRiskSdk\Domains\Rules\Commands\UpdateRuleCommand;
use MmtRiskSdk\Domains\Rules\RuleActionInputItem;

$create = new CreateRuleCommand(
    conditions: [['type' => 'comparison', 'metric' => 'return_equity_pct_day', 'operator' => '<=', 'value' => -2]],
    name: 'Risk Guardian #x',
    actions: [RuleActionInputItem::closeAllPositions()],
    close_position_comment: 'Loss limit - Rule #7',
);
assert($create->toArray()['close_position_comment'] === 'Loss limit - Rule #7');
assert(! array_key_exists('close_position_comment', (new CreateRuleCommand(conditions: []))->toArray()));

$update = new UpdateRuleCommand(conditions: [], actions: [], close_position_comment: 'Profit limit - Rule #7');
assert($update->toArray()['close_position_comment'] === 'Profit limit - Rule #7');

echo "OK\n";
