# AGENTS.md — Sistema de Control de Peso (Frontend)

> Contexto para agentes de IA (Claude Code, Cursor, Codex CLI, etc.) que trabajen en este repositorio.
> Léelo completo antes de escribir código. Este frontend consume la API definida en el backend (`AGENTS.md` / `docs/schema.dbml` de ese repo) bajo el prefijo `/api/v1`.

## 1. Resumen del proyecto

SPA en Vue 3 para el sistema de control de peso: registro de mediciones de báscula (peso, BMI, % grasa, % músculo, grasa visceral, edad corporal, RM), tendencias/gráficas, metas de peso, y un panel de administración de usuarios. Consume la API REST del backend Laravel.

## 2. Stack técnico

- Vue 3 + TypeScript + Vite
- Vue Router
- Pinia (solo estado de cliente/UI)
- @tanstack/vue-query (todo el estado de servidor: fetch, cache, invalidación)
- Axios
- Vee-Validate + Yup para formularios
- Vue Toastification para notificaciones
- Tailwind CSS
- vue3-apexcharts para gráficas de tendencia (alternativa más ligera: Chart.js / vue-chartjs)
- Vitest + Vue Test Utils
- ESLint + Prettier + vue-tsc

Asunción: repositorio separado del backend (SPA consumiendo una API externa vía `VITE_API_URL`). Si terminan en un monorepo, ajustar rutas de este documento en consecuencia.

## 3. Principio de arquitectura: estado de cliente vs. estado de servidor

Regla no negociable, causa más bugs si se rompe:

- **Pinia** → estado que vive solo en el cliente y no viene de una respuesta HTTP: sesión (`token`, `user`, `authStatus`), preferencias de UI (sidebar, tema).
- **Vue Query** → cualquier dato que viene del backend: mediciones, metas, usuarios, catálogos. Nunca copiar una respuesta del API a un store de Pinia "para tenerla a mano" — eso duplica la fuente de verdad y produce datos obsoletos. Si un componente necesita datos de servidor, usa un composable de Vue Query, no un store.

## 4. Estructura de carpetas

```text
src/
├── api/
│   └── apiClient.ts            # instancia de Axios, baseURL = VITE_API_URL, interceptor de auth
├── assets/
│   └── main.css
├── config/
│   └── yup.ts
├── icons/
│   └── *.vue
├── types/
│   └── api/                     # tipos que reflejan los Resources del backend (fuente: docs/schema.dbml)
│       ├── MeasurementSession.ts
│       ├── AnthropometricMeasurement.ts
│       ├── BodyCompositionMeasurement.ts
│       ├── Goal.ts
│       └── User.ts
├── modules/
│   ├── auth/
│   │   ├── actions/              # login, register (si aplica), refresh de sesión
│   │   ├── guards/                # is-authenticated, is-not-authenticated, has-role
│   │   ├── interfaces/
│   │   ├── layouts/
│   │   ├── routes/
│   │   ├── stores/                 # auth.store.ts (Pinia)
│   │   └── views/
│   ├── measurements/               # núcleo: CRUD de mediciones de báscula
│   │   ├── actions/
│   │   ├── components/              # MeasurementForm, MeasurementListItem
│   │   ├── composables/             # useMeasurementSessionsQuery, useCreateMeasurementSessionMutation
│   │   ├── interfaces/
│   │   ├── routes/
│   │   └── views/
│   ├── dashboard/                   # gráficas y tendencias
│   │   ├── components/               # WeightTrendChart, BodyFatChart, VisceralFatChart
│   │   ├── composables/
│   │   └── views/
│   ├── goals/
│   │   ├── actions/
│   │   ├── components/
│   │   ├── interfaces/
│   │   └── views/
│   ├── profile/                     # sexo, fecha de nacimiento, estatura de referencia, actividad
│   │   ├── actions/
│   │   ├── components/
│   │   ├── interfaces/
│   │   └── views/
│   ├── admin/                       # solo roles admin/super-admin
│   │   ├── layouts/
│   │   ├── routes/
│   │   ├── components/
│   │   └── views/
│   ├── skinfold/                    # [futuro] plicometría, placeholder, no implementar aún
│   └── common/
│       ├── components/               # inputs, botones, loaders, tablas
│       └── composables/
├── router/
│   └── index.ts
└── main.ts
```

## 5. Convenciones de nombres

| Elemento | Patrón | Ejemplo |
|---|---|---|
| Action | `verbo+Entidad+Action.ts` | `createMeasurementSessionAction.ts` |
| Composable de query | `use+Entidad+Query.ts` | `useMeasurementSessionsQuery.ts` |
| Composable de mutación | `use+Verbo+Entidad+Mutation.ts` | `useCreateMeasurementSessionMutation.ts` |
| Interface/tipo | `Entidad.interface.ts` | `MeasurementSession.interface.ts` |
| Store (Pinia, solo cliente) | `feature.store.ts` | `auth.store.ts` |
| Guard | `kebab-case.guard.ts` | `has-role.guard.ts` |
| Archivo de rutas | `featureRoutes.ts` | `measurementsRoutes.ts` |
| Vista | `PascalCaseView.vue` | `MeasurementListView.vue` |
| Componente | `PascalCase.vue` | `WeightTrendChart.vue` |
| Layout | `PascalCaseLayout.vue` | `AdminLayout.vue` |

## 6. Autenticación y roles

