# Plan de Desarrollo - Próximos Pasos para SmartKet

Este documento describe las próximas funcionalidades clave que deben implementarse para completar el MVP (Producto Mínimo Viable) y expandir las capacidades del sistema.

---

### GAP #1: Implementar el Flujo de Login para Sub-Usuarios (Staff)

**Objetivo:** Permitir que tanto dueños como empleados inicien sesión desde una única interfaz simple, utilizando un sistema de "Login Mágico" que identifique al usuario y su contexto automáticamente.

**Arquitectura Elegida: Login Mágico con Índice Global de Staff**

Se mantendrá una única página de login con un solo campo ("Email o Nombre de Usuario"). El sistema será responsable de determinar si el identificador corresponde a un dueño o a un empleado.

*   **Flujo del Dueño:** Inicia sesión con su `email`.
*   **Flujo del Empleado:** Inicia sesión con su `username` (que debe ser único en todo el sistema, ej. `juan-polleria-joe`).

**Tareas Requeridas:**

1.  **Backend (Base de Datos - El "Puente"):**
    *   Crear una nueva migración para añadir una tabla `staff_index` en la base de datos **principal** (`smartket_admin_db`).
    *   Esta tabla contendrá solo `id`, `username` (único, indexado) y `tenant_id` (indexado). Servirá como un "puente" o índice para localizar rápidamente a qué negocio pertenece un empleado.

2.  **Backend (Lógica de Sincronización):**
    *   **Creación/Actualización de Staff:** Modificar la lógica del `StaffController` (o un `StaffService`) para que, cada vez que se cree, actualice o elimine un empleado en la base de datos de un tenant, se sincronice la entrada correspondiente en la tabla `staff_index` de la base de datos principal.
    *   **Garantizar `username` único:** La lógica de creación de `Staff` debe verificar que el `username` propuesto no exista ya en la tabla `staff_index` antes de crearlo.

3.  **Backend (Lógica de Login):**
    *   Modificar el `AuthController` y el endpoint `POST /api/login` para que sea "inteligente":
        1.  Recibe un identificador (`login_id`) y la `password`.
        2.  Primero, intenta encontrar un usuario en la tabla `users` (base de datos principal) donde el `email` coincida con `login_id`. Si lo encuentra, procede como un **login de dueño**.
        3.  Si no lo encuentra, busca en la tabla `staff_index` donde el `username` coincida con `login_id`.
        4.  Si encuentra una entrada en `staff_index`, obtiene el `tenant_id`.
        5.  Se conecta a la base de datos de ese tenant, busca al empleado en la tabla `staff` y verifica su contraseña. Si tiene éxito, procede como un **login de empleado**.
        6.  El token JWT generado debe contener los roles del usuario para que el frontend sepa qué interfaz mostrar.

4.  **Frontend (Vue.js):**
    *   **Vista de Login:** Simplificar la vista para que solo tenga un campo "Email o Nombre de Usuario" y "Contraseña".
    *   **Lógica de Login:** La función de login simplemente enviará el contenido de los dos campos al endpoint `POST /api/login`. Toda la complejidad la manejará el backend.

---

### GAP #2: Implementar Layouts y Vistas Dinámicas por Rol

**Objetivo:** Mostrar una interfaz de usuario (UI) completamente diferente dependiendo del rol del sub-usuario que ha iniciado sesión.

**Tareas Requeridas:**

1.  **Frontend (Vue.js):**
    *   Crear componentes de Layout específicos por rol:
        *   `CocinaLayout.vue`: Una vista de pantalla completa optimizada para la cocina.
        *   `MeseroLayout.vue`: Una interfaz táctil para tablets/móviles.
        *   `CajaLayout.vue`: Una interfaz de Punto de Venta (POS).
    *   Mejorar el **Vue Router**: Después de un login de staff exitoso, el router debe leer el rol del usuario (obtenido del token o de la respuesta de la API) y redirigirlo automáticamente al layout y vista correctos.

---

### GAP #3: Implementar la Lógica de Planes y Límites (Business Rules)

**Objetivo:** Hacer que la API imponga las restricciones de los planes de suscripción (ej. límite de usuarios en el plan Trial).

**Tareas Requeridas:**

1.  **Backend (Laravel):**
    *   Añadir "validaciones de negocio" en los controladores correspondientes.
    *   **Ejemplo (`StaffController`):** Antes de ejecutar la lógica para crear un nuevo `Staff`, el método debe:
        1.  Obtener el `Tenant` actual.
        2.  Cargar su `plan` y los límites definidos.
        3.  Contar el número actual de `Staff` en la base de datos del tenant.
        4.  Si `conteo_actual >= límite_del_plan`, devolver un error `402 Payment Required` con un mensaje claro.
    *   Aplicar una lógica similar para otras restricciones (ej. número de sucursales).
