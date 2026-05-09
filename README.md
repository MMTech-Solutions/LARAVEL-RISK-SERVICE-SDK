# mmt-risk-sdk (PHP)

HTTP client for the **MMT Risk Management Service** REST API. Same envelope pattern as **`mmtech/mmt-trading-sdk`**: successful responses use `{ "code": "OK", "data": ... }` and the SDK returns only **`data`**. **`GET /health`** is plain JSON (no envelope).

- **HTTP**: Laravel HTTP client (`Illuminate\Http\Client\Factory`), Guzzle underneath.
- **Laravel**: package discovery for `MmtRiskSdkServiceProvider`, publishable config, optional facade **`MmtRisk`**.
- **Core**: `RiskRestClient::envelopeRequest`, `health()`, domain APIs on **`ingress`**, **`rules`**, **`accounts`**, **`brokers`**.
- **Contract**: OpenAPI snapshot at repo root — **`openapi.json`** (refresh from a running service with `curl -sS "{base}/openapi.json" -o openapi.json` when the API changes).

## Requirements

- PHP **8.2+**
- `illuminate/http` and `illuminate/support` **^11 | ^12 | ^13**

## Install

```bash
composer require mmtech/mmt-risk-sdk
```

VCS path (example):

```bash
composer require mmtech/mmt-risk-sdk:dev-main
```

## Standalone (no Laravel)

```php
<?php

use Illuminate\Http\Client\Factory;
use MmtRiskSdk\RiskRestClient;

$http = new Factory();
$client = RiskRestClient::fromEnvironment($http);

$health = $client->health();
$rules = $client->rules->listRules();
```

Environment variables: see below (`MMT_RISK_*`).

## Laravel

1. After `composer require`, the provider is registered via `extra.laravel.providers`.
2. Publish config (optional):

   ```bash
   php artisan vendor:publish --tag=mmt-risk-sdk-config
   ```

3. Configure `.env` using `.env.example` from this package.

4. Inject `MmtRiskSdk\RiskRestClient` or use the facade:

   ```php
   use MmtRiskSdk\Laravel\Facades\MmtRisk;

   $rows = MmtRisk::accounts()->listAccounts();
   ```

## Environment variables

| Variable | Description |
|----------|-------------|
| `MMT_RISK_API_BASE_URL` | Base URL (no trailing slash); default `http://127.0.0.1:6051` |
| `MMT_RISK_API_TOKEN` | Optional Bearer token |
| `MMT_RISK_HTTP_TIMEOUT` | Seconds (float) |

Additional headers can be set in **`config/mmt-risk-sdk.php`** under **`headers`** (merged after defaults and `Authorization`).

## OpenAPI

The authoritative route list is **`openapi.json`** in the package root. Regenerate when the Risk service contract changes, then align **`AccountsApi`**, **`RulesApi`**, etc., if paths differ.

## Tests

```bash
composer install
composer test
```

Uses `Http::fake` only — no network calls.

## License

Proprietary — see `composer.json`.
