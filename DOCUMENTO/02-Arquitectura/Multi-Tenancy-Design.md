# Multi-Tenancy Design - SmartKet ERP

## 🎯 Visión General

SmartKet implementa **multi-tenancy con aislamiento completo de datos** mediante el patrón **database-per-client**. Cada cliente que se registra obtiene su propia base de datos PostgreSQL, garantizando seguridad, performance y facilidad de backup.

---

## 🏗️ Arquitectura Multi-Tenant

### Modelo: Database-per-Client

```
┌─────────────────────────────────────────┐
│   Landlord DB (smartket_admin_db)      │
│   ┌──────────┬───────────┬───────────┐ │
│   │  users   │  tenants  │   plans   │ │
│   └──────────┴───────────┴───────────┘ │
└─────────────────────────────────────────┘
              │          │          │
              ├──────────┼──────────┤
              ▼          ▼          ▼
┌─────────────┐ ┌─────────────┐ ┌─────────────┐
│ Tenant DB 1 │ │ Tenant DB 2 │ │ Tenant DB N │
│ polleria_a  │ │ farmacia_b  │ │ retail_c    │
│             │ │             │ │             │
│ products    │ │ products    │ │ products    │
│ sales       │ │ sales       │ │ sales       │
│ staff       │ │ staff       │ │ staff       │
└─────────────┘ └─────────────┘ └─────────────┘
```

---

## 📋 Landlord Database

### Propósito
Gestión centralizada del SaaS (meta-información).

### Tablas Principales

#### `users`
Dueños de negocios (owners).
```sql
- id
- name
- email
- password
- created_at, updated_at
```

#### `tenants`
Información de cada cliente.
```sql
- id
- slug (único, ej: "polleria-el-hornito")
- business_name
- business_type (polleria, farmacia, retail)
- db_host, db_port, db_database, db_username, db_password
- is_active
- created_at, updated_at
```

#### `plans` y `subscriptions`
Gestión de suscripciones SaaS.

#### `modules`
Catálogo de funcionalidades disponibles.

#### `audit_events`
Auditoría global de acciones críticas.

---

## 🏬 Tenant Databases

### Propósito
Datos operativos de cada negocio (completamente aislados).

### Tablas Principales

#### `staff`
Empleados del negocio.
```sql
- id
- name
- email
- role_id
- branch_id (sucursal asignada)
- is_active
```

#### `branches`
Sucursales del negocio.
```sql
- id
- name
- address
- phone
- is_main
```

#### `products`
Inventario.
```sql
- id
- name
- description
- price
- stock
- category_id
- is_active
```

#### `sales`, `sale_items`
Transacciones de venta.

#### Módulo-Específico
Tablas adicionales según vertical:
- **Pollería**: `tables`, `orders`, `order_items`, `kitchen_queue`
- **Farmacia**: `prescriptions`, `expiry_alerts`
- **Retail**: `online_catalog`, `promotions`

---

## 🔄 Flujo de Creación de Tenant

### 1. Usuario se Registra (Landing)
```
POST /api/register
Body: { name, email, password, business_name, business_type }
```

### 2. TenantService Orquesta
```php
// 1. Crear usuario en landlord
$user = User::create([...]);

// 2. Crear tenant en landlord
$tenant = Tenant::create([
    'slug' => Str::slug($business_name),
    'business_name' => $business_name,
    'business_type' => $business_type,
    'db_database' => "smartket_{$slug}"
]);

// 3. Provisionar base de datos
$this->provisionDatabase($tenant);

// 4. Ejecutar migraciones tenant
$this->runTenantMigrations($tenant);

// 5. Seed datos iniciales
$this->seedTenantData($tenant);

// 6. Asignar módulos según plan
$this->assignModules($tenant);
```

### 3. Tenant Activo
Usuario puede login y usar su negocio.

---

## 🔐 Identificación del Tenant

