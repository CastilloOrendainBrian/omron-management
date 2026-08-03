# AGENTS.md

> Léelo completo antes de escribir código. Si una instrucción del usuario contradice este archivo, pregunta antes de romper la arquitectura.

## 1. Project
Backend de un sistema web de **control de peso corporal**. Datos manuales iniciales desde una báscula Omron (peso, estatura, BMI, %grasa, %músculo, grasa visceral, edad corporal, RM). Diseñado para crecer: plicometría, metas, dashboards, roles admin. Online y gratuito.

**Stack:** PHP 8.3+ · Laravel 13.x · PostgreSQL 15+ · `laravel/sanctum` · `spatie/laravel-permission` (backend). API REST versionada, prefijo `/api`.

## 2. Repo layout
- `backend/` — Laravel 13.8. Reglas específicas: [`backend/AGENTS.md`](backend/AGENTS.md). Skills de Laravel/PHP en `backend/.agents/skills/`.
- `frontend/` — vacío por ahora. Reglas: [`frontend/AGENTS.md`](frontend/AGENTS.md) cuando se defina el stack.
- `docs/schema.dbml` — fuente de verdad del modelo de datos (backend).
- `dev.Dockerfile`, `dev.docker-compose.yml` — en la **raíz**, no dentro de `backend/`.

## 3. Dev environment (Docker-only)
PHP/Composer/Node no están instalados en el host. Todo corre dentro de contenedores.

```bash
export UID=$(id -u) GID=$(id -g)
docker compose -f dev.docker-compose.yml up --build
```

Los detalles de qué instala el contenedor (Sanctum, Spatie, autoprovisioning, comandos `exec` específicos) viven en `backend/AGENTS.md`.

### Permissions
Los contenedores corren como `${UID:-1000}:${GID:-1000}`. Sin `export UID/GID`, los archivos quedan propiedad de root; recupera con `sudo chown -R $USER:$USER <workspace>/` (típicamente `backend/`).

## 4. Production (not yet built)
Pendiente: `prod.Dockerfile` + `prod.docker-compose.yml` con PHP-FPM + nginx reverse proxy. Los dev containers **no** son production-ready; no desplegar desde `dev.Dockerfile`.
