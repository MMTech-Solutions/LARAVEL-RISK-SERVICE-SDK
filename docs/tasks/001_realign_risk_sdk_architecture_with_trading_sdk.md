# 001 — Realinear `mmtech/mmt-risk-sdk` con la arquitectura del Trading SDK

> Plantilla base: [`MMT-PropFirm/docs/templates/task_template.md`](../../../MMT-PropFirm/docs/templates/task_template.md). Adaptada al contexto de un SDK (no microservicio Laravel).

---

## 1. Resumen de la tarea

### Archivo de la tarea (obligatorio)
- **Carpeta:** `docs/tasks/` (de este repo, `MMT-RISK-SERVICE-LARAVEL-SDK`).
- **Nombre del archivo:** `001_realign_risk_sdk_architecture_with_trading_sdk.md` (primer archivo en la carpeta).

### Título
**Título:** Realinear `mmtech/mmt-risk-sdk` con la arquitectura de `mmt/laravel-trading-service-sdk` (Transport/Commands/ObjectResponses/WireHydration).

### Objetivo
Rehacer el SDK PHP del Risk Management Service para que comparta **exactamente** la misma arquitectura interna que `LARAVEL-TRADING-SERVICE-SDK`:

- Transporte abstracto (`TransportInterface` + `TransportPacket` + `ActionResultInterface`) sobre **Guzzle directo**.
- **Commands** tipados con `CommandInterface::toArray()` para cuerpos POST/PATCH.
- **ObjectResponses** tipados por endpoint según el OpenAPI; hidratación opcional vía `getData(FQCN::class)` y `getMappedData()` con `WireHydration`.
- Entry point único `RiskService` con métodos por dominio (`accounts()`, `brokers()`, `rules()`, `ingress()`, `health()`) que resuelven `XxxServiceInterface` vía contenedor.

El consumo desde MMT-PropFirm y otros consumidores cambia de `MmtRisk::accounts()->listAccounts()` (envolvente `mixed`) a `app(RiskService::class)->accounts()->listAccounts()->getData(AccountResponseItem::class)` (envolvente tipado). Se trata como **breaking change** → versión **2.0.0**.

---

## 2. Análisis del proyecto y estado actual

### Tecnología y arquitectura objetivo (espejo del Trading SDK)

- **PHP:** `^8.3` (alineado con Trading SDK; sube desde `^8.2`).
- **Dependencias prod:** `guzzlehttp/guzzle ^7.2`. Mantener `illuminate/support` para el `ServiceProvider` (`^11|^12|^13`). **Eliminar** `illuminate/http`.
- **Patrón:** Transport con `send(TransportPacket): ActionResultInterface`; cada operación de dominio empaqueta `method`/`endpoint`/`payload`/`metadata` y delega en el transporte.
- **Sin sesión / connection_id**: Risk usa una única `base_url`. No replicamos `BrokerSession`. En su lugar, `RiskService` es el aglutinador y cada `XxxService` (dominio) tiene la URL base de su recurso.

### Estado actual (a remover/transformar)

| Hoy | Acción |
|-----|--------|
| `src/RiskRestClient.php` (envelope + sub-APIs como `public readonly`) | **Eliminar**. Reemplaza `RiskService` + `XxxService`/`XxxServiceInterface`. |
| `src/Api/AccountsApi.php`, `BrokersApi.php`, `RulesApi.php`, `InternalIngressApi.php` | **Eliminar** y reemplazar por `src/Domains/<Domain>/<Domain>Service.php` + `Contracts/<Domain>ServiceInterface.php`. |
| `src/Support/Envelope.php`, `UriHelper.php`, `QueryHelper.php` | **Eliminar**. La semántica de envelope vive en `ResponseResult`; el `rawurlencode` se replica en cada Service como en `MT5TradingService::encodePathSegment`; el `omitNull` se aplica vía `array_filter(...)` en `Command::toArray()` o en el método del Service cuando aplique. |
| `src/Laravel/MmtRiskSdkServiceProvider.php` | **Mover** a `src/MmtRiskSdkServiceProvider.php` (raíz de `src/`, como Trading). |
| `src/Laravel/Facades/MmtRisk.php` | **Eliminar** (Trading SDK no tiene Facade; sin BC). |
| `src/RiskApiError.php` | **Eliminar**. Los errores del envelope se reportan vía `ActionResultInterface::isFailure()` + `getErrorDetails()`. Se conservará una excepción de transporte equivalente a `TradingServiceRequestException` para fallos de conexión irrecuperables. |
| `config/mmt-risk-sdk.php` (con `api_token`, `default_timeout`, `headers`) | **Reescribir** a solo `'base_url' => env('RISK_SERVICE_URL', '')` (espejo de Trading). |
| `.env.example` (3 vars) | Reducir a `RISK_SERVICE_URL=...`. |
| `tests/` | **Eliminar** (espejo Trading, sin tests; aplica la regla del repo de no introducir tests por iniciativa). |
| `docs/specs/` (`requirements.md`, `design.md`, `tasks.md`) | **Eliminar** (espejo Trading; el contrato es el OpenAPI + esta tarea). |
| `openapi.json` (snapshot raíz) | **Conservar y refrescar**. Será paso 1 de la ejecución. |
| `CHANGELOG.md` | Actualizar con entrada `2.0.0`. |

---

## 3. Contexto y definición del problema

### Reglas y docs aplicables

- **Referencia arquitectónica:** `LARAVEL-TRADING-SERVICE-SDK/src/**` (espejo exacto salvo donde se indique). En particular:
  - `TradingServiceSdkServiceProvider.php`
  - `Contracts/CommandInterface.php`
  - `TransportDrivers/{Contracts,Drivers/Http}/*`
  - `WireHydration/*`
  - `Platforms/MT5/Contracts/MT5TradingServiceInterface.php` y `MT5TradingService.php` como modelo de un Service de dominio
  - Patrón `Commands/*` y `ObjectResponses/*`
