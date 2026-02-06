# CHANGELOG - SmartKet ERP

Registro de cambios importantes del proyecto.

El formato está basado en [Keep a Changelog](https://keepachangelog.com/es-ES/1.0.0/),
y este proyecto adhiere a [Semantic Versioning](https://semver.org/lang/es/).

---

## [Unreleased]

### A Agregar
- Módulo de Inventario Avanzado
- Facturación Electrónica (Perú)
- Dashboard Analytics con gráficos

---

## [2.0.0] - 2026-02-02

### 🎉 Reorganización Mayor

#### Agregado
- **Skills Sistema**: 5 skills especializados en `.agent/skills/`
  - Architecture Skill con validaciones automáticas
  - Testing Skill con integración /Pruebas
  - Database Skill para multi-tenancy
  - Debug Skill con catálogo de errores
  - Deployment Skill con proceso completo
- **Documentación Reorganizada**: Estructura profesional en `/DOCUMENTO`
  - 00-README.md (índice maestro)
  - 01-Vision-Mision/ con objetivos estratégicos
  - 02-Arquitectura/ con multi-tenancy design
  - 03-Desarrollo/ con coding standards
  - 04-Deployment/ con guías de setup
  - 05-Planes/ consolidado
  - 06-Referencia/ para archivos históricos

#### Cambiado
- **Backend**: Namespaces reorganizados siguiendo PSR-4
  - Controllers en Api/Core, Api/Admin, Api/Compartido, Api/Vertical
  - Models en Core/, Compartido/, Vertical/
  - Services en Core/, Vertical/
- **Frontend**: Vistas y componentes reorganizados
  - views/compartido, views/core, views/admin, views/vertical
  - components/compartido/layout y /ui
- **Skills reemplazan documentación dispersa**: Fuente única de verdad

#### Corregido
- Fatal Error en `HeaderTenantFinder` (namespace antiguo)
- CORS bloqueando login (middleware personalizado)
- Frontend sin configuración CORS (mode y credentials)
- `AuditService` not found (namespace en middleware)
- Namespaces en `PolleriaService`, `AuthServiceProvider`, `DiagnoseAuth`

---

## [1.5.0] - 2026-01-25

### Agregado
- Módulo Pollería completo
  - Gestión de mesas
  - Sistema de órdenes
  - Cocina (kitchen queue)
- RBAC granular con permisos por módulo
- Auditoría global (ISO 27001)

### Cambiado
- Dashboard con métricas en tiempo real
- UI mejorada estilo v5 (colores vibrantes)

### Corregido
- Performance en Dashboard (N+1 queries eliminados)
- Login redirect con token correcto

---

## [1.0.0] - 2025-12-15

### 🎉 Release Inicial

#### Agregado
- **Core Multi-Tenant**
  - Database-per-client implementation
  - Spatie Multi-Tenancy integration
  - Tenant provisioning automático
- **Autenticación**
  - Laravel Sanctum
  - HTTPOnly cookies
  - Login con email o username
- **Módulos Base**
  - Productos (CRUD completo)
  - Ventas básicas
  - Staff management
- **Frontend SPA**
  - Vue 3 + Composition API
  - Tailwind CSS
  - Vite build
- **Landing Page**
  - Marketing page
  - Registro de negocios
  - Login inicial

---

## Tipos de Cambios

- `Agregado` para nuevas funcionalidades
- `Cambiado` para cambios en funcionalidades existentes
- `Obsoleto` para funcionalidades que se eliminarán pronto
- `Eliminado` para funcionalidades eliminadas
- `Corregido` para corrección de bugs
- `Seguridad` para vulnerabilidades corregidas

---

## Versionado

**Formato**: MAJOR.MINOR.PATCH

- **MAJOR**: Cambios incompatibles con versión anterior
- **MINOR**: Nueva funcionalidad compatible
- **PATCH**: Bug fixes compatibles

**Ejemplo**: 
- `2.0.0` → Reorganización mayor (breaking changes)
- `1.5.0` → Nuevo módulo (compatible)
- `1.5.1` → Hotfix (compatible)

---

**Última actualización**: 2026-02-02
