# smartket-app — Dashboard limpio y rutas consistentes

Este documento resume la estructura final del dashboard, las rutas de Vue Router y las dependencias necesarias, tras la limpieza de duplicidades.

## Estructura de archivos (src)

- `src/App.vue`: punto de entrada con `router-view` principal.
- `src/main.js`: montaje de Vue, registro de router y plugin de API.
- `src/api.js`: instancia Axios (`api`) y plugin Vue (`$api`), headers `Authorization` y `X-Tenant-ID`, helper `audit`.
- `src/layouts/AppLayout.vue`: layout con sidebar y contenido principal, incluye `SetupPrompt` de aviso fiscal y `router-view` para las rutas privadas.
- `src/components/SetupPrompt.vue`: modal de aviso de configuración fiscal una sola vez.
- `src/router/index.js`: rutas limpias bajo `/app/...`, sin `alias` duplicados.
- `src/views/Home.vue`: vista pública de ejemplo.
- `src/views/Dashboard.vue`: vista privada del dashboard.
- `src/views/Productos.vue`: listado simple de productos; importa `api` desde `@/api`.
- `src/views/Onboarding.vue`: pantalla de configuración inicial; persiste `setup_complete` y `rubro`.
- `src/views/BusinessTypeSettings.vue`: selección y guardado de tipo de negocio.
- `src/views/SetupFiscal.vue`: formulario de datos fiscales.
- `src/views/polleria/*`: vistas del módulo Pollería (Mesero, Cocina, Caja, Delivery, Almacén, Admin).

## Rutas de Vue Router

- Privadas (bajo layout `AppLayout`):
  - `/app/dashboard` → `Dashboard`
  - `/app/productos` → `Productos`
  - `/app/settings/business-type` → `BusinessTypeSettings`
  - `/app/onboarding` → `Onboarding`
  - `/app/setup/fiscal` → `SetupFiscal`
  - `/app/polleria/*` → Vistas Pollería (control de acceso por roles)
- Pública:
  - `/` → `Home`

Notas:
- Se retiraron los `alias` (ej. `/dashboard`) para evitar coincidencias múltiples y posibles renders duplicados. Navegación del sidebar actualizada a rutas explícitas `/app/...`.
- El guard global mantiene autenticación, obtención de `tenantId`, aviso suave si el setup está incompleto y redirección automática a `polleria-admin` cuando procede.

## Dependencias

- `vue` (3.x)
- `vue-router` (4.x)
- `axios` (1.x)
- Dev: `vite`, `@vitejs/plugin-vue`, `vite-plugin-vue-devtools`, `vitest`, `@vue/test-utils`, `jsdom`

No se requieren dependencias adicionales para el dashboard actual.

## Prueba rápida

1. Ejecuta `npm run dev -- --port 5175` (si el puerto está ocupado, Vite elegirá otro).
2. Abre `http://localhost:<puerto>/app/dashboard` y verifica que el contenido no se duplique.
3. Usa el sidebar para navegar por `/app/productos` y rutas del módulo Pollería según roles.
4. Si la configuración está incompleta y aún no has visto el aviso, el modal `SetupPrompt` se mostrará una sola vez.

## Consideraciones

- Las vistas y componentes obsoletos/duplicados no están presentes en `src`; el proyecto quedó limpio en torno al dashboard.
- Se estandarizó el import de `api` en `Productos.vue` a `@/api` para consistencia.