- **Contrato API:** `openapi.json` (snapshot) + `http://68.178.205.211:6051/openapi.json` (vivo).
- **Reglas de comentarios:** comentarios y docblocks de PHP **en inglés** (regla workspace `comments-english.mdc`).

### Enunciado del problema

Hoy el Risk SDK y el Trading SDK siguen patrones divergentes (envelope crudo vs envelope tipado, sin Commands vs Commands obligatorios, sin DTOs de salida vs DTOs con hidratación). Esto:

- Obliga a cada consumidor a manejar dos estilos distintos para dos SDKs internos.
- Dificulta refactors mecánicos en MMT-PropFirm (no se puede aplicar el patrón `->getData(FQCN::class)` uniforme).
- Impide compartir utilidades (`WireHydrator`, transporte, envoltorios) entre SDKs.

### Criterios de éxito

- [ ] `composer.json` actualizado: PHP `^8.3`, `guzzlehttp/guzzle ^7.2`, `illuminate/support ^11|^12|^13`, sin `illuminate/http`. Versión `2.0.0`. Provider en `Mmtech\MmtRiskSdk\MmtRiskSdkServiceProvider` (mantiene namespace `MmtRiskSdk\`; nota: paquete `mmtech/mmt-risk-sdk` se conserva).
- [ ] `config/mmt-risk-sdk.php` reducido a una sola clave `base_url` con env `RISK_SERVICE_URL`.
- [ ] `.env.example` reducido a `RISK_SERVICE_URL=http://68.178.205.211:6051`.
- [ ] Estructura `src/` final (ver § 6) presente, sin archivos legacy de la lista de "Eliminar".
- [ ] `RiskService` resoluble vía `app(RiskService::class)` con métodos: `accounts()`, `brokers()`, `rules()`, `ingress()`, `health()`.
- [ ] Las 4 interfaces de dominio (`AccountsServiceInterface`, `BrokersServiceInterface`, `RulesServiceInterface`, `IngressServiceInterface`) bindeadas con `$this->app->bind(...)` (no singleton, igual que Trading lo hace para MT5).
- [ ] 33 endpoints cubiertos 1:1 con la lista del § 7 (todos los métodos llaman a `sendPacket(...)` interno y devuelven `ActionResultInterface`, excepto `RiskService::health(): array`).
- [ ] **Commands** creados para los 10 endpoints con request body o más de 3 query params (lista cerrada en § 7).
- [ ] **ObjectResponses** creados para cada schema de respuesta documentado en OpenAPI (lista cerrada en § 8); hidratables con `getData(FQCN::class)` o `getMappedData(FQCN::class)` (vía `WireHydration`).
- [ ] `CHANGELOG.md` con entrada `## [2.0.0]` listando los cambios breaking.
- [ ] `README.md` actualizado al patrón Trading (sección "Uso rápido" análoga).
- [ ] `docs/specs/` eliminado (o conservado solo el README de specs si lo prefiere el usuario tras revisión).
- [ ] `tests/` eliminado.
- [ ] No se introducen tests automatizados (alineación con Trading SDK y reglas del workspace).

---

## 4. Contexto del modo de desarrollo

- **Fase del proyecto:** rewrite mayor (v1.0.0 → v2.0.0).
- **Cambios breaking:** **sí**, asumidos por el usuario. No hay capa de compatibilidad.
- **Datos:** no aplica (el SDK no persiste nada).
- **Usuarios afectados:** `MMT-PropFirm` y cualquier app que hoy importe `mmtech/mmt-risk-sdk:^1.0`. Tras release `2.0.0` deberán actualizar dependencia y migrar llamadas.
- **Prioridad:** estabilidad y paridad estricta con Trading SDK por encima de velocidad.

---

## 5. Decisiones tomadas (registro)

Decisiones acordadas con el usuario el **2026-05-12** antes de redactar esta tarea:

| Tema | Decisión |
|------|----------|
| Namespace / paquete Composer | Mantener `MmtRiskSdk\` y `mmtech/mmt-risk-sdk` (sin renombrar). |
| Env vars | **Solo** `RISK_SERVICE_URL` (eliminar token Bearer, timeout configurable y headers extra). |
| HTTP transport | **Guzzle directo** (`new GuzzleHttp\Client`). Eliminar `illuminate/http`; mantener `illuminate/support`. |
| Organización de dominios | `src/Domains/<Domain>/{Contracts,Commands,ObjectResponses,Enums}/...` (`Accounts`, `Brokers`, `Rules`, `Ingress`). |
| Entry point | Clase `RiskService` (singleton) con `accounts()` / `brokers()` / `rules()` / `ingress()` / `health()` resolviendo cada `XxxServiceInterface` vía `resolve()`. |
| Commands | Solo para POST/PATCH con body (~10 Commands). GET con query simples siguen con parámetros nombrados. |
| ObjectResponses | Tipar **todas** las respuestas según OpenAPI (~30+ DTOs, incluidos nested). |
| Facade `MmtRisk` | **Eliminar**. Sin BC; versión 2.0.0. |
| `GET /health` | Método dedicado `RiskService::health(): array` (transport sin envelope; devuelve `array` decodificado tal cual). |
| `WireHydration` | Replicar **íntegro** (`Attributes/WireMapped` + `WireHydrator`); ambos `getData(FQCN)` y `getMappedData(FQCN)`. |
| `tests/` y `docs/specs/` | Eliminar ambos. |
| Ubicación archivo de tarea | `MMT-RISK-SERVICE-LARAVEL-SDK/docs/tasks/001_*.md` (este archivo). |
| OpenAPI source | Refrescar antes de planear (hecho el 2026-05-12: snapshot vivo coincide con el del repo, 33 endpoints). |
| Alcance ejecución | Solo creación del archivo de tarea + plan; **esperar confirmación** antes de tocar código. |
| Ubicación config / provider | `config/mmt-risk-sdk.php` se mantiene; provider se mueve a `src/MmtRiskSdkServiceProvider.php` (raíz de `src/`); clave de config `'mmt-risk-sdk'`. |

---

## 6. Estructura objetivo de `src/` (paridad con Trading SDK)

```
src/
├── MmtRiskSdkServiceProvider.php
├── Contracts/
│   └── CommandInterface.php                       # idéntico al Trading SDK
├── Enums/
│   └── (a definir conforme aparezcan enums en schemas — ver § 8 nota)
├── Exceptions/
│   └── RiskServiceRequestException.php            # equivalente a TradingServiceRequestException
├── TransportDrivers/
│   ├── Contracts/
│   │   ├── TransportInterface.php                 # copy 1:1
│   │   ├── TransportPacket.php                    # copy 1:1
│   │   └── ActionResultInterface.php              # copy 1:1
│   └── Drivers/
│       └── Http/
│           ├── RiskServiceHttpClient.php          # análogo a TradingServiceHttpClient
│           └── ResponseResult.php                 # análogo (parser envelope `{code,message,data}`)
├── WireHydration/
│   ├── Attributes/
│   │   └── WireMapped.php                         # copy 1:1
│   └── WireHydrator.php                           # copy 1:1
├── RiskService.php                                # entry point (singleton)
└── Domains/
    ├── Accounts/
    │   ├── Contracts/
    │   │   ├── AccountsServiceInterface.php
    │   │   └── AccountsService.php
    │   ├── Commands/
    │   │   ├── CreateAccountCommand.php
    │   │   ├── UpdateAccountCommand.php
    │   │   ├── EvaluationHistoryRangeCommand.php
    │   │   ├── EvaluationHistoryRecentCommand.php
    │   │   └── PatchAccountRuleMembershipCommand.php
    │   ├── Enums/                                 # (vacía salvo que aparezcan enums)
    │   └── ObjectResponses/                       # ver § 8 — listado completo
    ├── Brokers/
    │   ├── Contracts/{BrokersServiceInterface,BrokersService}.php
    │   ├── Commands/{CreateBrokerCommand,UpdateBrokerCommand}.php
    │   └── ObjectResponses/
    ├── Rules/
    │   ├── Contracts/{RulesServiceInterface,RulesService}.php
    │   ├── Commands/{CreateRuleCommand,UpdateRuleCommand}.php
    │   └── ObjectResponses/
    └── Ingress/
        ├── Contracts/{IngressServiceInterface,IngressService}.php
        ├── Commands/IngressEventCommand.php       # body es free-form `dict[str,Any]` — el Command envuelve el array para tipado
        └── ObjectResponses/                        # respuesta es `dict[str,Any]` — sin DTO; método devuelve mixed
```

**Notas:**

- La ruta `MT5TradingService::encodePathSegment(string $value): string` se replica como método `private` en cada `XxxService` que reciba IDs/logins por path (`rawurlencode`).
- El método interno `sendPacket(string $method, string $url, array $payload = [], array $metadata = []): ActionResultInterface` se replica privado en cada Service (idéntico al de `MT5TradingService`).
- Cada Service expone una constante `private string $baseUrl = '/<recurso>'` (ej. `'/accounts'`, `'/brokers'`, `'/rules'`, `'/internal/ingress'`).

---

## 7. Mapeo `endpoint → método del SDK`

Lista cerrada (33 endpoints). **PATH** = método del Service. **Cmd** indica si la operación requiere Command tipado. **Resp** indica la clase ObjectResponse para `getData(FQCN)` cuando aplique.

### 7.1 `AccountsService` (`baseUrl = '/accounts'`)

| HTTP | Path | Método | Firma | Cmd | Resp (Data) |
|------|------|--------|-------|-----|-------------|
| GET | `/accounts?broker_id=` | `listAccounts` | `(?string $brokerId = null): ActionResultInterface` | — | `AccountResponseItem[]` |
| POST | `/accounts` | `createAccount` | `(CommandInterface $command): ActionResultInterface` | `CreateAccountCommand` | `AccountResponseItem` |
| GET | `/accounts/by-login/{login}` | `getAccountByLogin` | `(string $login): ActionResultInterface` | — | `AccountResponseItem` |
| POST | `/accounts/evaluation-history/range` | `evaluationHistoryRange` | `(CommandInterface $command): ActionResultInterface` | `EvaluationHistoryRangeCommand` | `EvaluationHistoryByLoginResponseItem` |
| POST | `/accounts/evaluation-history/recent` | `evaluationHistoryRecent` | `(CommandInterface $command): ActionResultInterface` | `EvaluationHistoryRecentCommand` | `EvaluationHistoryByLoginResponseItem` |
| GET | `/accounts/page` | `listAccountsPage` | `(?string $brokerId, ?string $q, ?bool $isBlocked, ?string $sort, ?int $skip, ?int $take): ActionResultInterface` | — | `AccountListPageResponseItem` |
| GET | `/accounts/stats` | `accountStats` | `(?string $brokerId = null): ActionResultInterface` | — | `AccountBrokerScopeTotalsItem` |
| GET | `/accounts/{account_id}` | `getAccountById` | `(string $accountId): ActionResultInterface` | — | `AccountResponseItem` |
| PATCH | `/accounts/{account_id}` | `updateAccount` | `(string $accountId, CommandInterface $command): ActionResultInterface` | `UpdateAccountCommand` | `AccountResponseItem` |
| DELETE | `/accounts/{account_id}` | `deleteAccount` | `(string $accountId): ActionResultInterface` | — | `null` |
| GET | `/accounts/{account_id}/metric-changes` | `listAccountMetricChanges` | `(string $accountId, ?int $limit = null): ActionResultInterface` | — | `MetricChangeListResponseItem` |
| GET | `/accounts/{account_id}/metrics-context` | `getAccountMetricsContext` | `(string $accountId): ActionResultInterface` | — | `AccountMetricsContextResponseItem` |
| GET | `/accounts/{account_id}/metrics-enrichment` | `getAccountMetricsEnrichment` | `(string $accountId, ?int $days = null): ActionResultInterface` | — | `MetricsEnrichmentResponseItem` |
| GET | `/accounts/{account_id}/metrics/history` | `getAccountMetricHistory` | `(string $accountId, string $metricKey, string $fromUtc, string $toUtc, ?string $granularity, ?bool $onlyNonzeroDelta, ?float $minAbsDelta, ?string $sort, ?int $offset, ?int $limit): ActionResultInterface` | — | `MetricHistoryResponseItem` |
| GET | `/accounts/{account_id}/metrics/trade-timeline` | `getAccountMetricTradeTimeline` | `(string $accountId, string $metricKey, string $fromUtc, string $toUtc, ?int $offset, ?int $limit): ActionResultInterface` | — | `MetricTradeTimelineResponseItem` |
| GET | `/accounts/{account_id}/rule-memberships` | `listAccountRuleMemberships` | `(string $accountId): ActionResultInterface` | — | `AccountRuleMembershipItem[]` |
| POST | `/accounts/{account_id}/rules/{rule_id}/match-streak/reset` | `resetAccountRuleMatchStreak` | `(string $accountId, string $ruleId): ActionResultInterface` | — | `null` |
| PATCH | `/accounts/{account_id}/rules/{rule_id}/membership` | `patchAccountRuleMembership` | `(string $accountId, string $ruleId, CommandInterface $command): ActionResultInterface` | `PatchAccountRuleMembershipCommand` | `AccountRuleMembershipItem` |
| POST | `/accounts/{account_id}/sync-mt5-open-positions` | `syncMt5OpenPositions` | `(string $accountId): ActionResultInterface` | — | `array<string,int>` (`mixed`) |
| GET | `/accounts/{account_id}/trades/open` | `listAccountOpenTrades` | `(string $accountId): ActionResultInterface` | — | `OpenTradeRowItem[]` |

### 7.2 `BrokersService` (`baseUrl = '/brokers'`)

| HTTP | Path | Método | Firma | Cmd | Resp (Data) |
|------|------|--------|-------|-----|-------------|
| GET | `/brokers` | `listBrokers` | `(): ActionResultInterface` | — | `BrokerResponseItem[]` |
| POST | `/brokers` | `createBroker` | `(CommandInterface $command): ActionResultInterface` | `CreateBrokerCommand` | `BrokerResponseItem` |
| GET | `/brokers/{broker_id}` | `getBrokerById` | `(string $brokerId): ActionResultInterface` | — | `BrokerResponseItem` |
| PATCH | `/brokers/{broker_id}` | `updateBroker` | `(string $brokerId, CommandInterface $command): ActionResultInterface` | `UpdateBrokerCommand` | `BrokerResponseItem` |
| DELETE | `/brokers/{broker_id}` | `deleteBroker` | `(string $brokerId): ActionResultInterface` | — | `null` |

### 7.3 `RulesService` (`baseUrl = '/rules'`)

| HTTP | Path | Método | Firma | Cmd | Resp (Data) |
|------|------|--------|-------|-----|-------------|
| GET | `/rules` | `listRules` | `(?bool $activeOnly = null): ActionResultInterface` | — | `RuleResponseItem[]` |
| POST | `/rules` | `createRule` | `(CommandInterface $command): ActionResultInterface` | `CreateRuleCommand` | `RuleResponseItem` |
| GET | `/rules/active` | `listActiveRules` | `(): ActionResultInterface` | — | `RuleResponseItem[]` |
| GET | `/rules/{rule_id}` | `getRule` | `(string $ruleId): ActionResultInterface` | — | `RuleResponseItem` |
| PATCH | `/rules/{rule_id}` | `updateRule` | `(string $ruleId, CommandInterface $command): ActionResultInterface` | `UpdateRuleCommand` | `RuleResponseItem` |
| DELETE | `/rules/{rule_id}` | `deleteRule` | `(string $ruleId): ActionResultInterface` | — | `null` |

### 7.4 `IngressService` (`baseUrl = '/internal/ingress'`)

| HTTP | Path | Método | Firma | Cmd | Resp (Data) |
|------|------|--------|-------|-----|-------------|
| POST | `/internal/ingress/events` | `postEvent` | `(CommandInterface $command): ActionResultInterface` | `IngressEventCommand` (envuelve `array $payload`) | `array<string,mixed>` (`mixed`) |

### 7.5 `RiskService::health()` (especial — sin envelope)

`RiskService::health(): array` invoca el transporte con un `TransportPacket` cuyo metadata incluye una flag `'raw' => true`. `RiskServiceHttpClient` ante esa flag devuelve `array` parseado del JSON plano (sin pasar por `ResponseResult`). Alternativa equivalente: implementar `private function rawJson(string $uri): array` directamente en `RiskService` usando el mismo Guzzle client del transport (vía un getter o duplicando construcción).

> ⚠️ Pendiente de confirmar en fase de ejecución cuál variante se implementa. Default propuesto: flag `'raw'` en `TransportPacket->metadata`, el HttpClient devuelve `ActionResultInterface` cuyo `getData()` ya contiene el `array` decodificado plano. `RiskService::health()` lo desempaqueta y devuelve `array`. Esto evita meter una segunda ruta de retorno (`array` directo) en `TransportInterface::send()` que cambiaría su contrato.

---

## 8. ObjectResponses — listado cerrado a partir de schemas OpenAPI

Cada ObjectResponse es una `class final` con constructor promovido (`public readonly`), nombres de propiedad **idénticos** a las claves JSON del schema. Para listas anidadas se usa docblock `@var Xxx[]` que es lo que aprovecha `WireHydrator` (ver `Mmt\TradingServiceSdk\WireHydration\WireHydrator::arrayElementClassFromDocblock`).

### 8.1 Dominio `Accounts` (`src/Domains/Accounts/ObjectResponses/`)

- `AccountResponseItem` (← `AccountResponse`, 21 props)
- `AccountListPageResponseItem` (← `AccountListPageResponse`, 3 props) — contendrá `@var AccountResponseItem[]` para su lista de filas.
- `AccountBrokerScopeTotalsItem` (← `AccountBrokerScopeTotals`, 3 props)
- `AccountMetricsContextResponseItem` (← `AccountMetricsContextResponse`, 4 props)
- `EvaluationHistoryByLoginResponseItem` (← `EvaluationHistoryByLoginResponse`, 1 prop)
  - `RuleEvaluationHistoryItem` (12 props)
    - `RuleEvaluationSnapshotPayloadItem` (8 props)
      - `BoundRuleEvaluationPayloadItem` (6 props)
      - `MatchStreakItem` (2 props)
- `MetricChangeListResponseItem` (← `MetricChangeListResponse`, 1 prop)
  - `MetricChangeLogItem` (4 props)
    - `MetricChangeEntryItem` (3 props)
- `MetricsEnrichmentResponseItem` (← `MetricsEnrichmentResponse`, 4 props)
  - `DailyDeltaItem` (2 props)
- `MetricHistoryResponseItem` (← `MetricHistoryResponse`, 4 props)
  - `MetricHistoryPointItem` (3 props)
  - `MetricHistorySummaryItem` (11 props)
- `MetricTradeTimelineResponseItem` (← `MetricTradeTimelineResponse`, 6 props)
  - `MetricTradeTimelineRowItem` (6 props)
- `AccountRuleMembershipItem` (9 props; nombre se conserva del schema)
  - reutiliza `RuleMembershipSettingsResponseItem` (3 props)
  - reutiliza `RuleEvaluationSnapshotPayloadItem`
- `OpenTradeRowItem` (5 props)
  - `Mt5PlatformUserSnapshotItem` (16 props) — *referenciado por OpenTradeRow si aplica; se verifica al leer schema completo*.

### 8.2 Dominio `Brokers` (`src/Domains/Brokers/ObjectResponses/`)

- `BrokerResponseItem` (← `BrokerResponse`, 10 props)
  - `BrokerIntegrationResolvedItem` (3 props)
    - `BrokerIntegrationSdkResolvedItem` (6 props)
    - `BrokerIntegrationKafkaResolvedItem` (2 props)
    - `BrokerIntegrationIngressResolvedItem` (1 prop)
  - `BrokerIntegrationInputItem` (2 props) — `integration_overrides`
    - `BrokerSdkConfigInputItem` (6 props)
    - `BrokerKafkaConfigInputItem` (2 props)

### 8.3 Dominio `Rules` (`src/Domains/Rules/ObjectResponses/`)

- `RuleResponseItem` (← `RuleResponse`, 12 props). El schema `RuleCreate`/`RuleUpdate` se modela en Commands, no ObjectResponses.

### 8.4 Dominio `Ingress`

- Ninguno. Respuesta es `dict[str,Any]` → `getData()` devuelve `array` mixed.

### 8.5 Sufijo de nombrado

Trading SDK usa sufijo `Item` para los DTOs de salida (`UserItem`, `PositionItem`, `OrderItem`, etc.). Se mantiene `Item` para todos los ObjectResponses del Risk SDK. **Excepción visual:** `AccountRuleMembershipItem` ya termina en `Item` en el schema OpenAPI; se respeta tal cual. Para nombres como `AccountResponse` se renombra a `AccountResponseItem` (añadiendo `Item`) para diferenciar visualmente que son contenedores tipados del SDK y no entidades Eloquent.

> ⚠️ Pendiente de validar en ejecución: si el usuario prefiere conservar el nombre del schema (`AccountResponse` en vez de `AccountResponseItem`), se ajusta antes de comenzar. Default propuesto: añadir sufijo `Item` siempre, igual que Trading SDK.

---

## 9. Commands — listado cerrado

Cada Command es una `class` que `implements CommandInterface` con constructor promovido y `toArray(): array` (filtra null cuando aplique, igual que `CreateUserCommand::toArray()` del Trading SDK).

| Command | Dominio | Endpoint | Schema OpenAPI base |
|---------|---------|----------|----------------------|
| `CreateAccountCommand` | Accounts | `POST /accounts` | `AccountCreate` (5 props) |
| `UpdateAccountCommand` | Accounts | `PATCH /accounts/{id}` | `AccountUpdate` (7 props, todos opcionales) |
| `EvaluationHistoryRangeCommand` | Accounts | `POST /accounts/evaluation-history/range` | `EvaluationHistoryRangeRequest` (3 props) |
| `EvaluationHistoryRecentCommand` | Accounts | `POST /accounts/evaluation-history/recent` | `EvaluationHistoryRecentRequest` (2 props) |
| `PatchAccountRuleMembershipCommand` | Accounts | `PATCH /accounts/{id}/rules/{rid}/membership` | `RuleMembershipSettingsPatch` (2 props) |
| `CreateBrokerCommand` | Brokers | `POST /brokers` | `BrokerCreate` (4 props; `credentials` y `integration` anidados) |
| `UpdateBrokerCommand` | Brokers | `PATCH /brokers/{id}` | `BrokerUpdate` (4 props parciales) |
| `CreateRuleCommand` | Rules | `POST /rules` | `RuleCreate` (8 props) |
| `UpdateRuleCommand` | Rules | `PATCH /rules/{id}` | `RuleUpdate` (8 props parciales) |
| `IngressEventCommand` | Ingress | `POST /internal/ingress/events` | `dict[str,Any]` — el Command envuelve un único `public array $event` y `toArray()` lo devuelve tal cual. |

Reglas comunes:

- Propiedades obligatorias antes que opcionales en el constructor.
- Las propiedades opcionales se omiten del `toArray()` cuando son `null` (`array_filter(..., fn($v) => ! is_null($v))`).
- Enums backed se serializan con `->value` o `->name` según convenga al schema (criterio a validar contra el OpenAPI durante la implementación; default: `->value` cuando el schema marca el tipo como `string`).

---

## 10. Composer + Provider + Config

### 10.1 `composer.json` (cambios)

- `require`:
  - `"php": "^8.3"` (sube desde `^8.2`).
  - `"guzzlehttp/guzzle": "^7.2"` (nuevo).
  - **Eliminar** `"illuminate/http"`.
  - Mantener `"illuminate/support": "^11.0|^12.0|^13.0"`.
- `require-dev`:
  - Eliminar `"phpunit/phpunit": "^11.0"`.
- `autoload.psr-4`: mantener `"MmtRiskSdk\\": "src/"`.
- Eliminar `autoload-dev` (sin tests).
- `extra.laravel.providers`: `"MmtRiskSdk\\MmtRiskSdkServiceProvider"` (provider ahora en raíz de `src/`).
- Eliminar `extra.laravel.aliases.MmtRisk`.
- `scripts.test`: eliminar.
- `version`: subir a `"2.0.0"`.

### 10.2 `config/mmt-risk-sdk.php`

```php
<?php

return [
    'base_url' => env('RISK_SERVICE_URL', ''),
];
```

### 10.3 `.env.example`

```
RISK_SERVICE_URL=http://68.178.205.211:6051
```

### 10.4 `src/MmtRiskSdkServiceProvider.php`

Espejo de `TradingServiceSdkServiceProvider`:

```php
namespace MmtRiskSdk;

use Illuminate\Support\ServiceProvider;
use MmtRiskSdk\Domains\Accounts\Contracts\{AccountsService, AccountsServiceInterface};
use MmtRiskSdk\Domains\Brokers\Contracts\{BrokersService, BrokersServiceInterface};
use MmtRiskSdk\Domains\Rules\Contracts\{RulesService, RulesServiceInterface};
use MmtRiskSdk\Domains\Ingress\Contracts\{IngressService, IngressServiceInterface};
use MmtRiskSdk\TransportDrivers\Contracts\TransportInterface;
use MmtRiskSdk\TransportDrivers\Drivers\Http\RiskServiceHttpClient;

final class MmtRiskSdkServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/mmt-risk-sdk.php', 'mmt-risk-sdk');

        $this->app->singleton(TransportInterface::class, RiskServiceHttpClient::class);
        $this->app->bind(AccountsServiceInterface::class, AccountsService::class);
        $this->app->bind(BrokersServiceInterface::class, BrokersService::class);
        $this->app->bind(RulesServiceInterface::class, RulesService::class);
        $this->app->bind(IngressServiceInterface::class, IngressService::class);
        $this->app->singleton(RiskService::class);
    }

    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/mmt-risk-sdk.php' => config_path('mmt-risk-sdk.php'),
            ], 'mmt-risk-sdk-config');
        }
    }
}
```

### 10.5 `src/RiskService.php` (entry point)

Espejo conceptual de `TradingService` pero sin sesión:

```php
namespace MmtRiskSdk;

