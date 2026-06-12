# mmt-risk-sdk (PHP)

HTTP SDK for the **MMT Risk Management Service** REST API. Architecture mirrors **`mmt/laravel-trading-service-sdk`**: Guzzle transport, typed **Commands**, **`ActionResultInterface`** envelope parsing (`{ "code", "message", "data" }`), and optional **`WireHydration`** via `getMappedData(FQCN::class)`.

- **HTTP**: `guzzlehttp/guzzle` (direct `Client`, same transport style as the Trading SDK).
- **Laravel**: auto-discovery for `MmtRiskSdk\MmtRiskSdkServiceProvider`, publishable config.
- **Entry point**: `MmtRiskSdk\RiskService` (`accounts()`, `brokers()`, `rules()`, `ingress()`, `metricPhases()`, `health()`).
- **Contract**: OpenAPI snapshot at repo root — **`openapi.json`**.

## Requirements

- PHP **8.3+**
- `guzzlehttp/guzzle` **^7.2**
- `illuminate/support` **^11 | ^12 | ^13**

## Install

Package name: **`mmtech/mmt-risk-sdk`**.

```bash
composer require mmtech/mmt-risk-sdk:^2.0
```

Release notes: **`CHANGELOG.md`**.

## Environment

| Variable | Description |
|----------|-------------|
| `RISK_SERVICE_URL` | Base URL of the Risk API (no trailing slash required; normalized internally). |

Publish config (optional):

```bash
php artisan vendor:publish --tag=mmt-risk-sdk-config
```

## Quick usage (Laravel)

```php
use MmtRiskSdk\Domains\Accounts\Commands\CreateAccountCommand;
use MmtRiskSdk\Domains\Accounts\ObjectResponses\AccountResponseItem;
use MmtRiskSdk\RiskService;

$risk = app(RiskService::class);

$result = $risk->accounts()->listAccounts();

if ($result->isSuccess()) {
    /** @var AccountResponseItem[] $rows */
    $rows = $result->getMappedData(AccountResponseItem::class);
}

$health = $risk->health(); // plain array JSON from GET /health

$created = $risk->accounts()->createAccount(
    new CreateAccountCommand(login: '1001', broker_id: $brokerUuid),
);

if ($created->isSuccess()) {
    $account = $created->getMappedData(AccountResponseItem::class);
}

use MmtRiskSdk\Domains\Accounts\Commands\ProvisionAccountCommand;
use MmtRiskSdk\Domains\Accounts\Commands\ProvisionMetricPhaseCommand;
use MmtRiskSdk\Domains\Accounts\Commands\ProvisionRuleCommand;
use MmtRiskSdk\Domains\Accounts\ObjectResponses\AccountProvisionResponseItem;

$provisioned = $risk->accounts()->provisionAccount(
    new ProvisionAccountCommand(
        login: '50123456',
        broker_id: $brokerUuid,
        metric_phases: [
            new ProvisionMetricPhaseCommand(
                name: 'Challenge',
                rules: [
                    new ProvisionRuleCommand(
                        conditions: [
                            [
                                'type' => 'comparison',
                                'metric' => 'return_equity_pct_day',
                                'operator' => '<=',
                                'value' => -5.0,
                            ],
                        ],
                        name: 'Max daily drawdown <= -5%',
                        description: 'Daily equity drawdown limit',
                    ),
                ],
            ),
        ],
    ),
);

if ($provisioned->isSuccess()) {
    $payload = $provisioned->getMappedData(AccountProvisionResponseItem::class);
}
```

Use **`getData(FQCN::class)`** for flat list/object constructor spread (same as Trading SDK), and **`getMappedData(FQCN::class)`** when nested DTOs or `#[WireMapped]` shapes are needed.

## OpenAPI

Regenerate the snapshot when the service contract changes:

```bash
curl -sS "http://<host>:<port>/openapi.json" -o openapi.json
```

## License

Proprietary — see **`LICENSE`** and `composer.json` (`license`: `proprietary`).
