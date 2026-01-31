Actúa como un Ingeniero de Software Full-Stack Senior. Soy tu desarrollador y este es el plan para nuestro proyecto. Necesito que me ayudes a ejecutarlo.

**PROYECTO: SmartKet ERP**

**ARQUITECTURA GLOBAL:**
Vamos a construir un ERP SAAS llamado SmartKet. La arquitectura está separada en 3 proyectos que corren en Laragon:

1.  **`smartket-api`** (El Cerebro): Un proyecto `laravel new smartket-api --api`. Correrá en `http://smartket-api.test`.
2.  **`smartket-landing`** (El Marketing): Un proyecto `laravel new smartket-landing` (normal, con Blade). Correrá en `http://smartket-landing.test`.
3.  **`smartket-app`** (El ERP/Frontend): Un proyecto Vue.js 3 (`npm create vue@latest smartket-app`). Correrá en `http://localhost:3000`.

**STACK TÉCNICO:**
* **Backend:** Laravel 10+
* **Base de Datos:** PostgreSQL
* **Frontend:** Vue.js 3

---

**CONCEPTOS CLAVE (¡MUY IMPORTANTE!):**

1.  **ARQUITECTURA MULTI-TENANT:** Usaremos una arquitectura de BBDD aislada.
    * **BBDD Admin:** Tendremos UNA base de datos pública (`smartket_admin_db`) que yo ya creé manualmente. Esta BBDD almacena la lista de *todos* nuestros clientes (tenants) y los datos de conexión a *sus* bases de datos.
    * **BBDD Tenant (Cliente):** Cada cliente (ej. una pollería) tendrá su PROPIA base de datos PostgreSQL (ej. `db_cliente_123`).
2.  **APROVISIONAMIENTO AUTOMÁTICO:**
    * **REGLA:** Nosotros NUNCA creamos las BBDD de los clientes manualmente.
    * El sistema debe crearlas automáticamente cuando un nuevo usuario se registra en el `smartket-landing`.
    * Usaremos el paquete `spatie/laravel-multitenancy` para gestionar el cambio de conexión de BBDD en tiempo de ejecución.
3.  **MVP (Producto Mínimo Viable):**
    * Nuestro primer "vertical" o tipo de negocio es una **Pollería**. Las tablas del tenant deben reflejar esto.

---

**TAREAS A EJECUTAR (Aquí necesito tu ayuda):**

**TAREA 1: Configurar el `smartket-api` (El Cerebro Multi-Tenant)**

1.  **Instalar Spatie:** Ayúdame a instalar y configurar `spatie/laravel-multitenancy` en el proyecto `smartket-api` para que funcione con PostgreSQL.
2.  **Migración Admin:** Escribe la migración de Laravel para la BBDD `smartket_admin_db`. Necesito:
    * `tenants` (con campos: `nombre_negocio`, `plan`, `rubro`, `db_name`, `db_user`, `db_password` (encriptado), y `setup_complete` (boolean, default false)).
    * `users` (los dueños de los negocios, tabla de auth normal de Laravel).
    * `tenant_user` (tabla pivote para vincular `users` y `tenants`).
3.  **Migraciones Tenant (Pollería):** Escribe las migraciones para el tenant (plantilla). Estas deben ir en `database/migrations/tenant/`. Para el MVP de Pollería, necesito las tablas:
    * `sucursales`
    * `cash_registers` (cajas)
    * `staff` (sub-usuarios: meseros, cajeros)
    * `categories`
    * `products`
    * `mesas`
    * `pedidos` (con `status`, `mesa_id`, `staff_id`)
    * `pedido_items`
    * `sales` (ventas)
    * `sale_details`
4.  **Script de Aprovisionamiento (¡El más importante!):**
    * Escribe un Comando de Laravel (`php artisan make:command CreateTenant`).
    * Este comando debe aceptar (nombre_negocio, email_dueño, pass_dueño).
    * Debe ejecutar las siguientes acciones en orden:
        a. Crear un nuevo `user` en `smartket_admin_db`.
        b. Ejecutar los comandos SQL de PostgreSQL para crear una nueva BBDD (ej. `db_cliente_123`) y un nuevo USUARIO (ej. `user_cliente_123`) con su contraseña.
        c. Guardar `db_cliente_123`, `user_cliente_123` y la contraseña en un nuevo registro en la tabla `tenants` (de `smartket_admin_db`).
        d. Vincular el `user` y el `tenant` en la tabla pivote.
        e. Ejecutar las migraciones de `database/migrations/tenant/` SÓLO en la nueva `db_cliente_123`.
5.  **Endpoints:**
    * Crea el endpoint `POST /api/register` que llame a este comando `CreateTenant`.
    * Crea los endpoints de login (`POST /api/login`) usando Laravel Sanctum para emitir tokens.
6.  **CORS:** Ayúdame a configurar CORS en `smartket-api` para aceptar peticiones de `http://smartket-landing.test` y `http://localhost:3000`.

**TAREA 2: Configurar el `smartket-landing` (El Marketing)**

1.  Estoy en el proyecto `smartket-landing` (Laravel con Blade).
2.  Ayúdame a crear un `RegistroController` y una vista `registro.blade.php`.
3.  El formulario en Blade debe usar JavaScript (`fetch` o `axios`) para enviar los datos (nombre_negocio, email, password) al endpoint `http://smartket-api.test/api/register`.
4.  Haz lo mismo para una vista de Login que llame a `http://smartket-api.test/api/login` y, si tiene éxito, guarde el token en `localStorage` y redirija al usuario a `http://localhost:3000/dashboard`.

**TAREA 3: Configurar el `smartket-app` (El Frontend Vue)**

1.  Estoy en el proyecto `smartket-app` (Vue 3).
2.  Ayúdame a instalar `axios` y `vue-router`.
3.  Muéstrame cómo crear un "plugin" de `axios` (`/src/api.js`) que configure la `baseURL` a `http://smartket-api.test/api` y automáticamente adjunte el token de `localStorage` en los headers de todas las peticiones.
4.  Muéstrame cómo configurar `vue-router` para que las rutas (`/dashboard`, `/productos`) estén protegidas y redirijan a `/login` si no hay un token.