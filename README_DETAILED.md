# 🚀 SmartKet – Documentación del Proyecto

SmartKet es un sistema **ERP (Enterprise Resource Planning)** como servicio (**SaaS**) diseñado bajo la premisa de ser **"tan fácil de usar como una red social"**. Está enfocado en facilitar la gestión de PYMES, permitiendo un registro instantáneo y una configuración automatizada.

---

## 🏗️ Arquitectura del Sistema

El ecosistema SmartKet se divide en tres proyectos independientes pero interconectados, lo que permite una escalabilidad limpia y un mantenimiento eficiente:

1.  **[smartket-api](file:///d:/TRAE/smartket-v1/smartket-api)** (El Cerebro)
    *   **Tecnología:** Laravel (API mode).
    *   **Función:** Gestiona la lógica de negocio, autenticación (Sanctum) y el motor multi-tenant. No tiene interfaz gráfica, responde únicamente JSON.
2.  **[smartket-landing](file:///d:/TRAE/smartket-v1/smartket-landing)** (El Escaparate)
    *   **Tecnología:** Laravel + Blade + JavaScript.
    *   **Función:** Web pública de marketing, precios, registro y login inicial. Los formularios llaman a la API vía AJAX/Fetch.
3.  **[smartket-app](file:///d:/TRAE/smartket-v1/smartket-app)** (La Herramienta)
    *   **Tecnología:** Vue.js 3 + Vite.
    *   **Función:** Aplicación SPA (Single Page Application) privada donde los clientes gestionan su negocio (Dashboard, Ventas, Inventario).

---

## 🛠️ Stack Tecnológico

*   **Backend:** PHP 8.x con [Laravel](https://laravel.com/).
*   **Frontend Web:** [Vue.js 3](https://vuejs.org/) (Composition API), Vite, Tailwind CSS.
*   **Base de Datos:** [PostgreSQL](https://www.postgresql.org/).
*   **Servidor Local Recomendado:** Laragon.
*   **Entornos de Desarrollo:** VS Code con extensiones para PHP/Vue.

---

## 📂 Arquitectura de Base de Datos (Multi-Tenant)

SmartKet utiliza un modelo de **aislamiento total de datos** por cliente:

### 1. Base de Datos Administrativa (`smartket_admin_db`)
Centraliza la gestión del SaaS.
*   **Tabla `tenants`**: Almacena los datos de conexión a la base de datos de cada cliente.
*   **Tabla `users`**: Usuarios globales (dueños de negocios).

### 2. Bases de Datos de Clientes (`db_cliente_xyz`)
Cada cliente que se registra obtiene su propia base de datos aislada.
*   **Automatización:** Al registrarse, el sistema ejecuta un comando (`CreateTenant`) que crea la DB, el usuario de DB y corre las migraciones de los módulos contratados.
*   **Seguridad:** Los datos de un cliente nunca se mezclan con otros, garantizando privacidad y rendimiento.

> [!NOTE]
> Para ver el esquema detallado, consulta [DATABASE_SCHEMA.md](file:///d:/TRAE/smartket-v1/DATABASE_SCHEMA.md).

---

## ⚙️ Configuración y Ejecución Local

### Prerrequisitos
1.  **PostgreSQL** instalado y corriendo.
2.  **PHP 8.x** y **Composer**.
3.  **Node.js 20+** y **npm**.

### Atajo: Un solo comando para todo (Recomendado)
Para no abrir 4 terminales, he configurado un orquestador en la raíz:

```powershell
# En la raíz del proyecto
npm install
npm run dev
```
Esto levantará simultáneamente la API (8000), el Landing (8002 + Estilos) y el Panel (App).

---

### Pasos manuales (si prefieres control total)
Para trabajar en el proyecto completo, debes iniciar los tres servicios:

#### 1. API (Puerto 8000)
```powershell
cd smartket-api
npm install
composer install
cp .env.example .env  # Configurar DB_DATABASE=smartket_admin_db
php artisan migrate --seed
composer run dev
```

#### 2. Landing (Puerto 8002)
```powershell
cd smartket-landing
npm install
composer install
cp .env.example .env
composer run dev
```

#### 3. App (Puerto 5174)
```powershell
cd smartket-app
npm install
npm run dev
```

> [!IMPORTANT]
> Consulta [PORTS_AND_ENV.md](file:///d:/TRAE/smartket-v1/PORTS_AND_ENV.md) para detalles sobre las variables de entorno necesarias para que los proyectos se comuniquen entre sí.

---

## 📖 Documentación Interna Adicional
Puedes encontrar guías detalladas sobre la filosofía y el desarrollo en la carpeta `DOCUMENTO`:
*   [Manifiesto del Proyecto](file:///d:/TRAE/smartket-v1/DOCUMENTO/Manifiesto%20del%20Proyecto%20-%20SmartKet.md)
*   [Documento Maestro de Arquitectura](file:///d:/TRAE/smartket-v1/DOCUMENTO/Documento%20Maestro%20de%20Arquitectura%20-%20SmartKet%20ERP.md)
*   [Guía de Buenas Prácticas](file:///d:/TRAE/smartket-v1/DOCUMENTO/Guía%20de%20Buenas%20Prácticas%20y%20Filosofía%20de%20Desarrollo%20para%20SmartKet.md)