use MmtRiskSdk\Domains\Accounts\Contracts\AccountsServiceInterface;
use MmtRiskSdk\Domains\Brokers\Contracts\BrokersServiceInterface;
use MmtRiskSdk\Domains\Ingress\Contracts\IngressServiceInterface;
use MmtRiskSdk\Domains\Rules\Contracts\RulesServiceInterface;
use MmtRiskSdk\TransportDrivers\Contracts\TransportInterface;

class RiskService
{
    public function __construct(
        private readonly TransportInterface $transport,
    ) {}

    public function accounts(): AccountsServiceInterface { return resolve(AccountsServiceInterface::class); }
    public function brokers(): BrokersServiceInterface  { return resolve(BrokersServiceInterface::class); }
    public function rules(): RulesServiceInterface     { return resolve(RulesServiceInterface::class); }
    public function ingress(): IngressServiceInterface { return resolve(IngressServiceInterface::class); }

    /** GET /health — plain JSON, no envelope. */
    public function health(): array { /* delega vía Transport con metadata raw o usa Guzzle directo */ }
}
```

---

## 11. Plan de implementación por fases

**No iniciar hasta confirmación del usuario** (decisión § 5 / `execution_scope = a`).

### Fase 0 — Preparación

1. Refrescar `openapi.json` con `curl http://68.178.205.211:6051/openapi.json -o openapi.json` y `composer validate --strict` para snapshot final.
2. Verificar shape de los schemas marcados como pendiente (`Mt5PlatformUserSnapshot` desde `OpenTradeRow`; sufijo `Item` en nombres; flag `raw` en `health`).