### Header X-Tenant-ID
Frontend envía header en cada request:
```javascript
axios.defaults.headers.common['X-Tenant-ID'] = tenantId;
```

### HeaderTenantFinder
Middleware identifica tenant:
```php
class HeaderTenantFinder extends TenantFinder {
    public function findForRequest(Request $request): ?Tenant {
        $tenantId = $request->header('X-Tenant-ID');
        return Tenant::find($tenantId);
    }
}
```

### Switch de Conexión
```php
// Spatie Multi-Tenancy hace switch automático
config(['database.default' => 'tenant']);
config(['database.connections.tenant.database' => $tenant->db_database]);
```

---

## 🛡️ Seguridad e Isolation

### Aislamiento de Datos
- ✅ Nivel de conexión DB
- ✅ No hay `tenant_id` en tablas (no necesario)
- ✅ Imposible queries cross-tenant

### Credenciales Separadas
- ✅ Cada tenant tiene user/password DB único
- ✅ Permisos limitados a su propia DB

### Backup Individual
```bash
# Backup solo de un tenant
pg_dump -U smartket_polleria_a smartket_polleria_a > backup.sql
```

---

## 📊 Performance Considerations

### Ventajas Database-per-Client
- ✅ Performance aislado (un tenant lento no afecta otros)
- ✅ Backup/restore granular
- ✅ Escalabilidad (mover tenants a servidores diferentes)
- ✅ Compliance (datos en jurisdicción específica si se requiere)

### Desventajas y Mitigaciones
- ❌ **Muchas conexiones DB**: Mitigation → PgBouncer (connection pooling)
- ❌ **Migraciones complejas**: Mitigation → Scripts automatizados
- ❌ **Reportes cross-tenant**: Mitigation → Warehouse separado

---

## 🔄 Migraciones Multi-Tenant

### Landlord Migrations
```bash
php artisan migrate --database=landlord --path=database/migrations/landlord
```

**Ubicación**: `database/migrations/landlord/`

### Tenant Migrations
```bash
# Para todos los tenants
php artisan tenants:migrate

# Para un tenant específico
Tenant::find(5)->run(function() {
    Artisan::call('migrate', ['--database' => 'tenant']);
});
```

**Ubicación**: `database/migrations/tenant/`

---

## 🧪 Testing Multi-Tenant

### Test de Aislamiento
```php
test('data is isolated between tenants', function () {
    $tenant1 = Tenant::factory()->create();
    $tenant2 = Tenant::factory()->create();
    
    $tenant1->run(function () {
        Product::create(['name' => 'Tenant 1 Product']);
    });
    
    $tenant2->run(function () {
        // No debe ver productos de tenant 1
        expect(Product::count())->toBe(0); 
    });
});
```

### Factories Multi-Tenant
```php
class TenantFactory extends Factory {
    public function definition() {
        $slug = $this->faker->unique()->slug();
        return [
            'slug' => $slug,
            'business_name' => $this->faker->company(),
            'business_type' => 'retail',
            'db_database' => "smartket_test_{$slug}",
        ];
    }
}
```

---

## 🚀 Deployment Considerations

### Provisión Automática
Webhook al registrarse:
```
Register → TenantService → DB Creation → Migrations → Ready
```

### Límites y Quotas
```php
// En Plan model
public function getDatabaseLimit(): int {
    return match($this->tier) {
        'free' => 50_000_000,     // 50 MB
        'basic' => 500_000_000,   // 500 MB
        'pro' => 2_000_000_000,   // 2 GB
        'enterprise' => PHP_INT_MAX
    };
}
```

---

## 📖 Referencias

- **Implementación**: Ver `app/Services/Core/TenantService.php`
- **Finder**: Ver `app/TenantFinders/HeaderTenantFinder.php`
- **Config**: Ver `config/multitenancy.php`
- **Package**: [spatie/laravel-multitenancy](https://spatie.be/docs/laravel-multitenancy)

---

**Última actualización**: 2026-02-02
**Responsable**: Equipo Backend SmartKet
