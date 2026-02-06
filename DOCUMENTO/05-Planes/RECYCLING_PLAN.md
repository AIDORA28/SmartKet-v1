# 🏗️ Plan de Reciclaje y Hoja de Ruta: De v5 a v1

Este documento detalla qué componentes y lógica de la versión `v5` (React/Inertia) podemos "robar" para fortalecer la versión `v1` (Vue/API/Multi-tenant) y define los pasos para que el proyecto alcance un nivel profesional/senior.

---

## ♻️ ¿Qué podemos reciclar de la v5?

### 1. Diseño y UI (Look & Feel)
Aunque `v5` usa React y `v1` usa Vue, el **CSS (Tailwind)** es universal.
*   **Sidebar y Navbar:** Copia la estructura de clases de Tailwind del Sidebar de la `v5`. Puedes recrear los mismos componentes en Vue 3 manteniendo la estética que te gustó.
*   **Aesthetics:** Los colores, gradientes y espaciados ya están definidos en el `tailwind.config.js` de la `v5`. Cópialos al de la `v1`.

### 2. Lógica de Dominio (Backend)
La lógica de negocio no depende del framework.
*   **Controladores y Servicios:** Los cálculos de inventario, mermas, cierres de caja y reportes de la `v5` son oro puro. 
    *   *Acción:* Toma los métodos de `SaleController`, `InventoryController` y `CajaController` de la `v5`.
    *   *Adaptación:* Muévelos a la API de la `v1`. En lugar de devolver una vista (`return Inertia::render(...)`), devuelven JSON (`return response()->json(...)`).
*   **Modelos y Scopes:** Los Scopes de Eloquent que filtran datos son reutilizables al 100%.

### 3. Esquema de Base de Datos (Estructura de tablas)
Las tablas de negocio (productos, ventas, clientes) en la `v5` están bien pensadas.
*   *Acción:* Usa las migraciones de la `v5` como base para las migraciones del "Tenant" en la `v1`. Solo asegúrate de eliminar cualquier columna `empresa_id` o `sucursal_id` que sea redundante ahora que cada cliente tiene su propia DB.

---

## 🚀 Hoja de Ruta: Pasos a seguir

### Paso 1: "El Trasplante" (Immediate)
1.  **Frontend:** Recrear el Sidebar y Navbar de la `v5` en Vue 3 dentro de `smartket-app`.
2.  **API:** Migrar los modelos de negocio desde `v5` a `smartket-api`.
3.  **Auth:** Asegurar que el Login en la `v1` redirija correctamente a la App con el Token de Sanctum.

### Paso 2: "Blindaje Senior" (Short Term)
1.  **TypeScript:** Configurar TypeScript en `smartket-app`. Un ERP sin tipos es una bomba de tiempo.
2.  **Validaciones robustas:** Usar Form Requests en Laravel para validar hasta el último coma de lo que llega a la API.
3.  **Facturación Electrónica:** Empezar a investigar la integración con proveedores de facturación (PSE o similar según tu país). Sin esto, el ERP es solo un Excel caro.

### Paso 3: "Escalabilidad Pro" (Mid Term)
1.  **Automatización de Tenants:** Mejorar el comando `CreateTenant` para que también cree las carpetas de almacenamiento (storage) aisladas para cada cliente.
2.  **PgBouncer:** Configurar un pool de conexiones para que el servidor no explote cuando tengas 100 usuarios concurrentes.

---

## 🛠️ Correcciones y Mejoras Críticas para la v1

1.  **Manejo de Errores Globales:** La API debe devolver errores consistentes. Si un cliente no tiene acceso a un módulo, debe recibir un `403 Forbidden` claro, no un error 500 de base de datos.
2.  **Storage Aislado:** Asegúrate de que las fotos de los productos de la Pollería A no sean accesibles por la URL de la Pollería B.
3.  **Logs por Tenant:** Implementar un sistema donde los errores de cada cliente se guarden por separado para facilitar el soporte técnico.

> [!TIP]
> **Consejo Senior:** No intentes hacer todo a la vez. Primero haz que la v1 se vea tan bien como la v5 (UI). Luego haz que sea tan inteligente como la v5 (Lógica). Finalmente, haz que sea más segura (TypeScript + Aislamiento).
