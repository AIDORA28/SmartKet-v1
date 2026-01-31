# Plan de Acción Inmediato: Reparaciones Críticas del Sistema

Este documento resume las tareas urgentes que deben completarse para estabilizar la arquitectura del sistema y alinear el código con la visión del proyecto. No se deben desarrollar nuevas funcionalidades hasta que estos puntos estén resueltos.

---

### Problema Central: Indisciplina Arquitectónica

La causa raíz de los problemas actuales es que la lógica del código no ha seguido los principios definidos en la documentación (SoC, DRY). La lógica de negocio crítica reside en los Controladores en lugar de estar encapsulada en Servicios, lo que ha hecho que el sistema sea frágil y difícil de depurar.

---

### Tareas a Modificar con Urgencia

#### 1. Reparar el Flujo de Login (Funcionalidad Incompleta)

-   **Deficiencia Actual:** El sistema **carece de un mecanismo para que los empleados (Staff) inicien sesión.** Esto invalida el concepto de "Plan Pro" y limita el sistema a un solo usuario por negocio.
-   **Acción Requerida:** Implementar la arquitectura de **"Login Mágico con Índice Global de Staff"**.
    -   **Paso 1 (BD):** Crear la tabla `staff_index` en la base de datos principal para que actúe como un "puente" de búsqueda.
    -   **Paso 2 (Lógica):** Modificar el `AuthController` para que el endpoint `POST /api/login` sea "inteligente": que pueda diferenciar entre un `email` de dueño y un `username` de empleado, buscando en el `staff_index` para encontrar el tenant correcto.
    -   **Paso 3 (Sincronización):** Modificar la lógica de creación/actualización de `Staff` para que mantenga la tabla `staff_index` siempre sincronizada.

#### 2. Reparar el Registro de Nuevos Clientes (Inconsistencia de Datos)

-   **Deficiencia Actual:** El comando `CreateTenant` está **desactualizado** respecto a la nueva arquitectura de base de datos que hemos planeado. Si un nuevo cliente se registra ahora, su cuenta se creará en un estado **inconsistente y roto**.
-   **Acción Requerida:** Actualizar **inmediatamente** el comando `CreateTenant`.
    -   **Paso 1 (Slug):** Añadir la lógica para generar un `slug` único y guardarlo en la nueva columna de la tabla `tenants`.
    -   **Paso 2 (Suscripción):** En lugar de solo escribir en la tabla `tenants`, el comando debe crear el registro de suscripción inicial en la nueva tabla `subscriptions` (con `status: 'trialing'`).

#### 3. Reparar la Arquitectura del Frontend (Fragilidad Estructural)

-   **Deficiencia Actual:** El frontend carece de un "cerebro" central para gestionar el estado, lo que provoca la "página en blanco" y condiciones de carrera.
-   **Acción Requerida:** Formalizar la arquitectura de estado que comenzamos a implementar.
    -   **Paso 1 (Estado):** Asegurarse de que el `authStore` sea la **única fuente de verdad** para los datos del usuario y del tenant.
    -   **Paso 2 (Navegación):** Confirmar que el `Navigation Guard` (`router.beforeEach`) es el **único responsable** de proteger las rutas y gestionar el flujo de redirección basado en el estado de autenticación.
    -   **Paso 3 (Separación de Responsabilidades):** Implementar la llamada al nuevo endpoint `/api/tenant/entitlements` **después** de que el usuario haya iniciado sesión y esté en el `AppLayout`, separando la autenticación inicial de la carga de datos complejos.

---

### Orden de Implementación Sugerido

1.  **Crear la migración** que actualiza la base de datos con todas las nuevas tablas y columnas.
2.  **Actualizar el comando `CreateTenant`** para que sea compatible con la nueva estructura.
3.  **Implementar la lógica del "Login Mágico"** en el `AuthController`.
4.  **Ajustar el frontend** para que utilice la nueva arquitectura de estado y los nuevos endpoints.
