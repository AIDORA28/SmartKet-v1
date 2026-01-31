# Plan de Desarrollo del Dashboard – SmartKet ERP

## Objetivo
Entregar un Dashboard completo y modular para la gestión de negocios, con perfiles por rol (dueño, mesero, cocina, cajero, delivery), expansión por planes y add-ons, y onboarding de primer ingreso.

## Alcance Fase 1 (Scaffold funcional)
- Roles en `/api/me` (scaffold): devolver `roles: ['admin']` para habilitar guardas de la SPA.
- Rutas y vistas base en la SPA: dashboard, módulos Pollería (mesero, cocina, caja, delivery), onboarding, settings de rubro.
- API base: endpoints de tenant (`GET/PATCH /api/tenant`) ya existentes; auditoría; login/registro.

## Alcance Fase 2 (Roles y permisos reales)
- Modelo de roles por tenant (DB del tenant) y asignación a usuarios.
- Middleware de autorización por rol/permiso.
- Endpoints: listar/crear/editar/eliminar roles; asignar roles a usuarios.

## Alcance Fase 3 (Planes y add-ons)
- Modelo de plan y add-ons por tenant.
- Endpoints de activación/desactivación de módulos (mesero, cocina, delivery).
- Integración de pagos recurrentes (futuro) y estado de suscripción.

## Alcance Fase 4 (Tiempo real)
- Notificaciones y eventos en tiempo real para cocina y delivery.
- Canal de pedidos (mesero -> cocina -> caja -> delivery).

## Onboarding (Primer Ingreso)
- Wizard de configuración inicial: rubro, datos fiscales, módulos base.
- Tutorial guiado para el dueño de negocio.

## Pruebas
- Validación de permisos por rol (unit/integration en API y e2e en SPA).
- Pruebas de escalabilidad para módulos adicionales.
- Test de usabilidad por interfaz especializada.

## Consideraciones Técnicas
- Multitenancy por header `X-Tenant-ID` y compatibilidad con `NeedsTenant`.
- SPA obtiene `token` y `tenant_id` y aplica guards por roles.
- Auditoría de accesos denegados y eventos de setup.