### Fase 1 — Espejo de infraestructura del Trading SDK

3. Copiar `Contracts/CommandInterface.php` (renombrar namespace).
4. Copiar `TransportDrivers/Contracts/{TransportInterface,TransportPacket,ActionResultInterface}.php`.
5. Copiar `WireHydration/{Attributes/WireMapped,WireHydrator}.php`.
6. Crear `Exceptions/RiskServiceRequestException.php` (análoga a Trading).
7. Implementar `TransportDrivers/Drivers/Http/{RiskServiceHttpClient,ResponseResult}.php` espejando Trading; añadir manejo de la flag `metadata.raw` para `/health`.

### Fase 2 — Service Provider, config, entry point

8. Mover provider a `src/MmtRiskSdkServiceProvider.php`; ajustar `composer.json` y `extra.laravel.providers`. Eliminar Facade y alias.
9. Reducir `config/mmt-risk-sdk.php` y `.env.example`.
10. Crear `src/RiskService.php` con `accounts()/brokers()/rules()/ingress()/health()`.

### Fase 3 — Dominios (orden propuesto: Brokers → Rules → Ingress → Accounts)

Para cada dominio: crear `Contracts/<Domain>ServiceInterface.php` + `Contracts/<Domain>Service.php` + Commands + ObjectResponses listados en § 8/§ 9.

