# AGENTS.md

> Léelo completo antes de escribir código. Si una instrucción del usuario contradice este archivo, pregunta antes de romper la arquitectura.

## 1. Project
Backend de un sistema web de **control de peso corporal**. Datos manuales iniciales desde una báscula Omron (peso, estatura, BMI, %grasa, %músculo, grasa visceral, edad corporal, RM). Diseñado para crecer: plicometría, metas, dashboards, roles admin. Online y gratuito.

**Stack:** PHP 8.3+ · Laravel 13.x · PostgreSQL 15+ · `laravel/sanctum` · `spatie/laravel-permission`. API REST versionada, prefijo `/api`.

## 2. Source of truth — `docs/schema.dbml`
`docs/schema.dbml` (DBML para dbdiagram.io) es **la** fuente de verdad del modelo de datos. Las migraciones deben coincidir exactamente con ese archivo (tipos, enums, FKs, índices, unicidad). Si modificas el modelo, actualiza `schema.dbml` en el mismo cambio.

## 3. Repo layout
- `backend/` — Laravel 13.8. Todo el código de la app vive aquí.
- `frontend/` — vacío. Consumirá la API.
- `docs/schema.dbml` — fuente de verdad del modelo de datos.
- `dev.Dockerfile`, `dev.docker-compose.yml` — en la **raíz**, no dentro de `backend/`.

## 4. Dev environment (Docker-only)
PHP/Composer no están instalados en el host. Todo `php artisan` / `composer` corre dentro del contenedor `app`.

```bash
export UID=$(id -u) GID=$(id -g)
docker compose -f dev.docker-compose.yml up --build
# app http://localhost:8000 · postgres localhost:5432
```

```bash
docker compose -f dev.docker-compose.yml exec app php artisan <cmd>
docker compose -f dev.docker-compose.yml exec app composer <cmd>
docker compose -f dev.docker-compose.yml exec app ./vendor/bin/pint
```

### First-run autoprovisioning
`dev.Dockerfile` `CMD` corre en cada arranque:
1. Si falta `backend/artisan` → `composer create-project laravel/laravel .` (primera vez; `backend/` debe estar vacío).
2. Si falta `vendor/laravel/sanctum` → `composer require laravel/sanctum` + `php artisan install:api` (crea `routes/api.php`, publica config/migración, cablea el guard de API).
3. Si falta `vendor/spatie/laravel-permission` → `composer require spatie/laravel-permission` + publica `config/permission.php` y la migración `*_create_permission_tables.php`.
4. `sed`-reescribe `DB_*` en `backend/.env` → `pgsql` / `db:5432` / `omron-management / postgres / password`.
5. `php artisan migrate --force || true`.
6. `exec php artisan serve` en `0.0.0.0:8000`.

Los pasos de paquetes están gateados por `vendor/<package>/`; no se re-ejecutan. Para forzar reinstalación: `rm -rf backend/vendor/<package>` + `up --build`.

Implicaciones:
- Las ediciones manuales a `DB_*` en `.env` se sobrescriben cada arranque. Para cambiarlas permanente: editar el `sed` de `dev.Dockerfile` **y** el env del servicio `db` en `dev.docker-compose.yml` para que coincidan.
- El `CMD` solo corre `php artisan serve`; no invoca `composer dev`. Levanta queue/pail/vite en shells `exec` separadas:
  ```bash
  docker compose -f dev.docker-compose.yml exec app php artisan queue:listen --tries=1
  docker compose -f dev.docker-compose.yml exec app php artisan pail
  docker compose -f dev.docker-compose.yml exec app npm run dev
  ```

### Permissions
El contenedor corre como `${UID:-1000}:${GID:-1000}`. Sin `export UID/GID`, los archivos en `backend/` quedan propiedad de root; recupera con `sudo chown -R $USER:$USER backend/`.

## 5. Architecture (Clean Architecture ligera)
Regla de dependencia — todo apunta hacia adentro, hacia Domain:

```
Http ─▶ Application ─▶ Domain ◀── Infrastructure
                              (implementa contratos)
```

- **Domain** — PHP puro. Sin Eloquent, sin facades, sin nada de Laravel. Contratos (interfaces), DTOs, y (cuando la lógica lo justifique) Enums / ValueObjects / Excepciones.
- **Application** — casos de uso. Orquestan Domain a través de contratos. Nunca conocen Eloquent ni HTTP. `Commands/` para escrituras, `Queries/` para lecturas/dashboards.
- **Infrastructure** — implementaciones concretas de los contratos de Domain (repos Eloquent, servicios externos, manejo de tx). **Agrupar por feature**, no por tipo.
- **Http** — capa de presentación. Controladores delgados: `Request → FormRequest valida → DTO → UseCase → Resource`. Cero lógica de negocio.
- **Models** — Eloquent puro. Casts, relaciones, scopes simples. Sin métodos de negocio.

Reglas no negociables:
- `Domain` no importa nada de Laravel, Infrastructure ni Http.
- `Application` solo importa de `Domain`.
- `Infrastructure` implementa interfaces de `Domain`.
- `Http` solo llama a `Application` (UseCases). Nunca llama directo a un Repository o Model.
- Bindings interfaz → impl en `Providers/` (un `RepositoryServiceProvider` separado del `AppServiceProvider`).

