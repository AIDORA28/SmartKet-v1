SmartKet – Configuración de Puertos y Entornos

- Frontend `smartket-app` (Vite):
  - Puerto dev: `5174` (configurado en `smartket-app/vite.config.js`).
  - Variables: `VITE_API_BASE_URL` (API), `VITE_LANDING_URL` (login del landing).

- Landing `smartket-landing` (Laravel + Vite para assets):
  - Servidor PHP: `php artisan serve --port=8002` (forzado en `composer.json` script `dev`).
  - Vite dev (HMR): `5174` (configurado en `smartket-landing/vite.config.js`).
  - Variables: `APP_URL_APP` apuntando a `http://localhost:5174`.

- API `smartket-api` (Laravel API + Vite para assets):
  - Servidor PHP: `php artisan serve --port=8000` (forzado en `composer.json` script `dev`).
  - Vite dev (HMR): `5174` (configurado en `smartket-api/vite.config.js`).
  - CORS: por defecto permite `http://127.0.0.1:8002` y `http://localhost:5174`.

Notas de compatibilidad
- Los cambios afectan sólo entornos de desarrollo. Producción usa `build` y no depende de puertos Vite.
- Si ejecutas múltiples Vite dev servers simultáneamente, evita conflicto de puerto o usa flags `--port` adicionales.
- Las redirecciones de autenticación usan variables de entorno con fallback al host local para evitar URLs absolutas de Laragon.

Comandos útiles
- `smartket-app`: `npm run dev` abre Vite en `5174`.
- `smartket-landing`: `composer run dev` inicia PHP en `8002` y Vite en `5174`.
- `smartket-api`: `composer run dev` inicia PHP en `8000` y Vite en `5174`.