11. **Brokers**: 5 endpoints, 2 Commands, ~7 ObjectResponses (BrokerResponseItem + nested integration).
12. **Rules**: 6 endpoints, 2 Commands, 1 ObjectResponse (RuleResponseItem).
13. **Ingress**: 1 endpoint, 1 Command, sin ObjectResponse.
14. **Accounts**: 20 endpoints, 5 Commands, ~20 ObjectResponses (incl. métricas con anidación profunda).

### Fase 4 — Limpieza

15. Eliminar `src/Api/*`, `src/RiskRestClient.php`, `src/RiskApiError.php`, `src/Support/*`, `src/Laravel/`.
16. Eliminar `tests/` y `docs/specs/`.
17. Eliminar dependencia `phpunit/phpunit` y `autoload-dev` de `composer.json`.

### Fase 5 — Docs y release

18. Actualizar `README.md` al estilo Trading SDK (sección "Uso rápido" con `RiskService` + `getData(FQCN::class)`).
19. Añadir `## [2.0.0]` en `CHANGELOG.md` con bullets de breaking changes y nuevas capacidades.
20. Bump `composer.json` a `"version": "2.0.0"`.
21. Tag git `2.0.0` (manualmente por el usuario al finalizar; el agente no etiqueta).

### Checkpoints (revisión usuario)

