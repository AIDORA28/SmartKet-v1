# Autenticación y Registro (smartket-api)

## Endpoints

- `POST /api/register`
  - Body: `{ nombre_negocio: string, email: string, password: string (>=8 con mayúscula y número), plan?: string, rubro: 'polleria' }`
  - Respuesta 201: `{ message: 'Registro exitoso. Tenant aprovisionado.' }`
  - Errores: 422 (validación), 500 (provisión fallida)
  - Seguridad: throttling `10 req/min` por IP.

- `POST /api/login`
  - Body: `{ email: string, password: string }`
  - Respuesta 200: `{ token: string, user: { id, name, email }, tenant_id: number|null }`
  - Errores: 401 (credenciales inválidas), 422 (validación)
  - Seguridad: throttling `10 req/min` por IP.

- `GET /api/me`
  - Auth: `Bearer <token>` (Sanctum)
  - Respuesta 200: `{ id, name, email, tenant_id, roles: string[] }`
  - Errores: 401 (no autenticado)

- `POST /api/logout`
  - Auth: `Bearer <token>` (Sanctum)
  - Respuesta 200: `{ message: 'Sesión cerrada' }`
  - Efecto: revoca el token actual.

## Flujo de Landing → App
- Landing (`login.blade.php`) envía credenciales a `/api/login`, guarda `token` y `tenantId` y redirige a la SPA con query `?token=...&tenantId=...`.
- SPA captura ambos, los guarda en `localStorage` y limpia la URL.
- Guard global usa `/api/me` como respaldo si falta `tenantId`.

## Estándares y Seguridad
- Validación estricta en controladores; uso de Query Builder/Eloquent para prevenir inyección SQL.
- Cifrado en modelo `Tenant` (`db_name`, `db_user`, `db_password`) y `FiscalSetting` (`ruc`, `razon_social`).
- Logging en servicio Pollería; cache para datos estáticos.
- Rate limiting en `/register` y `/login`.

## Pruebas
- `AuthTest`: valida token en login y datos de `/api/me`.
- `AuthFlowTest`: rechaza token inválido y confirma revocación en logout.

## Compatibilidad con smartket-app
- Headers: `Authorization: Bearer <token>` y `X-Tenant-ID: <id>` (SPA los adjunta automáticamente).
- Guards de SPA: redirección por `setup_complete` y roles; aseguran coherencia post-login.

