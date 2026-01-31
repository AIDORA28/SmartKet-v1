Guía de Buenas Prácticas y Filosofía de Desarrollo para SmartKet

Este documento define la filosofía y las prácticas tácticas que usaremos para construir y mantener SmartKet. Tener una arquitectura clara es el primer paso; seguir estas reglas es lo que garantizará que el proyecto sea escalable, mantenible y robusto a largo plazo.

1. Principios Fundamentales (La Estrategia)

Principio 1: Separación de Responsabilidades (SoC)

Esta es nuestra regla de oro y aplica a todos los niveles del proyecto.

A Nivel de Arquitectura: Ya lo estamos haciendo.

smartket-landing: Solo se preocupa del marketing y la captura de usuarios.

smartket-api: Solo se preocupa de la lógica de negocio, los datos y la seguridad.

smartket-app: Solo se preocupa de la interfaz de usuario y la experiencia del cliente.

A Nivel de Módulo (¡Tu punto clave!)

Un módulo (ej. MODULO_POLLERIA) debe ser un "mini-proyecto" dentro de la aplicación.

Aislamiento: Un bug en el MODULO_POLLERIA (ej. al gestionar mesas) NUNCA debe "contaminar" o romper el CORE_VENTAS o el CORE_INVENTARIO. Si el módulo de mesas falla, la pollería aún debe poder hacer una venta para llevar.

Beneficio (Tu punto): Si un módulo se daña, diagnosticamos y corregimos solo ese módulo, sin que el resto del sistema se detenga.

A Nivel de Código (Práctica Táctica):

En smartket-api (Laravel):

Controlador: Recibe la petición, valida datos básicos. NUNCA habla con la BBDD.

Servicio (Service Class): Contiene TODA la lógica de negocio (ej. VentaService.php). El controlador llama al servicio.

Repositorio (Opcional): Gestiona las consultas complejas a la BBDD.

Modelo (Eloquent): Define la tabla y sus relaciones.

Principio 2: Desarrollo Modular e Iterativo (MVP Vertical)

Este es el antídoto para "hacer todo a la vez".

La Regla: No construyas el 10% de 10 módulos. Construye el 100% de 1 módulo.

Nuestro Plan: Nuestro MVP es "Pollerías".

Prohibido: No escribas ni una sola línea de código para el MODULO_FARMACIA o MODULO_MINIMARKET hasta que MODULO_POLLERIA esté 100% funcional, probado y, si es posible, validado por tu primer cliente.

Beneficio: Enfocas tu energía, reduces la cantidad de errores simultáneos y entregas valor real más rápido.

Principio 3: Reutilización (El Principio DRY)

DRY: "Don't Repeat Yourself" (No te Repitas).

Tu punto: "reutilizar elementos que se puedan repetir".

En smartket-app (Vue.js):

Si creas un botón con un estilo específico, hazlo un componente (BotonBonito.vue). Si necesitas un botón igual en la vista "Productos" y "Ventas", reutiliza el componente.

NO copies y pegues el HTML y CSS del botón en dos archivos distintos. Si luego quieres cambiar el color, tendrías que hacerlo en dos lugares (o en 100).

En smartket-api (Laravel):

Si la lógica para "revisar stock de un producto" se necesita al vender y al hacer un pedido a cocina, crea una función RevisarStockService.php y llámala desde ambos sitios.

NO copies y pegues la lógica de revisión de stock en dos controladores.

2. Prácticas Tácticas (La Ejecución)

Práctica 1: Usa Control de Versiones (Git) como un Profesional

Incluso si trabajas solo (o con amigos), esto es vital.

main (o master): Sagrado. Esta rama SOLO contiene código que funciona y ha sido probado. Nunca programes aquí.

develop: Tu rama de integración. Aquí se une todo.

feature/... (Ramas de Funcionalidad): Todo trabajo nuevo se hace en su propia rama.

feature/modulo-mesas-polleria

feature/login-jwt-api

bugfix/error-calculo-total-venta

Flujo: Creas feature/modulo-mesas, trabajas 1 semana, la terminas, la pruebas en develop y, solo cuando funciona, la pasas (merge) a main.

Práctica 2: Código Limpio y Autodocumentado

El código se escribe una vez, pero se lee 100 veces.

Nombres Claros:

MAL: $data, $arr, function hacertodo()

BIEN: $productosAgotados, $usuarioDelTenant, function calcularTotalVenta()

Funciones Cortas: Una función debe hacer una sola cosa y hacerla bien. Si tu función tiene 50 líneas, probablemente está haciendo 5 cosas. Divídela.

Comentarios: No comentes el "qué" (el código ya lo dice). Comenta el "por qué".

