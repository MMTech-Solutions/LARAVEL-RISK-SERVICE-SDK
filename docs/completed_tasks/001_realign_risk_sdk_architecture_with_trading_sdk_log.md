# Log de tarea — [001] realign_risk_sdk_architecture_with_trading_sdk

- **Archivo de tarea:** `docs/tasks/001_realign_risk_sdk_architecture_with_trading_sdk.md`
- **Fecha de cierre:** 2026-05-12
- **Estado:** Cerrada (aceptada por el usuario)

**Nota:** Esta tarea afecta solo el paquete **`mmtech/mmt-risk-sdk`**. No se actualizó `docs/knowledge/` en **MMT-PropFirm** (fuera de alcance explícito de la tarea; la migración del consumidor es trabajo aparte).

## Resumen

Se reimplementó el SDK PHP del Risk Management Service en **v1.1.0** (etiqueta de release; cambios breaking respecto a 1.0.x) para alinearlo con la arquitectura de **`mmt/laravel-trading-service-sdk`**: transporte Guzzle (`TransportInterface` / `TransportPacket` / `ActionResultInterface` / `ResponseResult`), comandos tipados (`CommandInterface`), DTOs de salida con `WireHydration` (`getMappedData` / `getData`), punto de entrada `RiskService` y servicios por dominio (`Accounts`, `Brokers`, `Rules`, `Ingress`). Se eliminaron el cliente legacy, facade, tests y `docs/specs/`. Config reducida a `RISK_SERVICE_URL`. Se refrescó `openapi.json` desde el servicio vivo.

## Archivos creados

| Ruta | Descripción |
|------|-------------|
| `src/MmtRiskSdkServiceProvider.php` | Provider Laravel (raíz `src/`, espejo Trading). |
| `src/RiskService.php` | Entry point singleton + `health()`. |
| `src/Contracts/CommandInterface.php` | Contrato de comandos. |
| `src/Exceptions/RiskServiceRequestException.php` | Excepción de transporte (análoga a Trading). |
| `src/TransportDrivers/**` | Contratos + `RiskServiceHttpClient` + `ResponseResult`. |
| `src/WireHydration/**` | `WireMapped` + `WireHydrator`. |
| `src/Domains/Accounts/**` | `AccountsService`, interfaz, commands, object responses. |
| `src/Domains/Brokers/**` | `BrokersService`, interfaz, commands, object responses. |
| `src/Domains/Rules/**` | `RulesService`, interfaz, commands, `RuleResponseItem`. |
| `src/Domains/Ingress/**` | `IngressService`, interfaz, `IngressEventCommand`. |
| `docs/tasks/001_realign_risk_sdk_architecture_with_trading_sdk.md` | Archivo de tarea (ya existía en el flujo; queda como referencia). |

## Archivos modificados

| Ruta | Descripción de cambios |
|------|-------------------------|
| `composer.json` | PHP ^8.3, Guzzle, sin `illuminate/http`, sin PHPUnit/autoload-dev, provider y aliases actualizados, versión publicada **1.1.0**. |
| `composer.lock` | Ignorado por `.gitignore`; regenerado localmente con `composer update`. |
| `config/mmt-risk-sdk.php` | Solo `base_url` → `env('RISK_SERVICE_URL')`. |
| `.env.example` | Solo `RISK_SERVICE_URL`. |
| `README.md` | Uso con `RiskService`, `getMappedData` / `getData`, requisitos nuevos. |
| `CHANGELOG.md` | Entrada **1.1.0** con breaking changes. |
| `openapi.json` | Snapshot refrescado desde el OpenAPI vivo del servicio. |

## Archivos eliminados (si aplica)

| Ruta | Motivo |
|------|--------|
| `src/RiskRestClient.php`, `src/RiskApiError.php` | Reemplazados por transport + `ResponseResult`. |
| `src/Api/*.php`, `src/Support/*.php`, `src/Laravel/**` | Arquitectura nueva por dominios y provider en raíz. |
| `tests/**`, `phpunit.xml` | Alineación con Trading SDK / política sin tests en el paquete. |
| `docs/specs/**` | Contrato vía OpenAPI + tarea; carpeta eliminada. |

## Decisiones y notas

- **`isSuccess()`**: basado en `code === "OK"` en respuestas con sobre (distinto del cliente Trading que marcaba éxito en todo 200 con cuerpo parseable).
- **`GET /health`**: transporte con `metadata['raw' => true]` y JSON plano en `data`.
- **`EvaluationHistoryByLoginResponseItem::by_login`**: mapa a listas de arrays; hidratación fila a fila puede hacerse en el consumidor si hace falta.
- **DTOs de integración en brokers**: `BrokerIntegrationInputItem` y relacionados viven bajo `ObjectResponses` y se reutilizan en `CreateBrokerCommand` / `UpdateBrokerCommand` para el shape JSON.
- **Consumidores (p. ej. MMT-PropFirm)**: no migrados en esta tarea; requerirán `^1.1` y sustituir facade / `RiskRestClient` por `app(RiskService::class)`.

## Comandos / verificaciones realizadas

- `composer validate --strict` — aviso por campo `version` en `composer.json` (recomendación Packagist).
- `composer update` — lock y vendor alineados con nuevas dependencias.
- `php -l` sobre todos los `src/**/*.php` — sin errores de sintaxis.
- `curl` OpenAPI vivo → `openapi.json`.
- `composer test` — no (tests eliminados por diseño).
- `composer lint` / `composer stan` — no ejecutados.

## Commit sugerido o realizado

- Sugerido: `feat: publicar mmt-risk-sdk 1.1.0 (arquitectura tipo Trading SDK)`
