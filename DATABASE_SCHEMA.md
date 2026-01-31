# Esquema de Base de Datos

- Nombre de la base de datos: `smartket_admin_db`
- Número de tablas: `13`

## Tablas y columnas (schema `public`)

### public.cache
- `key`: character varying(255), NOT NULL
- `value`: text, NOT NULL
- `expiration`: integer, NOT NULL

### public.cache_locks
- `key`: character varying(255), NOT NULL
- `owner`: character varying(255), NOT NULL
- `expiration`: integer, NOT NULL

### public.failed_jobs
- `id`: bigint, NOT NULL
- `uuid`: character varying(255), NOT NULL
- `connection`: text, NOT NULL
- `queue`: text, NOT NULL
- `payload`: text, NOT NULL
- `exception`: text, NOT NULL
- `failed_at`: timestamp without time zone, NOT NULL

### public.job_batches
- `id`: character varying(255), NOT NULL
- `name`: character varying(255), NOT NULL
- `total_jobs`: integer, NOT NULL
- `pending_jobs`: integer, NOT NULL
- `failed_jobs`: integer, NOT NULL
- `failed_job_ids`: text, NOT NULL
- `options`: text, NULL
- `cancelled_at`: integer, NULL
- `created_at`: integer, NOT NULL
- `finished_at`: integer, NULL

### public.jobs
- `id`: bigint, NOT NULL
- `queue`: character varying(255), NOT NULL
- `payload`: text, NOT NULL
- `attempts`: smallint, NOT NULL
- `reserved_at`: integer, NULL
- `available_at`: integer, NOT NULL
- `created_at`: integer, NOT NULL

### public.migrations
- `id`: integer, NOT NULL
- `migration`: character varying(255), NOT NULL
- `batch`: integer, NOT NULL

### public.password_reset_tokens
- `email`: character varying(255), NOT NULL
- `token`: character varying(255), NOT NULL
- `created_at`: timestamp without time zone, NULL

### public.personal_access_tokens
- `id`: bigint, NOT NULL
- `tokenable_type`: character varying(255), NOT NULL
- `tokenable_id`: bigint, NOT NULL
- `name`: text, NOT NULL
- `token`: character varying(64), NOT NULL
- `abilities`: text, NULL
- `last_used_at`: timestamp without time zone, NULL
- `expires_at`: timestamp without time zone, NULL
- `created_at`: timestamp without time zone, NULL
- `updated_at`: timestamp without time zone, NULL

### public.sessions
- `id`: character varying(255), NOT NULL
- `user_id`: bigint, NULL
- `ip_address`: character varying(45), NULL
- `user_agent`: text, NULL
- `payload`: text, NOT NULL
- `last_activity`: integer, NOT NULL

### public.tenant_user
- `id`: bigint, NOT NULL
- `tenant_id`: bigint, NOT NULL
- `user_id`: bigint, NOT NULL

### public.tenants
- `id`: bigint, NOT NULL
- `created_at`: timestamp without time zone, NULL
- `updated_at`: timestamp without time zone, NULL
- `nombre_negocio`: character varying(255), NOT NULL
- `plan`: character varying(255), NOT NULL
- `rubro`: character varying(255), NOT NULL
- `db_name`: character varying(255), NOT NULL
- `db_user`: character varying(255), NOT NULL
- `db_password`: text, NOT NULL
- `setup_complete`: boolean, NOT NULL

### public.users
- `id`: bigint, NOT NULL
- `name`: character varying(255), NOT NULL
- `email`: character varying(255), NOT NULL
- `email_verified_at`: timestamp without time zone, NULL
- `password`: character varying(255), NOT NULL
- `remember_token`: character varying(100), NULL
- `created_at`: timestamp without time zone, NULL
- `updated_at`: timestamp without time zone, NULL

### public.audit_events
- `id`: bigint, NOT NULL
- `user_id`: bigint, NULL
- `tenant_id`: bigint, NULL
- `event_type`: character varying(255), NOT NULL
- `route`: character varying(255), NULL
- `message`: text, NULL
- `meta`: json, NULL
- `created_at`: timestamp without time zone, NULL
- `updated_at`: timestamp without time zone, NULL
