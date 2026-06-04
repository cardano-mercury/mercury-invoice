# Cardano Mercury: Invoice — Agent Guide

Laravel 12 + Inertia 2 + Vue 3 + Vuetify 3 invoicing app with Cardano (ADA) and Stripe (fiat) payment processing. Deployed via Laravel Vapor.

## Layout (the one thing easy to get wrong)

The Laravel project lives in **`application/`**, not at the repo root. All `php`, `composer`, `npm`, and `artisan` commands run from there.

```
.
├── application/        # Laravel app (composer.json, package.json, .env*, etc.)
├── docker/             # docker-compose.yml, Dockerfile, apache vhost, php.ini
├── docs/               # business PDFs (cost analysis, GTM) — read-only context
├── .github/workflows/  # only staging.yml (auto-deploy on push to `staging`)
├── Makefile            # wraps every docker-compose / artisan call
└── opencode.json       # registers the laravel-boost MCP server
```

## Local dev (Docker-only — no host PHP)

The whole stack runs in Docker. Container names are fixed and **singular**:

- `cardanomercury-web` (php 8.4-apache, the app)
- `cardanomercury-mysql` (mysql 8.0, port **33100** on host)
- `cardanomercury-redis` (redis 6, internal only)
- `cardanomercury-horizon` (runs `php artisan horizon`)
- `cardanomercury-cron` (runs `php artisan schedule:work`)

Ports: Laravel **8100**, Vite HMR **8101**, MySQL **33100** (host-side), Redis internal.

Every workflow goes through the root `Makefile`. Common ones:

| Make target | What it does |
|---|---|
| `make build` | First-time: build images, `composer install`, wait for mysql, migrate, `npm run build` |
| `make up` / `make down` | Restart / stop all containers |
| `make rebuild` | Rebuild images without cache |
| `make shell` | Interactive bash in `cardanomercury-web` |
| `make artisan COMMAND="migrate:fresh --seed"` | Run any artisan command in the web container |
| `make composer-install` | `composer install` in container |
| `make db-migrate` / `make db-refresh` | Migrate / fresh+seed |
| `make frontend-build` | `npm install && npm run build` in container |
| `make frontend-watch` | `npm run dev` (Vite HMR) in container |
| `make tinker` | `php artisan tinker` in container |
| `make api-docs` | Regenerate Scribe API docs (writes to `application/.scribe/`) |
| `make logs`, `make logs-web`, `make logs-horizon`, `make logs-cron` | Tail container logs |
| `make stats`, `make status` | Resource usage / container status |

The Makefile is `.SILENT`, so you won't see echoed commands. Always invoke through `make` — don't run `docker exec` or `docker compose` by hand unless you have to.

After any PHP change: `vendor/bin/pint --dirty --format agent` (run inside the web container via `make shell` or `make artisan COMMAND="..."` style — for pint specifically just `docker exec cardanomercury-web vendor/bin/pint --dirty`).

## Architecture & domain notes

**Routing** (`application/routes/`):
- `web.php` — public landing, `/invoice/{encodedId}` public invoice view, `/incoming-webhooks/*` (Stripe), and the authenticated SPA shell (Jetstream: customers, products, services, invoices, reports, settings, webhooks).
- `api.php` — versioned **`/api/v1`**, gated by `auth:sanctum`. REST resources for the same entities (nested for emails/phones/addresses).
- `console.php` — schedules `SendInvoiceRemindersCommand` (daily 08:00 UTC), `ProcessCryptoPayments` (every 5 min), `GenerateReports` (every 5 min).

**Crypto / Cardano domain** — these are easy to miss:
- `config/cardanomercury.php` — `target_cardano_network` is **Mainnet in production, PreProd otherwise** (so local dev hits Cardano PreProd, not Mainnet). `crypto_payment_deadline_seconds = 3600` (1 hour to send payment).
- `app/Libraries/CardanoSlotTimer.php` — slot ↔ unix time conversions for Mainnet/Preview/PreProd.
- `app/ThirdParty/BlockfrostClient.php` — Cardano chain RPC client.
- `app/Services/AdaPriceService.php` — aggregates ADA/fiat price from CoinGecko + HitBTC + Coinbase (5-min cache).
- `app/Jobs/ProcessCryptoPaymentJob.php` + `ProcessCryptoPayments` artisan command — sweeps open crypto payments.

**Public invoice IDs are Hashids-encoded**, not numeric. Encode/decode via `App\Traits\HashIdTrait`. Routes use `{encodedId}` (e.g. `/invoice/{encodedId}/pay-via-crypto`).

