# AGENTS.md

## Repo layout
- `backend/` — Laravel 13.8 (PHP ≥ 8.3). The whole app lives here.
- `frontend/` — empty placeholder. Backend will expose the API.
- `dev.Dockerfile`, `dev.docker-compose.yml` — at the repo **root**, not inside `backend/`. Dev container + Postgres.

## Dev environment (Docker-only)
PHP/Composer are not installed on the host. All `php artisan` and `composer` commands run inside the `app` container.

```bash
export UID=$(id -u) GID=$(id -g)   # see Permissions below
docker compose -f dev.docker-compose.yml up --build
# app on http://localhost:8000, postgres on localhost:5432
```

Other entry points:
```bash
docker compose -f dev.docker-compose.yml exec app php artisan <cmd>
docker compose -f dev.docker-compose.yml exec app composer <cmd>
docker compose -f dev.docker-compose.yml exec app ./vendor/bin/pint
```

### First-run autoprovisioning — read before editing
`dev.Dockerfile` `CMD` runs on every container start:
1. If `backend/artisan` is missing → `composer create-project laravel/laravel .` (first start only; `backend/` must be empty for it to succeed).
2. If `vendor/laravel/sanctum` is missing → `composer require laravel/sanctum` + `php artisan install:api` (publishes Sanctum config + migration, creates `routes/api.php`, wires the api route file in `bootstrap/app.php`).
3. If `vendor/spatie/laravel-permission` is missing → `composer require spatie/laravel-permission` + publishes `config/permission.php` and the `*_create_permission_tables.php` migration.
4. `sed`-rewrites `DB_CONNECTION/HOST/PORT/DATABASE/USERNAME/PASSWORD` in `backend/.env` to `pgsql` / host `db` / port `5432` / db `omron-management` / user `postgres` / pass `password`.
5. `php artisan migrate --force || true`.
6. `exec php artisan serve` on `0.0.0.0:8000`.

Package installs are gated on `vendor/<package>/` so they only run on first start. To force a reinstall, delete that package's vendor subdirectory (or `composer remove` it and rebuild).

Implications:
- Manual edits to the `DB_*` keys handled by the `sed` block in `backend/.env` are overwritten every start. To change them permanently, edit the matching `sed` lines in `dev.Dockerfile` **and** the `db` service env in `dev.docker-compose.yml` to agree.
- The container CMD runs only `php artisan serve`. It does **not** invoke `composer dev`, so the queue worker, `pail` log tail, and Vite dev server are not auto-started. Run them in extra `exec` shells if needed:
  ```bash
  docker compose -f dev.docker-compose.yml exec app php artisan queue:listen --tries=1
  docker compose -f dev.docker-compose.yml exec app php artisan pail
  docker compose -f dev.docker-compose.yml exec app npm run dev
  ```

## Auto-installed packages
On first boot the dev container pulls in:
- `laravel/sanctum` — for API token auth. The `install:api` command also scaffolds `routes/api.php` and switches the API auth guard to Sanctum.
- `spatie/laravel-permission` — roles + permissions. Adds `HasRoles` trait usage on `User` (manual) and the `roles`, `permissions`, `model_has_permissions`, `model_has_roles`, `role_has_permissions` tables via the published migration.

When wiring up the `User` model for these packages, follow the layered architecture (`app/Models/User.php` stays thin; roles/permissions live in `app/Infrastructure/` or `app/Application/`).

### Permissions
Container runs as `${UID:-1000}:${GID:-1000}`. Skip the `export UID/GID` and files in `backend/` end up owned by container root; recover with `sudo chown -R $USER:$USER backend/`.

## Backend architecture (intended)
Documented in `backend/README.md`. Most folders do not exist yet — create them rather than dropping code into the wrong layer. Eloquent models stay thin; `Domain/` must not `use Illuminate\*`.

```
app/Application/<Feature>/  # Use cases (orchestration)
app/Domain/<Feature>/       # Pure logic, no framework deps
  ├── Contracts/            # Repository interfaces
  └── DTOs/
app/Http/                   # Controllers, Middleware, Requests, Resources
app/Infrastructure/         # Eloquent repos, DB tx manager, external services
  ├── Database/
  ├── Repositories/
  └── Services/
app/Models/                 # Eloquent only — no business logic
app/Notifications/
app/Policies/
app/Providers/              # Service-container bindings live here
```

## Testing
Tests use SQLite `:memory:` (`backend/phpunit.xml`) — the Postgres container is **not** required for phpunit.

```bash
docker compose -f dev.docker-compose.yml exec app composer test
# single test:
docker compose -f dev.docker-compose.yml exec app php artisan test --filter=ExampleTest
```

## Production (not yet built)
The user has stated intent for a separate `prod.Dockerfile` / `prod.docker-compose.yml` with PHP-FPM + nginx reverse proxy. The dev container is **not** production-ready; do not deploy from `dev.Dockerfile`.
