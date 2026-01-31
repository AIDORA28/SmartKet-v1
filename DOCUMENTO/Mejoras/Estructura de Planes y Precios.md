# Estructura de Planes y Precios de SmartKet

Este documento define la estrategia de monetización, los planes de suscripción y los add-ons opcionales para el sistema SmartKet, siguiendo una filosofía de "Anclaje por Valor".

---

### Filosofía: Anclaje por Valor

El objetivo del período de prueba no es limitar al usuario, sino integrar SmartKet tan profundamente en su operación diaria que se vuelva indispensable. Al final de la prueba, el valor de mantener el sistema (con todos sus datos y configuraciones) superará con creces el costo de la suscripción.

---

### 1. Planes de Suscripción

#### **Plan Trial (Gratis por 14 días)**

- **Objetivo Estratégico:** Lograr el "anclaje" del producto en el negocio del cliente.
- **Límites y Características:**
    - **Duración:** 14 días.
    - **Sucursales:** 1.
    - **Usuarios (Staff):** Hasta 2 en total (1 Dueño/Admin + 1 Empleado con cualquier rol, ej. 'caja').
    - **Productos:** Ilimitados.
    - **Ventas/Transacciones:** Ilimitadas.
    - **Funcionalidades:** Acceso **completo** a todas las características incluidas en el Plan Pro. Esto es crucial para que el usuario experimente todo el potencial del sistema.

#### **Plan Pro (Suscripción de Pago)**

- **Objetivo Estratégico:** Ser la solución estándar para la mayoría de los negocios en operación.
- **Límites y Características:**
    - **Sucursales:** 1 (ampliable con Add-ons).
    - **Usuarios (Staff):** Hasta 5 en total (ampliable con Add-ons).
    - **Productos:** Ilimitados.
    - **Ventas/Transacciones:** Ilimitadas.
    - **Funcionalidades Incluidas:**
        - Punto de Venta (POS).
        - Gestión de Menú y Productos.
        - Pantalla de Cocina en tiempo real.
        - Gestión de Inventario Básico.
        - Reportes de Ventas (diarios, semanales, mensuales).
        - Gestión de Clientes (CRM simple).

---

### 2. Add-Ons (Compras Opcionales para el Plan Pro)

Los add-ons ofrecen flexibilidad y son una fuente clave de ingresos adicionales.

#### **Add-on: "Paquete de Staff Adicional"**
- **Descripción:** "Añade 5 puestos de empleado adicionales a tu plan."
- **Lógica de Negocio:** Aumenta el límite de `Staff` en la sucursal principal. Un cliente puede comprar este add-on múltiples veces.

#### **Add-on: "Sucursal Adicional"**
- **Descripción:** "Abre y gestiona una nueva sucursal desde tu misma cuenta."
- **Lógica de Negocio:**
    - Aumenta el límite de `branches` (sucursales) permitidas.
    - **Incluye un paquete base de 5 usuarios (Staff)** para ser asignados a esa nueva sucursal.

#### **Add-on: "Módulo de Inventario Avanzado" (Ejemplo Futuro)**
- **Descripción:** "Control total de tu stock, recetas, costos, proveedores y órdenes de compra."
- **Lógica de Negocio:** Desbloquea una sección completamente nueva en la interfaz de la aplicación.

#### **Add-on: "Módulo de Integración con Delivery Apps" (Ejemplo Futuro)**
- **Descripción:** "Recibe pedidos de Rappi, PedidosYa, etc., directamente en tu Punto de Venta."
- **Lógica de Negocio:** Activa una integración con APIs de terceros.
