# MMT Risk Laravel SDK — Requirements

**Document chain:** este documento es la base del especificación. El diseño técnico y las tareas dependen de él.

| Depends on | Next document |
|------------|----------------|
| *(ninguno — fuente de verdad funcional)* | [design.md](./design.md) |

**Version:** 1.0  
**Last updated:** 2026-05-09  
**Related:** OpenAPI del servicio Risk (`openapi.json` en la raíz del repo o URL `/openapi.json` del microservicio).

---

## 1. Purpose

Definir los **requisitos funcionales y no funcionales** del paquete Composer **`mmtech/mmt-risk-sdk`**: un SDK PHP instalable en aplicaciones Laravel (y usable sin Laravel con `Illuminate\Http\Client`), que consuma la API HTTP del **MMT Risk Management Service** de forma coherente con el paquete de referencia **`mmtech/mmt-trading-sdk`** (`MMT-TRADING-SERVICES-LARAVEL-SDK`).

---

## 2. Scope

### 2.1 In scope

- Cliente HTTP basado en **`Illuminate\Http\Client\Factory`** con timeouts configurables.
- Cobertura de **todas las rutas** documentadas en el OpenAPI del servicio Risk en la versión acordada (rules, accounts, brokers, internal ingress, health).
- Respuestas con **envelope** `{ code, message, data }` cuando `code === OK`, extrayendo y devolviendo solo **`data`** al llamador (igual patrón que el SDK de trading).
- Excepción explícita: **`GET /health`** devuelve JSON **sin** envelope; el SDK debe exponer un método dedicado que devuelva el cuerpo decodificado tal cual.
- Integración Laravel: **ServiceProvider**, config publicable, **Facade** opcional (`MmtRisk`).
- Errores de negocio/API modelados como **`RiskApiError`** (análogo a `Mt5ApiError`): mensaje, HTTP status, código API opcional, payload opcional.
- Documentación de uso (`README`), `.env.example`, y tests con **`Http::fake`** donde aplique (sin obligar integración contra servidor real en CI).
- Archivo **OpenAPI** versionado o script/instrucciones para refrescarlo desde el servicio.

### 2.2 Out of scope

- Generación automática de código desde OpenAPI en cada build (opcional futura; no requisito v1).
- Modelos PHP fuertemente tipados por cada schema OpenAPI (opcional; v1 puede usar `array` / tipos mixtos devueltos por `json()`).
- Autenticación OAuth completa; solo requisitos configurables (Bearer token u headers arbitrarios vía config).
- Consumo de Kafka, bridges o ingreso de eventos fuera de HTTP (solo lo que expone la REST API).

---

## 3. Reference package parity

El SDK debe **imitar prácticas del trading SDK** salvo donde el dominio Risk lo impida:

| Aspect | Trading SDK | Risk SDK |
|--------|-------------|----------|
| Namespace raíz | `MmtTradingSdk` | `MmtRiskSdk` |
| Cliente principal | `Mt5RestClient` | `RiskRestClient` |
| Error | `Mt5ApiError` | `RiskApiError` |
| Envelope helper | `Support\Envelope` | `Support\Envelope` |
| Sesión / connection | `Mt5Session` + `connection_id` | **No aplica** — una sola base URL |
| Laravel provider | `MmtTradingSdkServiceProvider` | `MmtRiskSdkServiceProvider` |
| Facade | `MmtMt5` | `MmtRisk` |
| Config filename | `mmt-trading-sdk.php` | `mmt-risk-sdk.php` |

---

## 4. Functional requirements

### 4.1 Configuración

- **FR-CFG-1:** Variable `base_url` sin barra final; todas las rutas del OpenAPI son paths absolutos desde la raíz (`/rules`, `/accounts`, …).
- **FR-CFG-2:** Soporte de token Bearer opcional (`api_token` / env `MMT_RISK_API_TOKEN`) sin hardcodear secretos.
- **FR-CFG-3:** Timeout HTTP configurable (`default_timeout` / env `MMT_RISK_HTTP_TIMEOUT`).
- **FR-CFG-4:** Posibilidad de inyectar **headers adicionales** (array en config) para gateways o API keys no Bearer.

### 4.2 Contrato API

- **FR-API-1:** Para métodos que devuelven envelope, si `code !== 'OK'`, lanzar **`RiskApiError`** con mensaje usable y metadata (`httpStatus`, `apiCode`, `payload`).
- **FR-API-2:** Para HTTP de transporte fallido (4xx/5xx sin JSON válido), lanzar **`RiskApiError`** con cuerpo raw o detalle parseado.
- **FR-API-3:** Los métodos del SDK deben **mapear 1:1** las operaciones del OpenAPI: mismos paths, métodos HTTP, query params y cuerpos JSON esperados.

### 4.3 Superficie del servicio (checklist OpenAPI)

El SDK debe exponer llamadas equivalentes a (nombres orientativos):

- **Internal:** `POST /internal/ingress/events`
- **Rules:** `GET/POST /rules`, `GET /rules/active`, `GET/PATCH/DELETE /rules/{rule_id}`
- **Accounts:** listados, CRUD, stats, página, historial de evaluación (recent/range), métricas (context, enrichment, history, trade timeline), trades abiertos, cambios de métricas, memberships de reglas, sync MT5 positions, etc., según paths del OpenAPI actual.
- **Brokers:** `GET/POST /brokers`, `GET/PATCH/DELETE /brokers/{broker_id}`
- **Health:** `GET /health` — sin envelope.

### 4.4 Laravel

- **FR-LARAVEL-1:** Auto-discovery del provider vía `composer.json` → `extra.laravel.providers`.
- **FR-LARAVEL-2:** Publicación de config con tag dedicado (ej. `mmt-risk-sdk-config`).
- **FR-LARAVEL-3:** Registro singleton de **`RiskRestClient`** resuelto desde `Factory` + config.

---

## 5. Non-functional requirements

- **NFR-1:** PHP **^8.2**; `illuminate/http` y `illuminate/support` **^11 | ^12 | ^13** (alineado al trading SDK).
- **NFR-2:** `declare(strict_types=1);` en archivos PHP nuevos.
- **NFR-3:** Sin dependencias de prod fuera de `illuminate/*` necesarias para HTTP y soporte.
- **NFR-4:** User-Agent identificable (ej. `mmt-risk-sdk-php/<version>`).
- **NFR-5:** Documentación en español o inglés consistente con el repo (README principal puede seguir el idioma del trading SDK).

---

## 6. Acceptance criteria (requirements level)

- [ ] Todas las rutas públicas del OpenAPI acordado tienen un método expuesto en el SDK o justificación documentada de exclusión (ej. solo red interna).
- [ ] Comportamiento envelope + error documentado y probado con fake HTTP.
- [ ] Config + env documentados; sin secretos en repo.
- [ ] Paridad de estilo y estructura con `MMT-TRADING-SERVICES-LARAVEL-SDK` revisada en code review.

---

## 7. Traceability

| ID | Requirement | Addressed in design (section) |
|----|-------------|------------------------------|
| FR-CFG-* | Config y env | design.md § Configuration |
| FR-API-* | Envelope y errores | design.md § HTTP & errors |
| FR-API-3 | Cobertura endpoints | design.md § API surface |
| FR-LARAVEL-* | Integración Laravel | design.md § Laravel integration |

La columna “Addressed in design” se rellena en **[design.md](./design.md)**.

---

## 8. Next step

El documento **[design.md](./design.md)** depende de este archivo: no debe contradecir los requisitos anteriores y debe refinar clases, namespaces y flujos de petición.
