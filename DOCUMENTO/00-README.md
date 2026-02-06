# 📚 Documentación SmartKet - Índice Maestro

Bienvenido a la documentación central del proyecto SmartKet ERP. Este índice te guiará a través de todos los aspectos del sistema.

---

## 🎯 Inicio Rápido

**¿Nuevo en el proyecto?** Lee en este orden:
1. [Manifiesto del Proyecto](01-Vision-Mision/Manifiesto-del-Proyecto.md) - Misión y visión
2. [Arquitectura General](02-Arquitectura/Arquitectura-General.md) - Estructura del sistema
3. [Buenas Prácticas](03-Desarrollo/Buenas-Practicas.md) - Estándares de código
4. [Setup Local](04-Deployment/Setup-Local.md) - Configurar entorno de desarrollo

**¿Desarrollador experimentado?** Consulta las [Skills](../.agent/skills/README.md) para patrones y validaciones.

---

## 📂 Estructura de Documentación

### 01. [Visión y Misión](01-Vision-Mision/)
Contexto estratégico y filosofía del proyecto.

- [**Manifiesto del Proyecto**](01-Vision-Mision/Manifiesto-del-Proyecto.md) - Propósito, valores y diferenciadores
- [**Objetivos Estratégicos**](01-Vision-Mision/Objetivos-Estrategicos.md) - Metas y KPIs del proyecto

---

### 02. [Arquitectura](02-Arquitectura/)
Diseño técnico y decisiones arquitectónicas.

- [**Arquitectura General**](02-Arquitectura/Arquitectura-General.md) - Visión completa del sistema
- [**Multi-Tenancy Design**](02-Arquitectura/Multi-Tenancy-Design.md) - Implementación database-per-client
- [**Database Schema**](02-Arquitectura/Database-Schema.md) - Esquema de base de datos
- [**API Contracts**](02-Arquitectura/API-Contracts.md) - Contratos de API y módulos

---

### 03. [Desarrollo](03-Desarrollo/)
Guías para escribir código de calidad.

- [**Buenas Prácticas**](03-Desarrollo/Buenas-Practicas.md) - Filosofía de desarrollo
- [**Coding Standards**](03-Desarrollo/Coding-Standards.md) - Convenciones PSR, naming
- [**Git Workflow**](03-Desarrollo/Git-Workflow.md) - Branching strategy
- [**Testing Strategy**](03-Desarrollo/Testing-Strategy.md) - Qué, cuándo y cómo probar

---

### 04. [Deployment](04-Deployment/)
Procesos de deployment y configuración de servidores.

- [**Setup Local**](04-Deployment/Setup-Local.md) - Configurar entorno local
- [**Producción Checklist**](04-Deployment/Produccion-Checklist.md) - Pre-deploy verification
- [**Hosting Strategy**](04-Deployment/Hosting-Strategy.md) - Infraestructura recomendada

---

### 05. [Planes](05-Planes/)
Roadmaps y estrategias de evolución.

- [**RECYCLING_PLAN**](05-Planes/RECYCLING_PLAN.md) - Reciclaje de v5 a v1
- [**Plan de Dashboard**](05-Planes/Plan-de-Dashboard.md) - Diseño del dashboard
- [**Pentágono de Calidad**](05-Planes/Pentágono-de-Calidad.md) - 5 pilares de mantenimiento

---

### 06. [Referencia](06-Referencia/)
Material de consulta y archivos históricos.

- [**Conversaciones/**](06-Referencia/Conversaciones/) - PDFs de sesiones de diseño
- [**Mejoras/**](06-Referencia/Mejoras/) - Propuestas de mejoras

---

## 🛠️ Skills Técnicos

Además de esta documentación, el proyecto cuenta con **5 skills especializados** en `.agent/skills/`:

1. [**Architecture Skill**](../.agent/skills/architecture/SKILL.md) - Validar estructura y namespaces
2. [**Testing Skill**](../.agent/skills/testing/SKILL.md) - Automatizar pruebas
3. [**Database Skill**](../.agent/skills/database/SKILL.md) - Gestionar multi-tenancy
4. [**Debug Skill**](../.agent/skills/debug/SKILL.md) - Diagnosticar errores
5. [**Deployment Skill**](../.agent/skills/deployment/SKILL.md) - Deploy profesional

**Ver:** [README de Skills](../.agent/skills/README.md)

---

## 📖 Documentos en Raíz del Proyecto

- [**README_DETAILED.md**](../README_DETAILED.md) - Documentación técnica completa
- [**RECYCLING_PLAN.md**](../RECYCLING_PLAN.md) - Plan de reciclaje v5 → v1
- [**Instruccion.md**](../Instruccion.md) - Pentágono de Calidad
- [**RESUMEN-CORRECCIONES.md**](../RESUMEN-CORRECCIONES.md) - Historial de correcciones recientes

---

## 🔄 Changelog

Ver [CHANGELOG.md](CHANGELOG.md) para historial completo de cambios importantes.

**Última actualización**: 2026-02-02

---

## 💡 Cómo Contribuir a la Documentación

### Agregar nueva documentación:
1. Identifica la categoría correcta (01-06)
2. Crea archivo con formato `Nombre-del-Documento.md` (kebab-case)
3. Actualiza este índice
4. Registra en CHANGELOG.md

### Actualizar documentación existente:
1. Edita el archivo correspondiente
2. Actualiza fecha al final del documento
3. Registra cambio significativo en CHANGELOG.md

---

## 📞 Soporte

- **Issues técnicos**: Ver [Debug Skill](../.agent/skills/debug/SKILL.md)
- **Dudas arquitectónicas**: Consultar [Architecture Skill](../.agent/skills/architecture/SKILL.md)
- **Deployment**: Seguir [Deployment Skill](../.agent/skills/deployment/SKILL.md)

---

**Mantenido por**: Equipo SmartKet
**Versión de documentación**: 2.0