## 6. Folder structure
```
app/
├── Application/<Feature>/{Commands,Queries}/<Verb><Entity>UseCase.php
├── Domain/{Shared,<Feature>}/{Contracts,DTOs,Enums}/
├── Http/{Controllers,Middleware,Requests,Resources/<Entity>}/
├── Infrastructure/{Database,<Feature>/{Repositories,Services}}/
├── Models/<Entity>.php
├── Policies/<Entity>Policy.php
├── Notifications/
└── Providers/{AppServiceProvider,RepositoryServiceProvider}.php
```

## 7. Naming conventions
| Elemento | Patrón | Ejemplo |
|---|---|---|
| UseCase | `Verbo+Entidad+UseCase` | `CreateMeasurementSessionUseCase` |
| DTO | `Verbo+Entidad+DTO` | `CreateMeasurementSessionDTO` |
| Contrato repo | `Entidad+RepositoryInterface` | `MeasurementSessionRepositoryInterface` |
| Impl repo | `Eloquent+Entidad+Repository` | `EloquentMeasurementSessionRepository` |
| Form Request | `Verbo+Entidad+Request` | `StoreMeasurementSessionRequest` |
| Resource | `Entidad+Resource` / `Entidad+Collection` | `MeasurementSessionResource` |
| Rutas API | kebab-case plural | `/api/measurement-sessions` |

## 8. Data model (resumen)
Detalle completo en `docs/schema.dbml`.

- `users`, `user_profiles` — cuenta y demográficos (sexo, fecha de nacimiento; **la edad se calcula, nunca se guarda**).
- `devices` — básculas/dispositivos por usuario.
- `measurement_sessions` — tabla de hechos: un evento de medición.
- `anthropometric_measurements` — estatura, peso, BMI (1:1 con `measurement_sessions`).
- `body_composition_measurements` — %grasa, %músculo, grasa visceral, edad corporal, RM (1:1 con `measurement_sessions`).
- `goals` — metas de peso/composición corporal.
- `skinfold_protocols`, `skinfold_sites`, `skinfold_measurements`, `skinfold_measurement_details` — plicometría (modelada, no implementada aún).
- `roles`, `permissions`, `model_has_roles`, `model_has_permissions`, `role_has_permissions` — Spatie.
- Enums Postgres: `sex_enum`, `measurement_source_enum`, `goal_status_enum`, `activity_level_enum`.

## 9. Auth & permissions
- Roles vía Spatie: `super-admin`, `admin`, `user`. **No** agregar `coach` / `nutritionist` salvo que se pida explícitamente.
- **Spatie Permissions** = *qué puede hacer* un rol (p. ej. `create-reports`, `manage-users`).
- **Laravel Policies** = *sobre qué dato* puede actuar (p. ej. un `user` solo ve/edita sus propias `measurement_sessions`).
- Ambos mecanismos son **necesarios, no intercambiables**.

## 10. Code standards
- `declare(strict_types=1);` obligatorio en todo `Domain/` y `Application/`.
- DTOs como `final readonly class` con propiedades promovidas en el constructor.
- Clases `final` por defecto salvo razón explícita para extenderlas.
- Comentarios solo cuando el código no explica el "por qué"; evita comentarios redundantes con el nombre del método.
- **Todos los comentarios en archivos de código van en inglés** (PHPDoc, inline, notas de migración/seeder). La documentación en Markdown (incluido este archivo) puede ir en español.

## 11. Checklist — agregar una funcionalidad
1. DTO en `Domain/<Feature>/DTOs/`.
2. Contrato en `Domain/<Feature>/Contracts/`.
3. UseCase en `Application/<Feature>/Commands/` (escritura) o `Queries/` (lectura).
4. Repositorio en `Infrastructure/<Feature>/Repositories/`.
5. Binding interfaz → impl en el Provider correspondiente (típicamente `RepositoryServiceProvider`).
6. Model Eloquent en `Models/`.
7. Migración en `database/migrations/` que coincida con `docs/schema.dbml`.
8. Form Request, Controller y Resource en `Http/`.
9. Ruta en `routes/api.php`.
10. Policy si el recurso requiere autorización a nivel de objeto.

## 12. What NOT to do
- No poner lógica de negocio en Controllers ni en Models.
- No inyectar Eloquent Models en `Domain` ni en `Application`.
- No hacer queries directas a la DB fuera de `Infrastructure` (excepto lecturas de reportes ya resueltas en `Application/<Feature>/Queries`).
- No mezclar validación de formato (Form Requests) con reglas de negocio (Domain/Application).
- No exponer IDs autoincrementales de forma predecible en endpoints si el sistema se abre a terceros.

## 13. Testing
Tests usan SQLite `:memory:` (`backend/phpunit.xml`) — el contenedor de Postgres **no** se requiere para phpunit.
```bash
docker compose -f dev.docker-compose.yml exec app composer test
# un test:
docker compose -f dev.docker-compose.yml exec app php artisan test --filter=ExampleTest
```

## 14. Production (not yet built)
Pendiente: `prod.Dockerfile` + `prod.docker-compose.yml` con PHP-FPM + nginx reverse proxy. El dev container **no** es production-ready; no despliegues desde `dev.Dockerfile`.
