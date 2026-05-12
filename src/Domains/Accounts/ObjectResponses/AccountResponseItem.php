<?php

declare(strict_types=1);

namespace MmtRiskSdk\Domains\Accounts\ObjectResponses;

use MmtRiskSdk\WireHydration\Attributes\WireMapped;

#[WireMapped]
final class AccountResponseItem
{
    public string $id;

    public string $login;

    public string $broker_id;

    public ?string $risk_profile_id = null;

    public ?float $opening_equity = null;

    public ?float $opening_balance = null;

    public ?float $daily_loss_limit_pct = null;

    public ?float $breakeven_band_pct = null;

    public bool $is_blocked;

    public ?string $blocked_at = null;

    public ?string $blocked_reason = null;

    public string $created_at;

    public string $updated_at;

    public ?Mt5PlatformUserSnapshotItem $mt5_platform_user = null;

    public ?int $current_win_streak = null;

    public ?int $current_loss_streak = null;

    public ?int $max_win_streak = null;

    public ?int $max_loss_streak = null;

    public ?float $running_equity_peak = null;

    public ?float $max_drawdown_abs = null;

    public ?float $max_drawdown_pct = null;
}