- Roles esperados (deben coincidir con Spatie en el backend): `super-admin`, `admin`, `user`.
- **Preferido**: autenticación SPA de Sanctum basada en cookies (`withCredentials: true`, cookie CSRF) si el frontend y el backend comparten dominio raíz. Es más segura para datos de salud personales que guardar un token en `localStorage`.
- **Alternativa** (dominios distintos): token Bearer de Sanctum guardado en el store de Pinia, no persistido en `localStorage` plano; si necesitas persistencia entre recargas, evalúa el riesgo de XSS antes de usar `localStorage`.
- `apiClient.ts` agrega el token/credenciales a cada request mediante interceptor de Axios.
- Guards de ruta: `is-authenticated.guard.ts` (requiere sesión), `has-role.guard.ts` (requiere uno de los roles indicados). Las rutas de `modules/admin` van protegidas con `has-role(['admin', 'super-admin'])`.
- Un usuario `user` normal solo puede ver/editar sus propios datos — esto ya lo aplica el backend con Policies; el frontend no debe asumir que ocultar un botón es suficiente control de acceso, solo mejora la UX.

## 7. Datos de servidor con Vue Query

- Cada módulo con datos remotos expone composables en `composables/`, no llamadas a Axios sueltas dentro de componentes.
- Query keys jerárquicas y consistentes: `['measurement-sessions']`, `['measurement-sessions', id]`, `['goals', userId]`.
- Invalidar queries relacionadas tras cada mutación (crear una medición invalida `['measurement-sessions']` y cualquier query de dashboard que dependa de ella).
- Usar `staleTime` razonable para catálogos que cambian poco (roles, protocolos de plicometría) y más agresivo para datos que el usuario edita seguido.

## 8. Gráficas

- Librería: `vue3-apexcharts` para tendencias (peso, BMI, % grasa/músculo, grasa visceral) — soporta zoom y bandas de referencia (útiles para clasificación de BMI o umbral de grasa visceral) sin plugins adicionales.
- Los componentes de gráfica viven en `modules/dashboard/components/`, reciben datos ya transformados (no lógica de agregación dentro del componente de gráfica — esa lógica va en un composable o se resuelve en el backend si es agregación pesada).

## 9. Formularios

- Vee-Validate + Yup, con esquemas en `config/yup.ts` o junto al formulario si es específico de un módulo.
- Validación de formato en el frontend (rangos razonables, campos requeridos) no reemplaza la validación de negocio del backend — el frontend debe mostrar los errores 422 que devuelva la API, no asumir que su propia validación es suficiente.

## 10. Variables de entorno

```env
VITE_API_URL=https://api.tudominio.com/api/v1
```

Usada como `baseURL` en `src/api/apiClient.ts`.

## 11. Testing

- Vitest + Vue Test Utils.
- Tests colocados junto al archivo que prueban: `Componente.spec.ts`, `composable.spec.ts`.
- Mockear `actions`/Axios en tests de componentes y composables — no golpear la API real.
- Todo composable de mutación nuevo requiere al menos un test de éxito y uno de error (422/403).

## 12. Checklist para agregar una funcionalidad nueva

1. Tipo/interface en `types/api/` o `modules/{feature}/interfaces/` (que coincida con el Resource del backend).
2. Action(s) en `modules/{feature}/actions/`.
3. Composable de Vue Query en `modules/{feature}/composables/` si el dato se reutiliza en más de una vista.
4. Componente(s) y vista(s).
5. Ruta en `modules/{feature}/routes/`, registrada en `router/index.ts`.
6. Guard si la ruta requiere sesión o rol específico.
7. Store de Pinia solo si es genuinamente estado de cliente (ver sección 3).
8. Tests (composable + componente).
9. `eslint`, `prettier`, `vue-tsc --noEmit` en verde.

## 13. Setup inicial — pasos para el agente

1. `npm create vite@latest . -- --template vue-ts`
2. Instalar dependencias: `vue-router`, `pinia`, `@tanstack/vue-query`, `axios`, `vee-validate`, `yup`, `vue-toastification`, `vue3-apexcharts`, `tailwindcss`.
3. Configurar Tailwind y `main.css`.
4. Crear `src/api/apiClient.ts` con `baseURL` desde `VITE_API_URL` e interceptor de autenticación.
5. Crear la estructura de carpetas de la sección 4.
6. Implementar el módulo `auth` de punta a punta primero: login → guard → store → ruta protegida de prueba. Verificar contra el backend real antes de seguir.
7. Implementar `measurements` (listar + crear sesión de medición) como segundo flujo de referencia, usando Vue Query.
8. Implementar `dashboard` con al menos una gráfica de tendencia de peso, alimentada por los datos de `measurements`.
9. Configurar ESLint, Prettier, Vitest.
10. Confirmar `npm run test` y `npm run type-check` en verde antes de continuar con más módulos.

## 14. Qué NO hacer

- No hacer `fetch`/Axios directo dentro de un componente — siempre a través de una action + composable de Vue Query.
- No duplicar datos de servidor en un store de Pinia.
- No guardar el token de Sanctum en `localStorage` si la alternativa de cookies (mismo dominio) es viable.
- No poner lógica de agregación/cálculo pesado en componentes de gráfica — transformarla antes, en un composable.
- No asumir que ocultar un botón por rol es control de acceso suficiente; el backend es la autoridad.
- No hardcodear la URL del API — siempre `VITE_API_URL`.
