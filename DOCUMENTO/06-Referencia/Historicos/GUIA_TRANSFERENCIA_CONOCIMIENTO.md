# 📘 Guía de Transferencia de Conocimiento: SmartKet ERP

Esta documentación está diseñada para un nuevo desarrollador que hereda el proyecto **SmartKet**. Proporciona contexto, justificación técnica de la arquitectura actual y los pasos para migrar lógica de la versión anterior (`v5`) a la actual (`v1`).

---

## 1. Contexto y Visión
SmartKet es un **SaaS (Software as a Service)** de tipo **ERP** (Gestión de Recursos Empresariales).
*   **Misión:** Hacer que la gestión de un negocio sea tan intuitiva como usar una red social.
*   **Target:** Pequeñas y medianas empresas (PYMES), empezando por el vertical de "Pollerías".
*   **Objetivo Comercial:** Un flujo de "Free Trial" de 14 días donde el usuario se registra y el sistema le aprovisiona automáticamente su entorno de trabajo.

---

## 2. El Cambio de Arquitectura (v5 a v1)
Originalmente, el proyecto (`SmartKet-v5`) se construyó como un **Monolito** con **Inertia.js** y una **Base de Datos Compartida**. Se decidió migrar a la estructura actual (`v1`) por tres razones críticas:
1.  **Aislamiento de Datos:** La `v1` usa una base de datos física distinta para cada cliente. Esto es un requerimiento de seguridad de nivel empresarial.
2.  **Desacoplamiento:** Al usar una API JSON, el sistema está preparado para aplicaciones móviles futuras y integraciones externas.
3.  **Escalabilidad:** Cada componente (Landing, App, API) puede escalarse de forma independiente.

---

## 3. Arquitectura del Sistema (v1)
El sistema está compuesto por 3 macro-proyectos que conviven en el mismo repositorio:

### A. [smartket-api](file:///d:/TRAE/smartket-v1/smartket-api) (Laravel API)
*   **Rol:** El motor de lógica. No tiene vistas. Recibe y entrega JSON.
*   **Auth:** Usa Laravel Sanctum (Tokens).
*   **Multi-tenancy:** Usa el paquete `spatie/laravel-multitenancy`.

### B. [smartket-landing](file:///d:/TRAE/smartket-v1/smartket-landing) (Laravel Blade)
*   **Rol:** Marketing y puerta de entrada.
*   **Función:** Gestiona el registro y el login inicial. Cuando el usuario se loguea, recibe un token de la API y es redirigido a la `smartket-app`.

### C. [smartket-app](file:///d:/TRAE/smartket-v1/smartket-app) (Vue.js 3 SPA)
*   **Rol:** La herramienta de trabajo del cliente.
*   **Función:** Consume la API y muestra el Dashboard, Inventarios y Ventas.

---

## 4. Estrategia de Base de Datos (Multi-Tenant)
La característica técnica más avanzada de SmartKet es su manejo de BD:

1.  **Base de Datos Administrativa (`smartket_admin_db`):** 
    *   Gestiona los clientes (tabla `tenants`).
    *   Contiene las credenciales de conexión de cada base de datos individual.
2.  **Base de Datos de Cliente (Tenant DB):**
    *   Se crea automáticamente al momento del registro.
    *   Contiene solo los datos de ese negocio (sucursales, productos, ventas).
    *   **Comando Clave:** `php artisan tenants:artisan migrate` (Ejecuta migraciones en todas las DBs de clientes).

---

## 5. Guía de "Reciclaje" desde v5 (Para el desarrollador)
El desarrollador debe tomar la lógica ya escrita en `SmartKet-v5` y moverla a `v1`. Aquí los pasos detallados:

### A. Trasplante de Lógica Backend (Modelos y Controladores)
*   **Origen:** `SmartKet-v5/app/Http/Controllers/`
*   **Destino:** `smartket-api/app/Http/Controllers/`
*   **Qué hacer:** Toma los métodos de lógica (ej. calcular merma, registrar venta) y límpialos de cualquier referencia a `Inertia`. La API solo debe devolver JSON.
*   **Modelos:** Los modelos en `SmartKet-v5/app/Models` deben moverse a la carpeta `app/Models` de la API. Elimina la columna `empresa_id` de las búsquedas, ya que el aislamiento es por base de datos ahora.

### B. Trasplante de Diseño Frontend (Tailwind + Componentes)
*   **Origen:** `SmartKet-v5/resources/js/Layouts/` y `Components/`
*   **Destino:** `smartket-app/src/components/`
*   **Qué hacer:** Traduce los componentes de React (`.jsx`) a Vue 3 (`.vue`). Mantén todas las clases de Tailwind CSS para conservar el diseño de Sidebar y Navbar que ya fue aprobado.

---

## 6. Hoja de Ruta Inmediata
El nuevo desarrollador debe priorizar:
1.  **Levantar el entorno local:** Seguir los pasos de [README_DETAILED.md](file:///d:/TRAE/smartket-v1/README_DETAILED.md).
2.  **Sincronizar el diseño:** Migrar el Sidebar de la `v5` a la `smartket-app` (Vue).
3.  **Migrar el MVP de Pollería:** Asegurar que las tablas de Pedidos, Mesas e Insumos funcionen dentro del esquema Multi-tenant de la `v1`.
4.  **Implementar TypeScript:** Configurar TS en la `app` (Vue) para evitar errores de tipo en tiempo de ejecución.

---

## 7. Notas Técnicas de Desarrollo
*   **Puertos:** API (8000), Landing (8002), App (5174/5175).
*   **Interacción:** La Landing llama a la API vía Fetch -> Recibe Token -> Redirige a App con el Token.
*   **Variables de Entorno:** Ambas (`Landing` y `App`) necesitan conocer la URL de la API (`API_BASE_URL`).

---

**Firma:**
*Arquitecto Senior SmartKet* 🚀
