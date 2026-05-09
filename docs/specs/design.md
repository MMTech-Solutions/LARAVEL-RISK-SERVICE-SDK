# MMT Risk Laravel SDK — Design

**Document chain:** este diseño **depende** de los requisitos aprobados y alimenta el plan de tareas.

| Depends on | Next document |
|------------|----------------|
| **[requirements.md](./requirements.md)** — requisitos funcionales y no funcionales | [tasks.md](./tasks.md) |

**Version:** 1.0  
**Last updated:** 2026-05-09

---

## 1. Prerequisites

Leer y asumir vigente **[requirements.md](./requirements.md)**. Cualquier cambio de alcance debe actualizar primero los requisitos y luego este diseño.

---

## 2. Architecture overview

```
┌─────────────────────────────────────────────────────────────┐
│  Laravel app / CLI                                           │
│  injects Illuminate\Http\Client\Factory                      │
└────────────────────────────┬────────────────────────────────┘
                             │
                             ▼
┌─────────────────────────────────────────────────────────────┐
│  MmtRiskSdk\RiskRestClient (singleton en Laravel)           │
│  - baseUrl, defaultTimeout, headers (Bearer + extras)        │
│  - envelopeRequest(method, path, query?, body?)             │
│  - health() → GET /health (no envelope)                      │
│  - Facades to domain APIs:                                   │
│      ingress | rules | accounts | brokers                  │
└────────────────────────────┬────────────────────────────────┘
                             │ HTTPS JSON
                             ▼
┌─────────────────────────────────────────────────────────────┐
│  MMT Risk Management Service (REST)                          │
└─────────────────────────────────────────────────────────────┘
```

No hay capa de “sesión” ni `connection_id`: el cliente usa una única **base URL** del microservicio Risk.

---

## 3. Package layout

```
src/
  RiskRestClient.php       # Entry point + envelopeRequest + health
  RiskApiError.php
  Support/
    Envelope.php           # dataOrRaise — same semantics as trading SDK
    UriHelper.php          # rawurlencode path segments
  Api/
    InternalIngressApi.php # POST /internal/ingress/events
    RulesApi.php
    AccountsApi.php        # Largest surface — accounts / metrics paths
    BrokersApi.php
  Laravel/
    MmtRiskSdkServiceProvider.php
    Facades/
      MmtRisk.php          # @mixin RiskRestClient
config/
  mmt-risk-sdk.php
tests/
  RiskRestClientTest.php   # Http::fake patterns
```

**PSR-4:** `MmtRiskSdk\` → `src/`

---

## 4. Core components

### 4.1 `RiskRestClient`

- **Constants:** `VERSION` (semver alineado al paquete / API si se acuerda).
- **Constructor:** `Factory $http`, `string $baseUrl`, `float $defaultTimeout`, `array $headers`.
- **Factories:**
  - `fromConfig(Factory, array $config)` — lee `config('mmt-risk-sdk')`.
  - `fromEnvironment(Factory)` — `MMT_RISK_API_BASE_URL`, `MMT_RISK_API_TOKEN`, `MMT_RISK_HTTP_TIMEOUT`.
- **`url(string $path)`:** normaliza path con `/` inicial y concatena a `baseUrl`.
- **`envelopeRequest`:** igual patrón que `Mt5RestClient::platformRequest` pero **sin** prefijo de versión/plataforma/connection; tras la respuesta, `Envelope::dataOrRaise(...)`.
- **`health()`:** `GET` absoluto a `/health`; si HTTP OK, devolver `$response->json()` **sin** pasar por envelope.

### 4.2 `Support\Envelope`

- Copiar semántica del trading SDK: éxito iff `code === 'OK'`; devolver `data`.
- Lanzar `RiskApiError` con `apiCode` y `payload` cuando el servidor devuelve envelope de error.

### 4.3 Domain APIs (thin wrappers)

Cada clase recibe `RiskRestClient` en el constructor (readonly) y solo compone `path` + `method` + `query`/`json`:

| Class | OpenAPI tag / notes |
|--------|---------------------|
| `InternalIngressApi` | property `$ingress` on client — `postEvent(array $payload)` |
| `RulesApi` | list/create/active/get/patch/delete |
| `AccountsApi` | todos los paths bajo `/accounts/...` incl. métricas y memberships |
| `BrokersApi` | CRUD brokers |

**Naming:** métodos en **camelCase** descriptivo en inglés (ej. `listRules`, `getAccountById`, `evaluationHistoryRecent`), parámetros que reflejen query names del OpenAPI (`fromUtc` → query `from_utc`).

---

## 5. Configuration (`config/mmt-risk-sdk.php`)

| Key | Type | Env | Description |
|-----|------|-----|-------------|
| `base_url` | string | `MMT_RISK_API_BASE_URL` | Default ej. `http://127.0.0.1:6051` |
| `api_token` | ?string | `MMT_RISK_API_TOKEN` | Si no vacío → `Authorization: Bearer {token}` |
| `default_timeout` | float | `MMT_RISK_HTTP_TIMEOUT` | Segundos |
| `headers` | array<string,string> | — | Headers extra (merge después de defaults) |

Provider: `mergeConfigFrom`, singleton `RiskRestClient::class`, `publishes` con tag `mmt-risk-sdk-config`.

---

## 6. HTTP behavior

- Headers por defecto: `Content-Type: application/json`, `Accept: application/json`, `User-Agent: mmt-risk-sdk-php/{VERSION}`.
- Merge de `$headers` del config (incl. Bearer).
- Métodos permitidos: GET, HEAD, POST, PUT, PATCH, DELETE (como trading SDK).
- Query params: arrays asociativos; **omitir** claves `null` donde el OpenAPI trate el parámetro como opcional para no forzar defaults incorrectos (documentar excepciones).

---

## 7. Error model (`RiskApiError`)

Propiedades públicas readonly:

- `?int $httpStatus`
- `?string $apiCode` — valor de `code` del envelope cuando aplica
- `mixed $payload` — típicamente `data` del error o cuerpo parseado

---

## 8. Laravel integration

- **Facade `MmtRisk`:** resuelve `RiskRestClient::class`; PHPDoc `@mixin RiskRestClient` para IDE.
- **Consumption:** `app(RiskRestClient::class)->rules->listRules(...)` o `MmtRisk::rules()->...` si el facade expone solo el cliente (Facade al cliente completo, no sub-APIs separadas en el container).

---

## 9. Testing strategy

- PHPUnit 11; **sin** tests contra red real por defecto.
- `Http::fake` con respuestas envelope `{ code: OK, data: ... }` y casos `code: ERROR_*`.
- Test dedicado: `health` con JSON plano sin envelope.

---

## 10. OpenAPI maintenance

- Mantener `openapi.json` en raíz del repo o documentar comando `curl` hacia `{base}/openapi.json`.
- Al cambiar el servicio Risk, actualizar OpenAPI y revisar diff en **AccountsApi** y resto.

---

## 11. Traceability (requirements → design)

| Requirement ID | Design section |
|----------------|----------------|
| FR-CFG-* | §5 Configuration |
| FR-API-* | §4.1, §6 HTTP behavior, §7 Error model |
| FR-API-3 | §3 Package layout, §4.3 Domain APIs |
| FR-LARAVEL-* | §8 Laravel integration |

---

## 12. Next step

El archivo **[tasks.md](./tasks.md)** depende de **requirements.md** y de **este diseño**: las tareas deben ser verificables contra las secciones anteriores.
