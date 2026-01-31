# Estado de Base de Datos (MCP)

Este reporte muestra las tablas detectadas y un muestreo de datos relevantes para verificar posibles cruces de credenciales entre `users`, `tenants`, `tenant_user` y `personal_access_tokens`.

## Tablas detectadas (schema `public`)

- `cache`
- `cache_locks`
- `failed_jobs`
- `job_batches`
- `jobs`
- `migrations`
- `password_reset_tokens`
- `personal_access_tokens`
- `sessions`
- `tenant_user`
- `tenants`
- `users`

## Conteos rápidos

- `users`: 0
- `tenants`: 0
- `tenant_user`: 0
- `personal_access_tokens`: 2

## Muestreo de datos

Datos de `users` (top 20):

```json
[]
```

Datos de `tenants` (top 20):

```json
[]
```

Datos de `tenant_user` (top 50):

```json
[]
```

Datos de `personal_access_tokens` (top 20, sin columna `token`):

```json
[
  {
    "id": "1",
    "name": "api",
    "tokenable_id": "5",
    "tokenable_type": "App\\Models\\User",
    "abilities": "[\"*\"]",
    "last_used_at": null,
    "created_at": "2025-11-06T07:29:45.000Z",
    "updated_at": "2025-11-06T07:29:45.000Z"
  },
  {
    "id": "2",
    "name": "api",
    "tokenable_id": "5",
    "tokenable_type": "App\\Models\\User",
    "abilities": "[\"*\"]",
    "last_used_at": null,
    "created_at": "2025-11-06T07:39:38.000Z",
    "updated_at": "2025-11-06T07:39:38.000Z"
  }
]
```

## Observaciones

- Hay 2 registros en `personal_access_tokens` para `tokenable_id = 5` (`App\Models\User`), pero la tabla `users` está vacía (`COUNT = 0`).
- No hay `tenants` ni vínculos en `tenant_user`. Esto sugiere que:
  - Se eliminaron usuarios/tenants sin limpiar los tokens, o
  - Se está apuntando a un esquema/DB diferente cuando se crean usuarios y tenants.

## Riesgo de cruces de credenciales

- Tokens que referencian usuarios inexistentes pueden provocar estados inconsistentes durante login, especialmente si el flujo espera `tenant_id` asociado al usuario.

## Recomendaciones

- Limpieza de tokens huérfanos:

```sql
DELETE FROM personal_access_tokens t
WHERE NOT EXISTS (
  SELECT 1 FROM users u WHERE u.id = t.tokenable_id
);
```

- Volver a crear el usuario y vincularlo correctamente con `tenant` mediante Eloquent:

```php
$user->tenants()->attach($tenant->id);
// o
$user->tenants()->syncWithoutDetaching([$tenant->id]);
```

- Después de aplicar cambios en la API, ejecutar el flujo de registro y login nuevamente para regenerar tokens válidos y coherentes.

## Notas

- Este reporte se genera para auditoría rápida y convivirá junto a `DATABASE_SCHEMA.md` en la raíz del proyecto.

## Cambios aplicados (NULLs)

- Eliminadas columnas `created_at` y `updated_at` de `tenant_user` mediante migración `2025_11_06_120000_drop_timestamps_from_tenant_user.php` por ser NULLs sin utilidad en la tabla pivote.
- Verificación posterior:
  - Esquema actual de `tenant_user`: `id`, `tenant_id`, `user_id`.
  - Conteo de NULLs: `id_nulls = 0`, `tenant_id_nulls = 0`, `user_id_nulls = 0` sobre `total_rows = 1`.
- Decisiones:
  - `personal_access_tokens.expires_at` se mantiene NULL para tokens no expirables; `last_used_at` NULL indica nunca usado. Sin cambios.
  - `users.email_verified_at` y `remember_token` pueden ser NULL según el flujo actual (sin verificación de email / sin "remember me"). Sin cambios.
  - Campos opcionales de `audit_events` (`user_id`, `tenant_id`, `route`, `message`, `meta`) pueden ser NULL por diseño para eventos del sistema. Sin cambios.