- Tras Fase 1+2: review de infraestructura antes de empezar dominios.
- Tras Brokers (Fase 3 primer dominio): validar patrón.
- Tras Accounts: validar la nomenclatura de ObjectResponses y la cobertura de schemas anidados.
- Tras Fase 4+5: revisión final y aprobación de cierre.

---

## 12. Archivos a crear / modificar / eliminar

### Crear (nuevo)

```
src/MmtRiskSdkServiceProvider.php
src/RiskService.php
src/Contracts/CommandInterface.php
src/Exceptions/RiskServiceRequestException.php
src/TransportDrivers/Contracts/TransportInterface.php
src/TransportDrivers/Contracts/TransportPacket.php
src/TransportDrivers/Contracts/ActionResultInterface.php
src/TransportDrivers/Drivers/Http/RiskServiceHttpClient.php
src/TransportDrivers/Drivers/Http/ResponseResult.php
src/WireHydration/Attributes/WireMapped.php
src/WireHydration/WireHydrator.php
src/Domains/Accounts/Contracts/AccountsServiceInterface.php
src/Domains/Accounts/Contracts/AccountsService.php
src/Domains/Accounts/Commands/CreateAccountCommand.php
src/Domains/Accounts/Commands/UpdateAccountCommand.php
src/Domains/Accounts/Commands/EvaluationHistoryRangeCommand.php
src/Domains/Accounts/Commands/EvaluationHistoryRecentCommand.php
src/Domains/Accounts/Commands/PatchAccountRuleMembershipCommand.php
src/Domains/Accounts/ObjectResponses/AccountResponseItem.php
src/Domains/Accounts/ObjectResponses/AccountListPageResponseItem.php
src/Domains/Accounts/ObjectResponses/AccountBrokerScopeTotalsItem.php
src/Domains/Accounts/ObjectResponses/AccountMetricsContextResponseItem.php
src/Domains/Accounts/ObjectResponses/EvaluationHistoryByLoginResponseItem.php
src/Domains/Accounts/ObjectResponses/RuleEvaluationHistoryItem.php
src/Domains/Accounts/ObjectResponses/RuleEvaluationSnapshotPayloadItem.php
src/Domains/Accounts/ObjectResponses/BoundRuleEvaluationPayloadItem.php
src/Domains/Accounts/ObjectResponses/MatchStreakItem.php
src/Domains/Accounts/ObjectResponses/MetricChangeListResponseItem.php
src/Domains/Accounts/ObjectResponses/MetricChangeLogItem.php
src/Domains/Accounts/ObjectResponses/MetricChangeEntryItem.php
src/Domains/Accounts/ObjectResponses/MetricsEnrichmentResponseItem.php
src/Domains/Accounts/ObjectResponses/DailyDeltaItem.php
src/Domains/Accounts/ObjectResponses/MetricHistoryResponseItem.php
src/Domains/Accounts/ObjectResponses/MetricHistoryPointItem.php
src/Domains/Accounts/ObjectResponses/MetricHistorySummaryItem.php
src/Domains/Accounts/ObjectResponses/MetricTradeTimelineResponseItem.php
src/Domains/Accounts/ObjectResponses/MetricTradeTimelineRowItem.php
src/Domains/Accounts/ObjectResponses/AccountRuleMembershipItem.php
src/Domains/Accounts/ObjectResponses/RuleMembershipSettingsResponseItem.php
src/Domains/Accounts/ObjectResponses/OpenTradeRowItem.php
src/Domains/Accounts/ObjectResponses/Mt5PlatformUserSnapshotItem.php
src/Domains/Brokers/Contracts/BrokersServiceInterface.php
src/Domains/Brokers/Contracts/BrokersService.php
src/Domains/Brokers/Commands/CreateBrokerCommand.php
src/Domains/Brokers/Commands/UpdateBrokerCommand.php
src/Domains/Brokers/ObjectResponses/BrokerResponseItem.php
src/Domains/Brokers/ObjectResponses/BrokerIntegrationResolvedItem.php
src/Domains/Brokers/ObjectResponses/BrokerIntegrationSdkResolvedItem.php
src/Domains/Brokers/ObjectResponses/BrokerIntegrationKafkaResolvedItem.php
src/Domains/Brokers/ObjectResponses/BrokerIntegrationIngressResolvedItem.php
src/Domains/Brokers/ObjectResponses/BrokerIntegrationInputItem.php
src/Domains/Brokers/ObjectResponses/BrokerSdkConfigInputItem.php
src/Domains/Brokers/ObjectResponses/BrokerKafkaConfigInputItem.php
src/Domains/Rules/Contracts/RulesServiceInterface.php
src/Domains/Rules/Contracts/RulesService.php
src/Domains/Rules/Commands/CreateRuleCommand.php
src/Domains/Rules/Commands/UpdateRuleCommand.php
src/Domains/Rules/ObjectResponses/RuleResponseItem.php
src/Domains/Ingress/Contracts/IngressServiceInterface.php
src/Domains/Ingress/Contracts/IngressService.php
src/Domains/Ingress/Commands/IngressEventCommand.php
```

