Manifiesto del Proyecto: SmartKet

1. La Misión: "El ERP tan fácil como una Red Social"

Nuestra misión es desafiar el status quo de la industria del software de gestión.

El problema de los ERP convencionales no es que les falten funciones, es que son difíciles de adquirir y complejos de usar. Exigen instalaciones, largas consultas de ventas y una curva de aprendizaje que los dueños de pequeños negocios no pueden permitirse.

La Misión de SmartKet es democratizar la gestión de negocios.

Creemos que gestionar un inventario, registrar una venta o entender un reporte de ganancias debe ser tan intuitivo y accesible como publicar una foto en Facebook.

Nuestra visión se basa en 3 pilares:

Acceso Instantáneo: El usuario se registra en nuestra landing page y empieza a usar el sistema en ese mismo instante. Sin consultores, sin llamadas de ventas, sin instalaciones.

Usabilidad Radical: Nuestro sistema está diseñado para personas que no usan sistemas complejos. Debe ser amigable, rápido y visual.

Crecimiento Modular: El cliente empieza simple y añade poder solo cuando lo necesita. No lo abrumamos con funciones que no utiliza.

2. El Origen de la Arquitectura (De la Idea a la Solución)

Para cumplir esta misión, nos enfrentamos a desafíos técnicos complejos. La "facilidad" para el usuario implica una ingeniería superior en el backend. Así es como resolvimos cada desafío:

Problema 1: "El Monolito" vs. "Múltiples Proyectos"

La Idea Original: "Mi sistema era un solo proyecto Laravel" o "quizás deba crear Frontend-Polleria, Frontend-Farmacia..."

El Riesgo: El monolito es inflexible. Múltiples frontends son una pesadilla de mantenimiento (un bug en el "login" habría que arreglarlo 10 veces).

Nuestra Solución (La Arquitectura de 3 Proyectos):

smartket-api (El Cerebro): Un solo backend de Laravel que contiene TODA la lógica.

smartket-landing (El Vendedor): Una web de marketing que solo se preocupa de registrar y loguear.

smartket-app (La Herramienta): Un SOLO frontend (Vue.js) que todos los clientes usan.

Problema 2: "Pollería no es igual a Farmacia"

La Idea Original: "Quiero sistemas separados porque sus módulos son distintos".

El Riesgo: Duplicar código. Si ambos necesitan "Ventas", tendrías que programar dos veces el módulo de ventas.

Nuestra Solución (Desarrollo Modular):

Dentro de smartket-app, creamos un CORE (con Ventas.vue, Inventario.vue que TODOS usan) y MÓDULOS específicos (ej. /Modulos/Polleria/VistaMesas.vue).

El sistema es uno solo, pero le "enciende" o "apaga" módulos al cliente según su rubro.

Problema 3: "Como Facebook" vs. "Base de Datos por Cliente"

La Idea Original: "¿Cómo maneja Facebook a tantos usuarios? ¿Una BBDD por año? ¿O como Odoo (BBDD por cliente)?"

El Riesgo: Una BBDD gigante ("como Facebook") es insegura y lenta. Si un cliente tiene 10 millones de ventas, hace que el sistema de todos los demás sea lento.

Nuestra Solución (Multi-Tenant Aislado):

Confirmamos que el modelo "Odoo" (BBDD por cliente) es el profesional.

smartket_admin_db (La Recepción): Guarda la lista de nuestros clientes y a qué base de datos pertenecen.

db_cliente_123 (La Habitación Privada): Cada cliente tiene su BBDD aislada. Sus datos están 100% seguros y su rendimiento no afecta a nadie más.

Problema 4: "Usuarios Secundarios (Meseros, Cocina)"

La Idea Original: "¿Cómo acceden? ¿Tendrán un dashboard distinto? ¿Cómo evito que entren personas no deseadas?"

El Riesgo: Crearles un sistema o login separado sería complejo y un hueco de seguridad.

Nuestra Solución (Roles y Vistas Especializadas):

El Dueño del negocio (Usuario Principal) crea a sus empleados (staff) dentro de su sistema.

Les asigna un Rol (ej. MESERO, COCINA).

El mesero inicia sesión en la misma smartket-app (app.smartket.com).

El sistema detecta su rol MESERO y lo redirige automáticamente a la vista /mesero, que es una versión simplificada de la app. No puede ver reportes ni inventario.

Problema 5: "Costo Adicional y Crecimiento (Sucursales, Múltiples Negocios)"

La Idea Original: "Quiero cobrar extra por el módulo de delivery" y "¿Cómo maneja un cliente 2 pollerías y 1 minimarket?"

El Riesgo: No tener un plan claro de monetización y escalabilidad.

Nuestra Solución (Módulos de Pago y Múltiples Tenants):

Módulos Adicionales: En smartket_admin_db, guardamos los modulos_activos de cada cliente (ej. ['CORE', 'ADDON_COCINA']). La smartket-app lee esta lista y "enciende" los botones por los que el cliente ha pagado.

Sucursales: Se manejan dentro de la BBDD del cliente (una columna sucursal_id en ventas).

Múltiples Negocios: Un user (en admin_db) puede ser dueño de múltiples tenants. Al loguearse, el sistema le pregunta: "¿Deseas gestionar 'Pollería Juan' o 'Minimarket Juan'?"

Problema 6: La Visión Original: "Fácil de Adquirir"

La Idea Original: "Registrarse y usarlo sin consultarnos".

El Riesgo: Un mal flujo de registro. Pedir el pago por adelantado mata la conversión.

Nuestra Solución (El Flujo "Free Trial"):

Este es el pilar de la Misión.

Paso 1: Registro rápido en smartket-landing (solo email/pass).

Paso 2: El sistema aprovisiona automáticamente la BBDD (db_cliente_123).

Paso 3: El cliente es redirigido a smartket-app y ve un "Setup Wizard" (aquí sí pedimos el RUC, nombre de la tienda, etc.).

Paso 4: El cliente usa la app en modo "Prueba Gratuita" por 14 días.

Paso 5: El cliente, ya "enganchado" y con sus datos configurados, paga para convertir su cuenta.

3. Conclusión

SmartKet no es solo un ERP. Es una filosofía de ingeniería centrada en el usuario. Nace de una simple pregunta: "¿Por qué es tan difícil?"

Nuestra arquitectura (Multi-Tenant, API Separada, App Modular) no es solo una decisión técnica; es la única forma de cumplir la promesa de un sistema que es, a la vez, poderoso, escalable y radicalmente fácil de usar.