# API – Contratos iniciales (Roles, Permisos, Módulos)

> Nota: Estos contratos definen el comportamiento objetivo. La Fase 1 devuelve `roles: ['admin']` desde `/api/me` como scaffold temporal.

## Autenticación y Perfil
- `GET /api/me` (auth:sanctum, sin `NeedsTenant`) → `{ id, name, email, tenant_id, roles[] }`
- `POST /api/logout` → `{ message }`

## Tenants
- `GET /api/tenant` (auth:sanctum, header `X-Tenant-ID`) → estado del tenant.
- `PATCH /api/tenant` (auth:sanctum, header `X-Tenant-ID`) → actualizar `rubro`, `setup_complete`.

## Roles y Permisos (Fase 2)
- `GET /api/roles` (auth:sanctum, `X-Tenant-ID`) → lista de roles.
- `POST /api/roles` (auth:sanctum, `X-Tenant-ID`) → crear rol.
- `PATCH /api/roles/{id}` (auth:sanctum, `X-Tenant-ID`) → actualizar rol.
- `DELETE /api/roles/{id}` (auth:sanctum, `X-Tenant-ID`) → eliminar rol.
- `POST /api/roles/{id}/assign` (auth:sanctum, `X-Tenant-ID`) → asignar rol a usuario.

## Módulos y Planes (Fase 3)
- `GET /api/modules` (auth:sanctum, `X-Tenant-ID`) → estado de módulos (mesero, cocina, caja, delivery).
- `POST /api/modules/activate` (auth:sanctum, `X-Tenant-ID`) → activar módulos.
- `POST /api/modules/deactivate` (auth:sanctum, `X-Tenant-ID`) → desactivar módulos.

## Tiempo Real (Fase 4)
- `GET /api/notifications/stream` (auth:sanctum, `X-Tenant-ID`) → SSE/WebSocket para cocina/delivery.
- `POST /api/notifications` → emitir notificaciones.

## Onboarding
- `GET /api/setup/fiscal` y `POST /api/setup/fiscal` (auth:sanctum) → ya existentes.
- `GET /api/onboarding/steps` → pasos del wizard; `POST /api/onboarding/complete` → marcar completado.

## Respuestas de error
- 400: Falta `X-Tenant-ID` cuando corresponde.
- 401: Token inválido o ausente.
- 403: Falta de permiso/rol.
- 404: Recurso no encontrado.