MAL: // Suma 1 a $i

BIEN: // Usamos esta lógica de descuento compleja por requisito legal del país X

Práctica 3: La Documentación de tu API es el "Contrato"

Dado que smartket-api y smartket-app son proyectos separados, la única forma en que pueden "hablar" es si ambos cumplen un contrato. Ese contrato es la documentación de tu API.

Herramienta: Usa Postman o Insomnia. Crea una "colección" para tu API.

Documenta CADA Endpoint:

POST /api/login

Qué necesita (Body): { email: "string", password: "string" }

Qué devuelve si tiene éxito (200): { token: "jwt_string", user: {...} }

Qué devuelve si falla (401): { message: "Credenciales inválidas" }

Sin esto, fracasarás. Si el smartket-app espera un token y tu API devuelve un token_jwt, la aplicación se romperá.

Resumen

Esta guía no es "trabajo extra". Es la metodología que evita el caos, reduce los errores y te permite construir un producto profesional y escalable en lugar de un prototipo que se rompe constantemente.

Lento es Suave, Suave es Rápido.

---

Anexo Técnico: Core de Base de Datos del Tenant (smartket_admin_db)

Objetivo
- Definir un núcleo mínimo viable, estable y mantenible para la base de datos principal del tenant, siguiendo SoC, DRY y un diseño por capas.

Tablas Esenciales (Normalización Balanceada)
- `tenants`: negocio por tenant. Campos: `nombre_negocio`, `plan`, `rubro`, `setup_complete`, `owner_user_id`, `db_name`, `db_user`, `db_password`.
- `branches`: sucursales por tenant. Campos: `tenant_id`, `name`, `address`.
- `tenant_user`: usuarios por tenant y rol. Campos: `tenant_id`, `user_id`, `role`.
- `products`: catálogo por tenant. Campos: `tenant_id`, `name`, `sku`, `price`, `active`.
- `orders` y `order_items`: pedidos y sus ítems. Campos: `tenant_id`, `branch_id`, `table_code`, `status`; ítems con `order_id`, `product_id`, `qty`, `unit_price`.
- `sales` y `sale_items`: ventas consolidadas y sus ítems. Campos: `tenant_id`, `order_id`, `branch_id`, `total`, `payment_method`.
- `fiscal_settings`: configuración fiscal por tenant. Campos: `tenant_id`, `ruc`, `razon_social`, `comprobante_default`, `boleta_simple_enabled`.
- `audit_events`: auditoría. Campos: `user_id`, `tenant_id`, `event_type`, `route`, `message`, `meta`.

Índices Estratégicos
- `tenants.setup_complete`, `tenants.rubro`: navegación y guards.
- `products.tenant_id`, `products.active`, `products.sku`: catálogo y búsquedas.
- `orders.tenant_id`, `orders.status`, `orders.table_code`: cocina/mesero.
- `sales.tenant_id`, `sales.payment_method`, `sales.created_at`: reportes y caja.
- `audit_events.tenant_id`, `audit_events.event_type`, `audit_events.created_at`: monitoreo y limpieza.

Seguridad y Privilegios
- Cifrar datos sensibles en modelo: `Tenant::$casts` para `db_name`, `db_user`, `db_password`; `FiscalSetting::$casts` para `ruc`, `razon_social`.
- Principio de mínimo privilegio: separar usuarios de BBDD por rol (lectura/escritura); usar cuentas de solo lectura para reportes.
- Validación estricta en controladores y uso de Eloquent/Query Builder para prevenir SQL injection.

Caching y Circuitos de Tolerancia a Fallos
- Cache para datos estáticos: planes (`Cache::remember('polleria_plans', 3600, ...)`).
- Logging estructurado (canal por defecto) en operaciones clave del servicio (`Log::info(...)`).
- Limpieza programada: comando `smartket:clean-obsolete` diario para eliminar auditoría obsoleta.

Consultas Simples y Eficientes
- Usar scopes y filtros por índices (`whereTenant`, `whereStatus`).
- Evitar joins profundos en flujo crítico; preferir consultas segmentadas con claves indexadas.

Pruebas y CI
- Pruebas de autenticación y `/me` (unitarias) para flujo básico.
- Pruebas de integración por módulo (pedidos/ventas) conforme se agregue persistencia real.
- Ejecutar limpieza en ambientes de staging; monitoreo de logs diario.

Alcance y MVP
- Implementar primero Pollería con las tablas anteriores.
- No agregar tablas ni campos fuera del core hasta concluir funcionalidad y pruebas del módulo.

Este anexo sustituye instrucciones dispersas previas para la base de datos del tenant y consolida el contrato técnico mínimo y las prácticas de operación.