**Webhooks** are per-user (model `Webhook` + `WebhookEventTargetName` enum). `App\Services\WebhookService` dispatches `WebhookNotificationJob` for `customer`/`product`/`service`/`invoice` events.

**CSRF is exempted for `incoming-webhooks/*`** (`bootstrap/app.php`) — required for Stripe. Don't add CSRF back there.

**API error responses are JSON** and shaped in `bootstrap/app.php` (NotFound/Auth/AccessDenied/MethodNotAllowed/Validation/unhandled → JSON). Don't add try/catch in API controllers to reformat errors; the global handlers already do it.

**Auth stack:** Fortify (backend) + Jetstream (frontend scaffolding) + Sanctum (API tokens). `App\Actions\Fortify\*` and `App\Actions\Jetstream\DeleteUser` are the customization points.

**Queue:** Redis-backed, run by the dedicated `cardanomercury-horizon` container. Horizon UI is at **`/horizon`**. `config/horizon.php` exists; supervisors in `HorizonServiceProvider`.

**API docs:** Scribe (knuckleswtf/scribe). Regenerate with `make api-docs`. Output cache lives in `application/.scribe/` (gitignored); the rendered UI is published to `application/public/vendor/scribe/` (committed).

**Scheduled reports:** `app/Jobs/Generate*ReportJob.php` are picked up by the `GenerateReports` console command.

## Conventions & quirks

- **PHP 8 constructor property promotion**, **explicit return types**, **curly braces on all control structures** — no `if (...) do_thing();` on one line.
- Casts go in a `casts()` method on the model, not the `$casts` property.
- Enums in `app/Enums/` use `App\Traits\EnumToArrayTrait` to expose `values()` for Blade/Vue.
- Reuse these traits: `HashIdTrait`, `LogExceptionTrait`, `UploadCSVTrait`, `JsonDownloadTrait`, `ScopedRouteModelBindingTrait`, `EnumToArrayTrait`. Check sibling files before introducing new ones.
- Vue pages are Inertia-driven and live in `application/resources/js/Pages/`. Single-root components only.
- Don't create new top-level directories. Don't change `composer.json` dependencies without approval.
- `application/.env` is **gitignored** — never commit a real `.env`. The committed `application/.env.example` has `__UPDATE_ME__` placeholders for SMTP and `DEV_STRIPE_WEBHOOK_HANDLER` for a webhook.site URL.
- `application/.scribe/` is gitignored; `application/public/vendor/scribe/` is committed.
- `application/phpunit.xml` has the sqlite-in-memory override **commented out** — feature tests run against MySQL by default. The Docker stack must be up.
- Tailwind config (`tailwind.config.js`) and Vuetify are both present; Vuetify is the primary UI kit in `Pages/`.

## Tests

- Pest 3, with `RefreshDatabase` applied to all `Feature/` tests via `tests/Pest.php`.
- No `composer test` script — use `php artisan test` (inside the web container).
- Existing `tests/Feature/*` is mostly Jetstream default auth tests. Domain coverage is thin.
- Run a single test: `php artisan test --compact --filter=testName` or `php artisan test --compact tests/Feature/SomeTest.php`.
- Factories exist for every model in `application/database/factories/`.

## Deploy

- Production & staging both go through **Laravel Vapor** (`application/vapor.yml`).
- Staging auto-deploys on push to the `staging` branch via `.github/workflows/staging.yml` (the only CI workflow). Production deploys are manual `vapor deploy production`.
- Domain: `mercury-invoice.com` (prod) / `staging.mercury-invoice.com`.

## OpenCode / Laravel Boost

- `opencode.json` registers the `laravel-boost` MCP server, which runs `php artisan boost:mcp` inside `cardanomercury-web`. **The container must be running** for Boost MCP tools (`database-query`, `database-schema`, `get-absolute-url`, `browser-logs`, `search-docs`, `last-error`, `read-log-entries`) to work.
- Domain skills in `.agents/skills/`: `configuring-horizon`, `fortify-development`, `inertia-vue-development`, `laravel-best-practices`, `pest-testing`. Activate the relevant one before working in that domain.
- Use `database-schema` (with `summary: true` first) before writing migrations; use `database-query` for read-only SQL; use `get-absolute-url` before sharing any URL with the user.

## Quick command reference

```bash
# from repo root
make build                       # first-time setup
make up                          # start stack
make shell                       # bash in web container
make artisan COMMAND="make:model InvoiceNote -mfs"
make db-refresh                  # fresh + seed
make frontend-watch              # vite HMR
make api-docs                    # regenerate Scribe
make logs-web                    # tail web container

# inside the web container
php artisan test --compact
php artisan test --compact --filter=AuthenticationTest
vendor/bin/pint --dirty --format agent
php artisan route:list --path=api
```
