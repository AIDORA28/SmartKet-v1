# Módulo Pollería (Frontend)

## Resumen
- Implementa pantallas por rol: Mesero, Cocina, Caja, Delivery, Almacén, Administrador.
- Rutas protegidas por rol con guardas en `src/router/index.js`.
- Integración con backend vía `src/api.js` (headers `Authorization` y `X-Tenant-ID`).

## Rutas y Roles
- `'/app/polleria/mesero'` — meta.roles: `['mesero']`
- `'/app/polleria/cocina'` — meta.roles: `['cocina']`
- `'/app/polleria/caja'` — meta.roles: `['caja']`
- `'/app/polleria/delivery'` — meta.roles: `['delivery']`
- `'/app/polleria/almacen'` — meta.roles: `['almacen']`
- `'/app/polleria/admin'` — meta.roles: `['admin']`

El guard global obtiene roles de `localStorage.smartket_roles` (JSON array) o del backend `GET /api/me` (`roles[]` o `role`). Si el usuario no tiene el rol requerido, se redirige a `'/app/dashboard'` y se registra auditoría.

## Vistas y Endpoints
- Mesero (`MeseroOrders.vue`): `GET /polleria/menu`, `POST /polleria/orders`.
- Cocina (`CocinaScreen.vue`): `GET /polleria/kitchen/orders`, `PUT /polleria/orders/{id}/prepared`.
- Caja (`CajaPOS.vue`): `GET /polleria/menu`, `POST /polleria/sales`.
- Delivery (`DeliveryScreen.vue`): `GET /polleria/delivery`, `PATCH /polleria/delivery/{id}`.
- Almacén (`AlmacenInventory.vue`): `GET /inventory/items`, `PATCH /inventory/items/{id}`.
- Administrador (`AdminPanel.vue`): `GET /polleria/plans`, `POST /polleria/plans/activate`, `GET /polleria/branches`, `POST /polleria/branches`.

Notas:
- Si el backend aún no expone estos endpoints, las vistas manejan errores y muestran mensajes sin romper la UI.
- Entorno real-time Delivery usa polling configurable: `VITE_REALTIME_POLL_INTERVAL_MS` (por defecto: `10000 ms`).

## Navegación
- Sección “Pollería” añadida en `AppLayout.vue`, visible por rol (`canAccess(role)` usa roles del usuario o `admin`).

## Diseño Responsive
- Se siguen guías básicas del documento “Guía de Buenas Prácticas y Filosofía de Desarrollo para SmartKet.md”.
- Layouts con `grid` y `flex`, controles accesibles y títulos claros.

## Seguridad
- Lógica de guardas respeta `smartket_token` y `smartket_tenant_id`.
- Auditoría de eventos con `api.post('/audit')`.
- Limpieza de credenciales al recibir 401 desde interceptores.

## Pruebas
- Vitest configurado (`vitest.config.js`).
- Ejecutar: `npm run test:unit`.
- Pruebas incluidas: render básico de `MeseroOrders.vue` y `DeliveryScreen.vue`.

## Compatibilidad de Puertos
- Frontend (Vite) se ejecuta preferentemente en `5174`. Si está ocupado, Vite variará al siguiente puerto disponible; usar la URL mostrada por la terminal.
- Backend: `smartket-api` corre en `8000`; `smartket-landing` en `8002`.

## Variables de Entorno Clave (smartket-app)
- `VITE_API_BASE_URL`: URL base del backend (`http://localhost:8000/api`).
- `VITE_LANDING_URL`: URL del landing (`http://localhost:8002`).
- `VITE_REALTIME_POLL_INTERVAL_MS`: intervalo de polling para Delivery.

## Cómo probar roles localmente
En el navegador, establecer `localStorage.smartket_roles` con el arreglo de roles del usuario, por ejemplo:
```js
localStorage.setItem('smartket_roles', JSON.stringify(['mesero']))
```
Refrescar y acceder a la ruta correspondiente.

