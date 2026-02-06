# Skills SmartKet - README

Esta carpeta contiene **skills especializados** que actúan como "memoria institucional" del proyecto SmartKet, manteniendo coherencia en arquitectura, testing, database management, debugging y deployment.

---

## 📚 Skills Disponibles

### 1. [Architecture Skill](architecture/SKILL.md)
**Propósito**: Mantener integridad arquitectónica del proyecto

**Cuándo usar**:
- Agregar nuevos controllers/services/models
- Refactorizar código existente
- Reorganizar estructura de carpetas
- Validar namespaces PSR-4

**Scripts clave**:
- `validate-structure.ps1` - Verifica organización de carpetas
- `check-namespaces.php` - Valida PSR-4 compliance
- `analyze-coupling.php` - Detecta Fat Controllers

---

### 2. [Testing Skill](testing/SKILL.md)
**Propósito**: Automatizar pruebas y diagnósticos

**Cuándo usar**:
- Crear nuevos tests
- Ejecutar suite completa pre-deploy
- Debugging con scripts de /Pruebas

**Integración**:
- Scripts de `/Pruebas` incluidos
- Templates PHPUnit y Pest
- Checklist pre-deployment

---

### 3. [Database Skill](database/SKILL.md)
**Propósito**: Gestión multi-tenancy y migraciones

**Cuándo usar**:
- Crear migraciones landlord o tenant
- Agregar nuevo tenant
- Seed de datos
- Cambios de esquema

**Reglas clave**:
- Nunca mezclar conexiones landlord/tenant
- Migraciones separadas por tipo
- Naming: snake_case, inglés

---

### 4. [Debug Skill](debug/SKILL.md)
**Propósito**: Diagnóstico rápido de errores

**Cuándo usar**:
- Error en producción
- CORS issues
- Namespace errors
- Database connection failures
- Performance problems

**Recursos**:
- Catálogo de errores comunes
- Decision tree de debugging
- Scripts de diagnóstico automáticos

---

### 5. [Deployment Skill](deployment/SKILL.md)
**Propósito**: Deployment profesional con 0 downtime

**Cuándo usar**:
- Deploy a staging/production
- Configurar CI/CD
- Setup de servidores
- Rollback de emergencia

**Incluye**:
- Pre-deploy checklist
- Scripts de deployment
- Configuración Nginx/Supervisor
- Estrategia de rollback

---

## 🚀 Cómo Usar las Skills

### Leer antes de modificar código
Antes de hacer cambios arquitectónicos importantes, leer el SKILL.md correspondiente:

```bash
# Ejemplo: Voy a agregar un nuevo service
cat .agent/skills/architecture/SKILL.md
```

### Ejecutar scripts de validación
```bash
# Validar estructura
.\.agent\skills\architecture\scripts\validate-structure.ps1

# Validar namespaces
cd smartket-api
php ..\.agent\skills\architecture\scripts\check-namespaces.php
```

### Consultar antes de deployment
```bash
cat .agent/skills/deployment/SKILL.md
# Seguir checklist paso a paso
```

---

## 📖  Estructura de un Skill

Cada skill sigue esta estructura:

```
skill-name/
├── SKILL.md                 # Instrucciones principales (YAML frontmatter + markdown)
├── scripts/                 # Scripts ejecutables
│   ├── script1.ps1
│   └── script2.php
├── examples/                # Ejemplos de código
│   ├── pattern-example.php
│   └── template-example.php
└── resources/               # Documentación adicional
    ├── reference.md
    └── diagram.mmd
```

---

## 🔄 Mantenimiento de Skills

### Actualizar cuando:
- Cambies arquitectura del proyecto
- Encuentres nuevo error recurrente
- Mejores proceso de deployment
- Agregues nueva funcionalidad crítica

### Regla de oro:
> "Si modificas arquitectura, actualiza el skill correspondiente el mismo día"

---

## 💡 Filosofía

**Las skills NO limitan creatividad** - establecen **carriles de alta velocidad** para que desarrollo futuro sea:
- ✅ Rápido (patrones claros)
- ✅ Seguro (validaciones automáticas)
- ✅ Predecible (workflows documentados)
- ✅ Escalable (mismos principios en crecimiento)

---

## 🎓 Para Nuevos Developers

Si eres nuevo en SmartKet:

1. **Leer** los 5 SKILL.md en orden (Architecture → Testing → Database → Debug → Deployment)
2. **Ejecutar** los scripts de validación para entender el proyecto
3. **Consultar** cuando tengas dudas arquitectónicas
4. **Actualizar** si encuentras gaps o mejoras

---

## 📞 Soporte

Si un skill está desactualizado o falta información:
1. Crear issue en el repo
2. Actualizar el SKILL.md correspondiente
3. Registrar en `/DOCUMENTO/CHANGELOG.md`

---

**Última actualización**: 2026-02-02
**Mantenedor**: Equipo SmartKet
