# mmt-risk-sdk (PHP)

HTTP SDK for the **MMT Risk Management Service** REST API. Architecture mirrors **`mmt/laravel-trading-service-sdk`**: Guzzle transport, typed **Commands**, **`ActionResultInterface`** envelope parsing (`{ "code", "message", "data" }`), and optional **`WireHydration`** via `getMappedData(FQCN::class)`.

- **HTTP**: `guzzlehttp/guzzle` (direct `Client`, same transport style as the Trading SDK).
- **Laravel**: auto-discovery for `MmtRiskSdk\MmtRiskSdkServiceProvider`, publishable config.
- **Entry point**: `MmtRiskSdk\RiskService` (`accounts()`, `brokers()`, `rules()`, `ingress()`, `health()`).
- **Contract**: OpenAPI snapshot at repo root — **`openapi.json`**.

## Requirements

- PHP **8.3+**
- `guzzlehttp/guzzle` **^7.2**
- `illuminate/support` **^11 | ^12 | ^13**

## Install

Package name: **`mmtech/mmt-risk-sdk`**.

```bash
composer require mmtech/mmt-risk-sdk:^1.2
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
```

Use **`getData(FQCN::class)`** for flat list/object constructor spread (same as Trading SDK), and **`getMappedData(FQCN::class)`** when nested DTOs or `#[WireMapped]` shapes are needed.

## OpenAPI

Regenerate the snapshot when the service contract changes:

```bash
curl -sS "http://<host>:<port>/openapi.json" -o openapi.json
```

## License

Proprietary — see **`LICENSE`** and `composer.json` (`license`: `proprietary`).
