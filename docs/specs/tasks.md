# MMT Risk Laravel SDK — Tasks

**Document chain:** este plan de trabajo **depende** de los requisitos y del diseño; no ejecutar tareas en orden contradictorio.

| Depends on |
|------------|
| **[requirements.md](./requirements.md)** |
| **[design.md](./design.md)** |

**Version:** 1.0  
**Last updated:** 2026-05-09

---

## 1. How to use this document

1. Congelar o versionar **requirements.md** y **design.md** para el sprint/release.
2. Completar tareas en el orden indicado (bloques T1 → T5).
3. Marcar checkboxes al cerrar con PR/commit.
4. Si el resultado contradice requisitos o diseño, **actualizar primero** `requirements.md` / `design.md`, luego esta lista.

---

## 2. Task groups

### T1 — Foundation (bloqueante para todo lo demás)

| ID | Task | Acceptance criteria | Design ref |
|----|------|---------------------|------------|
| T1.1 | Crear/ajustar `composer.json` (nombre, autoload, extra.laravel, scripts) | `composer validate` OK; provider y alias registrados | design §3, §8 |
| T1.2 | Implementar `RiskApiError`, `Support\Envelope`, `Support\UriHelper` | Misma semántica que trading SDK; tests unitarios mínimos opcionales | design §4.2, §7 |
| T1.3 | Implementar `RiskRestClient` (`fromConfig`, `fromEnvironment`, `url`, `envelopeRequest`, `health`) | Fake HTTP: envelope OK y error; health sin envelope | design §4.1, §6 |
| T1.4 | Añadir `config/mmt-risk-sdk.php` + `.env.example` | Variables documentadas; sin secretos | design §5 |
| T1.5 | Implementar `MmtRiskSdkServiceProvider` + `Facades\MmtRisk` | App Laravel resuelve singleton; publish config funciona | design §8 |

**Done when:** `composer dump-autoload` + smoke manual `php -r` o mini script que instancie cliente con fake.

---

### T2 — Domain APIs: Internal + Rules + Brokers

| ID | Task | Acceptance criteria | Design ref |
|----|------|---------------------|------------|
| T2.1 | `Api\InternalIngressApi` — `POST /internal/ingress/events` | Método acepta array/payload acorde OpenAPI | design §4.3 |
| T2.2 | `Api\RulesApi` — todas las rutas `/rules*` | Parámetros path/query/body alineados OpenAPI | design §4.3 |
| T2.3 | `Api\BrokersApi` — CRUD `/brokers` | Igual | design §4.3 |
| T2.4 | Cablear propiedades en `RiskRestClient`: `ingress`, `rules`, `brokers` | Acceso tipo `$client->rules->listRules(...)` | design §3 |

**Done when:** revisión cruzada con paths del `openapi.json` del repo.

---

### T3 — Domain API: Accounts (mayor superficie)

| ID | Task | Acceptance criteria | Design ref |
|----|------|---------------------|------------|
| T3.1 | CRUD + listados: `/accounts`, `/accounts/page`, `/accounts/stats`, `by-login`, `{id}` | Query params opcionales no envían ruido innecesario | design §4.3 |
| T3.2 | Historial evaluación: `evaluation-history/recent`, `evaluation-history/range` | Body JSON según schemas OpenAPI | design §4.3 |
| T3.3 | Métricas: `metrics-context`, `metrics-enrichment`, `metrics/history`, `metrics/trade-timeline` | Mapeo explícito `from_utc` / `to_utc` / `metric_key` / etc. | design §4.3 |
| T3.4 | Trades y cambios: `trades/open`, `metric-changes` | OK | design §4.3 |
| T3.5 | Reglas por cuenta: `rule-memberships`, `membership` PATCH, `match-streak/reset` | OK | design §4.3 |
| T3.6 | `sync-mt5-open-positions` POST | OK | design §4.3 |
| T3.7 | Propiedad `accounts` en `RiskRestClient` | Expuesta y documentada en README | design §3 |

**Done when:** checklist de paths del OpenAPI sin métodos faltantes.

---

### T4 — Documentation & packaging

| ID | Task | Acceptance criteria | Requirements ref |
|----|------|---------------------|------------------|
| T4.1 | `README.md` — instalación, env, uso Laravel y standalone | Cubre FR de documentación | §4 NFR |
| T4.2 | Incluir o referenciar `openapi.json` y cómo refrescarlo | Trazabilidad con API real | §2.1 |
| T4.3 | `.gitignore` alineado trading SDK | vendor, phpunit cache, lock según política equipo | — |

---

### T5 — Quality gates

| ID | Task | Acceptance criteria | Requirements ref |
|----|------|---------------------|------------------|
| T5.1 | Tests PHPUnit con `Http::fake` para envelope + health | Sin red; reproducible | §2.1 |
| T5.2 | Revisión paridad con `MMT-TRADING-SERVICES-LARAVEL-SDK` | Lista de diferencias justificadas (solo dominio) | §3 |
| T5.3 | Tag Git / versión en `RiskRestClient::VERSION` coherente | User-Agent y changelog si aplica | NFR-4 |

---

## 3. Dependency graph (execution order)

```
requirements.md (approved)
       │
       ▼
   design.md (approved)
       │
       ├── T1 Foundation
       │        │
       │        ├──► T2 Internal/Rules/Brokers
       │        │
       │        └──► T3 Accounts
       │                 │
       └─────────────────┴──► T4 Docs ──► T5 Quality
```

**Regla:** no marcar T3 completo antes de T1; no release público antes de T5.2 mínimo.

---

## 4. Verification checklist (release)

- [ ] Todos los endpoints requeridos en **requirements §4.3** tienen método en código.
- [ ] `GET /health` no pasa por `Envelope`.
- [ ] Errores envelope y HTTP lanzan `RiskApiError`.
- [ ] Config Laravel publicable y Facade opcional documentada.
- [ ] `composer test` (o script definido) pasa en limpio.

---

## 5. Feedback loop

Si durante T2–T3 se detecta ambigüedad del OpenAPI:

1. Anotar en **requirements.md** (alcance o criterio de aceptación).
2. Actualizar **design.md** (firma de método o convención de query).
3. Ajustar esta lista si aparecen subtareas nuevas.