### Modificar

```
composer.json                 (deps, version 2.0.0, autoload-dev fuera, providers/aliases)
config/mmt-risk-sdk.php       (reducir a base_url)
.env.example                  (reducir a RISK_SERVICE_URL)
README.md                     (reescribir al estilo Trading SDK)
CHANGELOG.md                  (entrada 2.0.0)
openapi.json                  (refrescar snapshot desde el servicio vivo)
```

### Eliminar

```
src/RiskRestClient.php
src/RiskApiError.php
src/Support/Envelope.php
src/Support/UriHelper.php
src/Support/QueryHelper.php
src/Api/AccountsApi.php
src/Api/BrokersApi.php
src/Api/RulesApi.php
src/Api/InternalIngressApi.php
src/Laravel/MmtRiskSdkServiceProvider.php      (movido a src/MmtRiskSdkServiceProvider.php)
src/Laravel/Facades/MmtRisk.php
tests/                                          (carpeta completa)
docs/specs/                                     (carpeta completa)
phpunit.xml
.phpunit.cache/                                 (artefacto)
```

---

## 13. Análisis de impacto de segundo orden

- **Consumidores actuales del Risk SDK** (al menos `MMT-PropFirm` según el contexto del workspace): tendrán que actualizar a `2.0.0` y refactorizar llamadas. La nueva firma es similar al Trading SDK (`->getData(FQCN::class)`), por lo que el patrón ya es conocido en MMT-PropFirm.
- **MMT-PropFirm**: el `endpoint_changelog.md` del repo MMT-PropFirm registra cambios de contrato HTTP del propio servicio; aquí cambia solo el SDK. No procede entrada de changelog en MMT-PropFirm; sí procede entrada en `decisions.md` allá si se decide migrar inmediatamente.
- **Autodescubrimiento de paquetes**: Composer detecta el nuevo provider por `extra.laravel.providers`. Eliminar `aliases.MmtRisk` elimina el binding al Facade en consumidores; cualquier import `MmtRiskSdk\Laravel\Facades\MmtRisk` causará fatal error y debe ser refactorizado.
- **Sin Bearer token / headers extra**: si el Risk Service hoy expone su API sin auth (default `http://68.178.205.211:6051` no requiere token) el cambio es transparente. Si en el futuro se introduce auth, este SDK requerirá una v2.x o v3.0 que reintroduzca el campo `headers` en transport metadata (no en config).
- **Tests removidos**: pérdida de cobertura local. Aceptable porque (a) Trading SDK no tiene tests, (b) el contrato real lo valida el servicio Risk al integrar con MMT-PropFirm, (c) está dentro del scope explícito del usuario.

