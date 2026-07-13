# Changelog

All notable changes to this project are documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [3.7.1] - 2026-07-13

### Fixed

- **WireHydrator** — `@var string[]` / other builtin element types are no longer treated as object lists (fixes `PhaseMetricsEnrichmentResponseItem::$dates_utc` hydration; see [#1](https://github.com/MMTech-Solutions/LARAVEL-RISK-SERVICE-SDK/issues/1)).

## [3.7.0] - 2026-07-07

### Fixed

- **MT5 broker SDK config** — `BrokerSdkConfigAssembler::forMt5()` again accepts {@see BrokerMt5SdkConfigItem} with **`platform_server`**, **`platform_port`**, **`platform_login`** (Risk {@see BrokerSdkConfigInput}); legacy **`mt5_*`** keys are removed from payloads and DTOs (API rejects them as `extra_forbidden`).
- **`BrokerMt5SdkConfigItem`** — restored as the MT5/MT4 platform connection DTO using `platform_*` field names.

### Changed

- Regenerated **`openapi.json`** from Risk service at `http://198.244.253.112:6051`.

## [3.6.0] - 2026-07-07

### Breaking

- **`BrokerSdkConfigAssembler::forMt5()`** — accepts only {@see BrokerSdkCommonConfigItem}; no longer emits `mt5_server`, `mt5_port`, or `mt5_login` (Risk API rejects them when using trading-service `connection_id`).
- **`BrokerMt5SdkConfigItem`** — deprecated; kept for backward compatibility but unused by `forMt5()`.

### Added

- **`MmtRiskSdk\Support\RiskApiErrorMessageResolver`** — resolves human-readable messages from Risk API failures, including FastAPI validation `detail` arrays.

## [3.5.0] - 2026-06-22

### Breaking

- **`BrokerB2TraderSdkConfigItem`**: removed **`dss_ws_base_url`**; added **`platform_server`**, **`platform_port`**, **`platform_login`**, and all **`kafka_*`** constructor parameters (aligns with Risk broker `integration.sdk` B2Trader payload).
- **`BrokerSdkConfigInputItem` / `BrokerIntegrationSdkResolvedItem` / `BrokerSdkConfigAssembler`**: same field swap for B2Trader flat SDK config payloads.

## [3.4.0] - 2026-06-18

### Added

- **Accounts**: `ProvisionAccountCommand` now requires **`start_balance`** (flat opening equity/balance for the new account and first metric phase; OpenAPI `AccountProvisionCreate`).
- Regenerated **`openapi.json`** from Risk service at `http://68.178.205.211:6051`.

## [3.3.0] - 2026-06-17

### Added

- **MetricPhases**: `resetMetricPhase` — `POST /accounts/{account_id}/metric-phases/{phase_id}/reset` (in-place phase reset; map success `data` with `MetricPhaseResponseItem`).
- Regenerated **`openapi.json`** from Risk service at `http://68.178.205.211:6051`.

## [3.2.0] - 2026-06-12

### Added

- **Accounts**: `provisionAccount` — `POST /accounts/provision` with `ProvisionAccountCommand` (atomic account + metric phase + rules).
- New types: `ProvisionAccountCommand`, `ProvisionMetricPhaseCommand`, `ProvisionRuleCommand`, `AccountProvisionResponseItem`, `ProvisionMetricPhaseIdResponseItem`.
- Regenerated **`openapi.json`** from Risk service at `http://68.178.205.211:6051`.

## [3.1.0] - 2026-06-09

### Breaking

- **`BrokerB2TraderSdkConfigItem`**: removed `frontoffice_base_url`, `frontoffice_api_key`, and all `kafka_*` constructor parameters; added **`dss_ws_base_url`** (aligns with Trading Service B2T connect payload).
- **`BrokerSdkConfigInputItem` / `BrokerIntegrationSdkResolvedItem` / `BrokerSdkConfigAssembler`**: same field swap for B2Trader flat SDK config payloads.

## [3.0.0] - 2026-06-03

### Breaking

- **`RuleResponseItem`**: `account_ids` renamed to **`phase_ids`** (OpenAPI `RuleResponse`).
- **`CreateRuleCommand` / `UpdateRuleCommand`**: `accountIds` constructor parameter renamed to **`phaseIds`**; wire payload key **`phase_ids`** (replaces `account_ids`).

### Added

- **`MetricPhasesService`**: `listPhaseRuleMemberships`, `assignRuleToPhase`, `unassignRuleFromPhase` for `/accounts/{account_id}/metric-phases/{phase_id}/rules`.
- **`AssignPhaseRuleCommand`**, **`PhaseRuleMembershipItem`**.

### Changed

- **`WireHydrator`**: applies PHP property defaults when a wire key is missing on `#[WireMapped]` classes (e.g. empty `phase_ids` arrays).
- Regenerated **`openapi.json`** from Risk service at `http://68.178.205.211:6051`.

## [2.0.0] - 2026-05-31

### Breaking

- **Account-level metrics endpoints removed** — `listAccountMetricChanges`, `getAccountMetricsContext`, `getAccountMetricsEnrichment`, `getAccountMetricHistory`, and `getAccountMetricTradeTimeline` were removed from `AccountsServiceInterface`. Use **`RiskService::metricPhases()`** (`MetricPhasesServiceInterface`) for phase-scoped metrics instead.
- **`BrokerIntegrationKafkaResolvedItem`**: `topic_risk_notifications` replaced by **`topic_risk_events`** (aligns with OpenAPI).

### Added

- **`MetricPhases` domain** — `listMetricPhases`, `createMetricPhase`, `deleteMetricPhase`, `disableMetricPhase`, `listPhaseMetricChanges`, `getPhaseMetricsEnrichment`, `getPhaseMetricHistory`, `getPhaseMetricTradeTimeline` via `RiskService::metricPhases()`.
- **B2Trader broker SDK config** — `Domains/Brokers/Platforms/B2Trader/BrokerB2TraderSdkConfigItem` and assembler helpers for flat API payloads.
- **MT5 broker SDK config** — `Domains/Brokers/Platforms/MT5/BrokerMt5SdkConfigItem` (separated from B2Trader fields).
- **Shared broker builders** — `BrokerSdkConfigAssembler`, `BrokerIntegrationPayloadBuilder`, `BrokerSdkCommonConfigItem`.
- **`AccountResponseItem::opening_credit`** field.
- **`BrokerSdkConfigInputItem` / `BrokerIntegrationSdkResolvedItem`**: B2Trader fields (`keycloak_url`, `bbp_*`, `frontoffice_*`, `history_base_url`, `default_transfer_asset_id`, `kafka_*`).
- **`BrokerKafkaConfigInputItem::topic_risk_events`** (deprecated alias `topic_risk_notifications` retained for writes).
- **`Domains/Accounts/Platforms/MT5/Mt5PlatformUserSnapshotItem`** — MT5-specific snapshot; old `ObjectResponses` path kept as alias.

### Changed

- Regenerated **`openapi.json`** from live Risk service (metric-phases, B2Trader broker SDK fields).

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
