Documento Maestro de Arquitectura: SmartKet ERP

1. Visión del Proyecto (La Misión)

SmartKet es un ERP (Sistema de Planificación de Recursos) como Servicio (SaaS) diseñado para ser increíblemente fácil de usar.

Objetivo: Permitir que dueños de negocios (PYMES) se registren y empiecen a gestionar su negocio en minutos, tan fácil como registrarse en Facebook.

Modelo de Negocio: Flujo de "Free Trial" (Prueba Gratuita) de 14 días. El usuario se registra sin pagar, configura su negocio (con un "Setup Wizard") y solo paga cuando ve el valor.

MVP (Producto Mínimo Viable): El primer "vertical" o tipo de negocio que soportaremos será Pollerías.

2. Stack Tecnológico

Entorno Local: Laragon

Base de Datos: PostgreSQL

Backend API: Laravel (Proyecto: smartket-api)

Frontend Landing: Laravel + Blade (Proyecto: smartket-landing)

Frontend App (ERP): Vue.js 3 (Proyecto: smartket-app)

3. Arquitectura General (Los 3 Proyectos)

El sistema está separado en 3 proyectos independientes que se ejecutan en Laragon:

smartket-api

Propósito: El "Cerebro". Una API de Laravel (--api) sin estado que solo habla JSON.

Rol: Maneja toda la lógica de negocio, autenticación (Sanctum) y la lógica multi-tenant.

URL Local: http://smartket-api.test

smartket-landing

Propósito: El "Marketing". Un proyecto Laravel normal (con Blade).

Rol: Muestra la web de marketing, precios, y los formularios públicos de Registro y Login.

Flujo: Los formularios de esta web no usan el auth de Laravel, sino que hacen llamadas JavaScript (fetch) directamente al smartket-api.

URL Local: http://smartket-landing.test

smartket-app

Propósito: El "ERP". La aplicación privada que usa el cliente.

Rol: Una SPA (Single Page Application) de Vue.js que consume la API (smartket-api) después de que el usuario se ha logueado.

Flujo: El usuario es redirigido aquí después de un login exitoso en el smartket-landing.

URL Local: http://localhost:3000 (o el puerto que asigne npm run dev)

4. Arquitectura de Backend (¡El Núcleo!)

Esta es la parte más importante: la Arquitectura Multi-Tenant.

Concepto: Cada cliente (cada pollería) tendrá su propia base de datos PostgreSQL totalmente aislada.

Paquete: Usamos spatie/laravel-multitenancy para gestionar esto.

4.1. La Base de Datos "Admin" (smartket_admin_db)

Es la BBDD principal, pública, de nuestro negocio (SmartKet).

Se crea manualmente (1 sola vez).

Tablas Principales:

tenants: Almacena la lista de todos nuestros clientes.

id

nombre_negocio

plan (ej: 'trial', 'pro')

rubro (ej: 'polleria')

db_name (¡Importante! ej: db_cliente_123)

db_user (¡Importante! ej: user_cliente_123)

db_password (¡Importante! encriptado)

setup_complete (boolean, para el Setup Wizard)

modulos_activos (jsonb, para add-ons)

users: Los dueños de los negocios (nuestros clientes).

tenant_user: Tabla pivote que vincula users con tenants.

4.2. La Base de Datos "Tenant" (Plantilla del Cliente)

Esta es la estructura que tendrá CADA cliente en su propia BBDD aislada.

NO se crea manualmente.

Tablas Principales (MVP Pollería):

sucursales

cash_registers (Cajas)

staff (Sub-usuarios: meseros, cajeros)

categories

products

mesas

pedidos (con status, mesa_id, staff_id)

pedido_items

sales (Ventas)

sale_details

4.3. Aprovisionamiento Automático (La Magia)

REGLA: NUNCA creamos una BBDD de cliente manualmente.

Flujo:

Usuario se registra en smartket-landing.

Llama al endpoint POST /api/register en smartket-api.

Este endpoint ejecuta un comando de Laravel: CreateTenant.

El comando CreateTenant hace todo automáticamente:
a. Crea el user en smartket_admin_db.
b. Ejecuta SQL para crear la BBDD (CREATE DATABASE db_cliente_123).
c. Ejecuta SQL para crear el Usuario (CREATE USER user_cliente_123...).
d. Guarda db_cliente_123 y user_cliente_123 en la tabla tenants.
e. Ejecuta las migraciones de la "Plantilla" (las tablas de pollería) SÓLO en la nueva db_cliente_123.

5. Estado Actual del Proyecto y Próximas Tareas

TAREA 1: smartket-api

Estado: 🟩 COMPLETADA

Resultado: El proyecto está creado. spatie/laravel-multitenancy está configurado. Las migraciones admin y tenant están listas. El comando CreateTenant está hecho. Los endpoints de register y login (con Sanctum) están listos. CORS está configurado.

TAREA 2: smartket-landing

Estado: 🟩 COMPLETADA

Resultado: El proyecto está creado. Tiene un layout Blade maestro. Las vistas registro.blade.php y login.blade.php están creadas y tienen el JavaScript (fetch) que llama correctamente a smartket-api y gestiona la redirección/token.

TAREA 3: smartket-app

Estado: 🟥 PENDIENTE (Esta es nuestra próxima tarea)

Objetivo: Configurar la base del proyecto Vue.js para que pueda "hablar" con la API.

Acciones Requeridas:

Instalar axios y vue-router.

Crear un "plugin" de axios (/src/api.js) que:

Establezca el baseURL a http://smartket-api.test/api.

Añada un "interceptor" que lea el token (smartket_token) de localStorage y lo adjunte en el header Authorization: Bearer ... de TODAS las peticiones.

Configurar vue-router (/src/router/index.js) con "guards" (guardias de ruta).

Las rutas privadas (/dashboard, /productos) deben revisar si el token existe.

Si no hay token, debe redirigir al usuario a la página de login (que está en http://smartket-landing.test/login).