---

## 14. Seguimiento del avance

- El agente debe actualizar los checkboxes de **§ 3 Criterios de éxito** conforme se completen.
- Al finalizar cada fase de § 11, dejar nota breve en este archivo (sección "Avance" añadida al final) con commit hash y archivos tocados.
- Al cerrar la tarea: crear `docs/completed_tasks/001_realign_risk_sdk_architecture_with_trading_sdk_log.md` siguiendo la plantilla `MMT-PropFirm/docs/templates/task_log_template.md`.

---

## 15. Instrucciones para el agente IA

### Proceso obligatorio

- **No iniciar implementación** hasta que el usuario confirme explícitamente este plan. Decisión `execution_scope = a`.
- Consultar `LARAVEL-TRADING-SERVICE-SDK/src/**` como **única fuente de verdad arquitectónica** durante la implementación; cuando haya duda entre dos caminos, preferir el espejo exacto del Trading SDK.
- **Comentarios y docblocks** en código PHP siempre en **inglés** (regla del workspace `comments-english.mdc`).
- **No introducir tests** (alineación con Trading SDK + reglas del workspace sobre no-tests por iniciativa).
- Cada vez que se cree un archivo PHP nuevo, ejecutar `git add <ruta>` antes de cerrar la fase.
- **No usar `Auth::user()`** en código (no aplica aquí porque el SDK no toca Laravel auth, pero se recuerda la regla del workspace).
- **No tocar** `MMT-PropFirm` ni ningún consumidor durante esta tarea. La migración del consumidor es trabajo separado (probablemente otra tarea `002_*` en MMT-PropFirm).

### Preferencias de comunicación

- Avanzar por fases del § 11 con resumen breve al final de cada fase y pausa para validación del usuario.
- Reportar bloqueos inmediatos (ej. ambigüedad en un schema OpenAPI) **sin asumir**; preguntar.

### Estándares de código

- PHP 8.3 con `declare(strict_types=1);` en archivos nuevos (alineado a `requirements.md` original que se eliminará).
- Constructor promovido siempre que sea posible (Commands, ObjectResponses, Services).
- Sin `array<string,mixed>` como contrato público en Service methods (excepto donde el OpenAPI lo exige, como `IngressEventCommand` y respuestas `dict[str,Any]`).

---

## 16. Cierre de tarea

Al terminar, el agente:

1. Verifica todos los checkboxes del § 3 marcados.
2. Pregunta explícitamente: «¿La tarea está terminada y estás de acuerdo con lo implementado?».
3. Si NO: itera con los cambios solicitados.
4. Si SÍ: crea el log `docs/completed_tasks/001_realign_risk_sdk_architecture_with_trading_sdk_log.md` con resumen + archivos + decisiones + commit. Hace `git add` del archivo de log.
5. Actualiza `CHANGELOG.md` con la entrada `2.0.0` definitiva si aún no estaba.
