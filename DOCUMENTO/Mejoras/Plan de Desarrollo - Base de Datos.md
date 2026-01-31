# Plan de Desarrollo - Base de Datos Principal (smartket_admin_db)

Este documento describe las mejoras y adiciones estratégicas para la base de datos principal, con el objetivo de construir una plataforma SaaS robusta, escalable y gestionable.

---

### 1. Estructura de Planes y Monetización

La estrategia de negocio ("Anclaje por Valor") se reflejará en la base de datos a través de la interacción de las tablas `plans`, `modules`, y la nueva tabla `subscriptions`.

-   **Plan Trial:** Se asignará a través de la tabla `subscriptions` con un `status` de `trialing` y una fecha en `trial_ends_at`. Los límites (2 usuarios, 1 sucursal) se aplicarán a nivel de la lógica de la aplicación.
-   **Plan Pro:** Será una suscripción activa (`status: 'active'`) en la tabla `subscriptions`.
-   **Add-Ons:** Se gestionarán como registros en la tabla `module_tenant`, que vincula a un `tenant` con un `module`. La columna `quantity` en esta tabla es clave:
    -   Para un "Paquete de Staff Adicional", `quantity` podría ser `1`, `2`, etc., representando cuántos paquetes ha comprado.
    -   Para un "Módulo de Inventario Avanzado", `quantity` sería `1` para indicar que está activo.

---

### 2. Mejoras a la Tabla `tenants`

#### 2.1. Columna `slug` (Prioridad: ALTA)
- **Tipo:** `string`, `unique`, `indexed`.
- **Propósito:** Añadir un identificador de negocio único, inmutable y amigable para la URL. Será la clave para el "Login Mágico" de los empleados.
- **Ejemplo:** "Pollería Joe #2" -> `polleria-joe-2`.

#### 2.2. Columna `logo_path` (Prioridad: MEDIA)
- **Tipo:** `string`, `nullable`.
- **Propósito:** Almacenar la ruta relativa al logo optimizado del negocio.

---

### 3. Nuevas Tablas para Funcionalidades Clave

#### 3.1. Tabla `staff_index` (Prioridad: ALTA)
- **Propósito:** Crear un "puente" o índice global para implementar el "Login Mágico" de forma eficiente.
- **Columnas:** `id`, `username` (unique), `tenant_id` (FK a `tenants`).

#### 3.2. Tabla `subscriptions` (Prioridad: ALTA)
- **Propósito:** Gestionar el ciclo de vida de las suscripciones de los clientes.
- **Columnas:** `id`, `tenant_id`, `plan_id`, `status`, `trial_ends_at`, `current_period_ends_at`, `provider`, `provider_subscription_id`.

#### 3.3. Tabla `system_events` (Prioridad: MEDIA)
- **Propósito:** Crear un registro de auditoría a nivel de sistema para eventos importantes.
- **Columnas:** `id`, `event_type`, `tenant_id`, `user_id`, `metadata`, `created_at`.

---

### 4. Nuevas Tablas para Seguridad y Control de Acceso (Post-MVP)

#### 4.1. Estructura de Permisos Granulares (RBAC)
- **Propósito:** Ofrecer un control detallado sobre las acciones que cada rol puede realizar, permitiendo una personalización avanzada de la seguridad.
- **Filosofía de Implementación ("Defaults Sensatos"):** El sistema vendrá con roles preconfigurados (Cajero, Mesero, etc.) que tendrán un conjunto de permisos lógicos ya asignados. El dueño del negocio podrá, si lo desea, entrar a una sección de "Configuración Avanzada" para modificar estos permisos o crear nuevos roles.
- **Cambios en la BD:**
    - **Tabla `permissions` (en la BD Principal):**
        - `id` (PK)
        - `name` (string, ej: "Anular Venta")
        - `key` (string, `unique`, ej: `sales.void`)
        - `module` (string, para agrupar permisos, ej: 'Ventas', 'Inventario')
    - **Tabla `permission_role` (en la BD del Tenant):**
        - `permission_id` (FK a `permissions.id`)
        - `role_id` (FK a `roles.id` en la BD del tenant)

#### 4.2. Control de Acceso por Horario
- **Propósito:** Permitir que el dueño del negocio defina "turnos" o ventanas de tiempo durante las cuales los empleados pueden iniciar sesión.
- **Cambios en la BD:** Se creará una tabla `shifts` en la base de datos de **cada tenant**.
- **Columnas:** `id`, `staff_id`, `day_of_week`, `start_time`, `end_time`.

---

### 5. Estrategias (No requieren cambios directos en BD Principal)

- **Procesamiento de Imágenes:** Se utilizará una librería en el backend (ej. Intervention Image) para optimizar los logos. La BD solo almacenará la ruta.
- **Lógica de Límites:** Las reglas de negocio (ej. "el Plan Pro tiene 5 usuarios") se aplicarán en la capa de la aplicación (API), consultando el `plan` del tenant y sus `add-ons` comprados (en `module_tenant`).

