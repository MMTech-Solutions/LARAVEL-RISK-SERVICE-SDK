# Changelog

All notable changes to this project are documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [1.2.0] - 2026-05-15

### Breaking

- **`AccountRulesBulkRemovedItem` removed** — use **`AccountRulesClearedResponseItem`** (aligns with OpenAPI `AccountRulesClearedResponse` and `*ResponseItem` naming in this package).

### Changed

- `AttachAccountRuleCommand`: documented as OpenAPI `AccountRuleAssignRequest`.
- **`composer.json`**: removed inline `version` (Packagist uses Git tags); added `homepage` and `support`.

## [1.1.2] - 2026-05-15

### Added

- **Accounts**: `attachAccountRule` — `POST /accounts/{account_id}/rules` with `AttachAccountRuleCommand` (`rule_id`); map success `data` with `AccountRuleMembershipItem`.
- **Accounts**: `detachAccountRule` — `DELETE /accounts/{account_id}/rules/{rule_id}`.
- **Accounts**: `detachAllAccountRules` — `DELETE /accounts/{account_id}/rules`; map success `data` with `AccountRulesBulkRemovedItem` (`removed` count).
- New types: `AttachAccountRuleCommand`, `AccountRulesBulkRemovedItem`.

Regenerate **`openapi.json`** from the Risk service when the contract is published (see README).

## [1.1.1] - 2026-05-12

### Fixed

- `RiskServiceHttpClient`: read `mmt-risk-sdk.base_url` via `Illuminate\Support\Facades\Config` so the base URI resolves correctly under the SDK namespace and static analysis recognizes the call.

## [1.1.0] - 2026-05-12

Publicación en Packagist con etiqueta **1.1.0**. Incluye la realineación arquitectónica (cambios incompatibles con **1.0.x**).

### Breaking

- Replaced `RiskRestClient`, `MmtRiskSdk\Api\*`, and Laravel **`MmtRisk`** facade with **`RiskService`** plus domain services resolved from the container (`accounts()`, `brokers()`, `rules()`, `ingress()`).
- Configuration is now a single key **`base_url`** backed by **`RISK_SERVICE_URL`** (removed API token, configurable timeout, and extra default headers from package config).
- Dropped **`illuminate/http`**; HTTP is performed with **Guzzle** directly, aligned with **`mmt/laravel-trading-service-sdk`** transport.
- Raised minimum PHP to **8.3**.
- Removed **`tests/`**, **`phpunit`**, and **`docs/specs/`** from the package (parity with Trading SDK packaging).

### Added

- `TransportInterface` / `TransportPacket` / `ActionResultInterface` + `RiskServiceHttpClient` + `ResponseResult` (envelope parsing; `code === "OK"` drives `isSuccess()`).
- `WireHydration` (`WireMapped`, `WireHydrator`) for `getMappedData()`.
- Typed **Commands** for POST/PATCH bodies and **ObjectResponses** for OpenAPI-shaped payloads.
- `RiskService::health(): array` for plain JSON **`GET /health`** (transport `metadata.raw`).

## [1.0.0] - 2026-05-09

### Added

- Initial **1.x** line (`RiskRestClient`, domain `Api` classes, Laravel facade, Laravel HTTP client).
