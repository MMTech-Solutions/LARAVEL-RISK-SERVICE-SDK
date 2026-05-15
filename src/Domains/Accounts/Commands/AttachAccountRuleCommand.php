<?php

declare(strict_types=1);

namespace MmtRiskSdk\Domains\Accounts\Commands;

use MmtRiskSdk\Contracts\CommandInterface;

/**
 * Request body for POST /accounts/{account_id}/rules.
 *
 * OpenAPI schema: AccountRuleAssignRequest.
 */
final class AttachAccountRuleCommand implements CommandInterface
{
    public function __construct(
        public string $rule_id,
    ) {}

    /**
     * @return array<string, string>
     */
    public function toArray(): array
    {
        return [
            'rule_id' => $this->rule_id,
        ];
    }
}
