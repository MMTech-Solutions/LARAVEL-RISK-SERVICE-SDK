# Changelog

All notable changes to this project are documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [1.0.0] - 2026-05-09

### Added

- Initial stable release of **mmtech/mmt-risk-sdk**.
- `RiskRestClient` with envelope handling (`{ code, data, message }`), `health()`, and environment-based factory.
- Domain APIs: **ingress**, **rules**, **accounts**, **brokers** (`MmtRiskSdk\Api\*`).
- Laravel package: service provider, publishable config, optional **`MmtRisk`** facade.
- OpenAPI snapshot **`openapi.json`** at package root.
- PHPUnit tests using `Http::fake` (no network).
