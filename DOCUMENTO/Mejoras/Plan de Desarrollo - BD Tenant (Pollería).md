# Plan de Desarrollo - Base de Datos del Tenant (Módulo Pollería) - Versión Internacional

Este documento describe la arquitectura de base de datos de calibre internacional para el módulo de "Pollería". El diseño está enfocado en control de costos, flexibilidad de marketing y fidelización de clientes, además de la operación diaria.

---

### Arquitectura de Tablas Detallada

#### 1. Núcleo Operativo (Flujo de Pedido a Venta)

- **`orders` y `order_items`:** El corazón del sistema. Registran la intención de compra y los detalles del pedido para la cocina. La estructura actual es sólida.
- **`sales` y `payments`:** El registro financiero. La estructura actual que separa la venta de sus pagos es robusta y se mantiene.
- **`customers`:** La base del CRM. Se mantiene.
- **`cashier_sessions`:** Fundamental para el control diario de caja. Se mantiene.

---

### 2. Gestión de Inventario y Costos (Prioridad: ALTA)

**Objetivo:** Dar al dueño un control total sobre su costo de mercancía vendida (COGS) y reducir el desperdicio.

- **`ingredients` y `recipe_items`:** Se mantiene la estructura para definir recetas.
- **NUEVA Tabla `suppliers` (Proveedores):**
    - `id` (PK), `name`, `contact_person`, `phone_number`, `email`.
- **NUEVA Tabla `purchase_orders` (Órdenes de Compra):**
    - `id` (PK), `supplier_id` (FK), `staff_id` (FK), `status` (`pending`, `received`), `total_cost`, `created_at`.
- **NUEVA Tabla `purchase_order_items`:**
    - `id` (PK), `purchase_order_id` (FK), `ingredient_id` (FK), `quantity`, `cost_per_unit`.
- **NUEVA Tabla `stock_adjustments` (Ajustes de Inventario):**
    - `id` (PK), `ingredient_id` (FK), `staff_id` (FK), `quantity` (decimal, puede ser negativo), `reason` (string, ej: `merma`, `desperdicio`, `corrección_conteo`).

---

### 3. Flexibilidad de Menú y Marketing (Prioridad: MEDIA)

**Objetivo:** Permitir la creación de combos, ofertas y modificadores, que son el motor de las ventas en un restaurante.

- **NUEVA Tabla `modifier_groups` (Grupos de Modificadores):**
    - `id` (PK), `name` (string, ej: "Elige tu Gaseosa", "Elige tus Cremas"), `selection_type` (`single`, `multiple`).
- **NUEVA Tabla `product_modifiers` (Modificadores):**
    - `id` (PK), `modifier_group_id` (FK), `name` (string, ej: "Inca Kola 1.5L"), `price_modifier` (decimal).
- **NUEVA Tabla `promotions` (Promociones):**
    - `id` (PK), `name` (string, ej: "Martes 2x1"), `type` (`bogo`, `discount`, `combo`), `rules` (JSON).

---

### 4. Fidelización de Clientes (CRM 2.0) (Prioridad: MEDIA)

**Objetivo:** Fomentar la recurrencia de clientes a través de un sistema de recompensas.

- **Modificaciones a `customers`:**
    - Añadir `loyalty_points` (integer, default 0).
- **NUEVA Tabla `loyalty_transactions`:**
    - `id` (PK), `customer_id` (FK), `order_id` (FK, nullable), `points_earned`, `points_spent`, `description`.

---

### 5. Estructura de Permisos (RBAC) (Prioridad: ALTA - Estructural)

- **NUEVA Tabla `permission_role` (Pivote):**
    - `permission_id` (FK a `permissions` en BD principal), `role_id` (FK a `roles` en BD del tenant).
- **Nota:** Esta tabla es fundamental para la seguridad y debe ser creada a través de una nueva migración en la carpeta `database/migrations/tenant`.
