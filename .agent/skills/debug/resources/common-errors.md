# Catálogo de Errores Comunes - SmartKet

Referencia rápida de errores frecuentes y sus soluciones.

---

## 🔴 Error 500: Internal Server Error

### Causas Comunes

#### 1. Namespace incorrecto
```
Class "App\Models\User" not found
```

**Solución**:
```bash
# Verificar namespaces
php ..\.agent\skills\architecture\scripts\check-namespaces.php

# Actualizar autoload
composer dump-autoload
```

#### 2. Permiso de archivos
```
The stream or file "storage/logs/laravel.log" could not be opened
```

**Solución**:
```bash
chmod -R 775 storage
chmod -R 775 bootstrap/cache
```

#### 3. .env faltante o incorrecto
```
No application encryption key has been specified.
```

**Solución**:
```bash
cp .env.example .env
php artisan key:generate
```

---

## 🟡 Error 419: CSRF Token Mismatch

### Causa
- Token expiró
- Cookie no se envía
- Dominio incorrecto

### Solución

**Frontend**:
```javascript
// Asegurar que Sanctum cookie se obtenga primero
await axios.get('/sanctum/csrf-cookie');
await axios.post('/api/login', credentials);
```

**Backend** (`.env`):
```env
SESSION_DRIVER=cookie
SESSION_DOMAIN=.smartket.com
SANCTUM_STATEFUL_DOMAINS=localhost:3000,localhost:8002
```

---

## 🔵 Error 403: Forbidden

### Causa
- Usuario sin permisos
- RBAC bloqueando

### Solución

**Verificar permisos del usuario**:
```bash
php artisan tinker
>>> $user = User::find(1);
>>> $user->roles;
>>> $user->getAllPermissions();
```

**Asignar permiso**:
```php
$user->givePermissionTo('products.create');
```

---

## 🟠 Error422: Unprocessable Entity

### Causa
- Validación falló

### Solución

**Ver detalles en response**:
```json
{
  "message": "The given data was invalid.",
  "errors": {
    "email": ["The email field is required."],
    "password": ["The password must be at least 8 characters."]
  }
}
```

**Corregir en frontend**: Mostrar errores al usuario

---

## 🟣 Error 401: Unauthorized

### Causas Comunes

#### 1. Token no enviado
```javascript
//  ❌ Falta header
fetch('/api/products')

// ✅ Con token
fetch('/api/products', {
  headers: {
    'Authorization': `Bearer ${token}`
  }
})
```

#### 2. Token expirado
**Solución**: Refresh token o re-login

#### 3. Token inválido
**Solución**: Verificar que el token guardado coincida

---

## 🔶 CORS Errors

### Error en navegador
```
Access to fetch at 'http://127.0.0.1:8000/api/login' from origin 'http://127.0.0.1:8002' has been blocked by CORS policy
```

### Solución

**1. Verificar middleware CORS**:
```php
// bootstrap/app.php
$middleware->prepend(\App\Http\Middleware\Cors::class);
```

**2. Verificar .env**:
```env
CORS_ALLOWED_ORIGINS=http://127.0.0.1:8002,http://localhost:8002
```

**3. Frontend debe enviar**:
```javascript
fetch(url, {
  mode: 'cors',
  credentials: 'include',
  headers: {
    'Content-Type': 'application/json'
  }
})
```

---

## 💾 Database Errors

### Error: Connection refused
```
SQLSTATE[08006] [7] connection to server at "127.0.0.1", port 5432 failed
```

**Solución**:
```bash
# Verificar PostgreSQL está corriendo
Get-Service postgresql*  # Windows
sudo systemctl status postgresql  # Linux

# Iniciar si está detenido
Start-Service postgresql  # Windows
sudo systemctl start postgresql  # Linux
```

### Error: Database does not exist
```
SQLSTATE[3D000] database "smartket_admin_db" does not exist
```

**Solución**:
```bash
# Crear database
createdb smartket_admin_db

# O desde psql
psql -U postgres
CREATE DATABASE smartket_admin_db;
```

### Error: Permission denied for relation
```
SQLSTATE[42501] permission denied for table users
```

**Solución**:
```sql
-- Otorgar permisos
GRANT ALL PRIVILEGES ON ALL TABLES IN SCHEMA public TO usuario;
GRANT ALL PRIVILEGES ON ALL SEQUENCES IN SCHEMA public TO usuario;
```

---

## 🔧 Migration Errors

### Error: Class not found in migration
```
Class "CreateProductsTable" not found
```

**Solución**:
```bash
composer dump-autoload
php artisan migrate:fresh
```

### Error: Syntax error in migration
```
SQLSTATE[42601] syntax error at or near ")"
```

**Solución**: Revisar el archivo de migración, probablemente falta coma o paréntesis

### Error: Column already exists
```
SQLSTATE[42701] column "name" of relation "products" already exists
```

**Solución**:
```bash
# Hacer rollback de la migración
php artisan migrate:rollback

# O fresh (CUIDADO: borra todo)
php artisan migrate:fresh
```

---

## 🌐 Multi-Tenant Errors

### Error: Missing X-Tenant-ID header
```
No tenant found for request
```

**Solución**:
```javascript
// Frontend debe enviar header
axios.defaults.headers.common['X-Tenant-ID'] = tenantId;
```

### Error: Data leaking between tenants
```
User A seeing User B's data
```

**Solución**:
```php
// Verificar que modelo usa conexión tenant
class Product extends Model {
    protected $connection = 'tenant';  // ← Importante
}

// O global scope
protected static function boot() {
    parent::boot();
    static::addGlobalScope('tenant', function ($query) {
        $query->where('tenant_id', session('tenant_id'));
    });
}
```

---

## 🔑 Authentication Errors

### Error: Token not provided
```
{
  "message": "Unauthenticated."
}
```

**Solución**: Verificar que cookie o header Authorization se envíen

### Error: Invalid credentials
```
{
  "message": "These credentials do not match our records."
}
```

**Diagnóstico**:
```bash
# Usar script de diagnóstico
php Pruebas/diagnostico-usuario.php

# Verificar que usuario existe
php artisan tinker
>>> User::where('email', 'test@example.com')->first()
```

---

## 📝 Quick Diagnosis Commands

```bash
# Ver últimos errores
tail -n 50 storage/logs/laravel.log

# Ver queries ejecutadas
php artisan tinker
>>> DB::enableQueryLog();
>>> // ... ejecutar código ...
>>> dd(DB::getQueryLog());

# Verificar rutas
php artisan route:list

# Limpiar cache
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

# Verificar config actual
php artisan config:show database
```

---

## 🚨 Emergency Procedures

### Si el sistema está completamente caído:

1. **Verificar servicios básicos**:
   ```bash
   # PostgreSQL
   pg_isready
   
   # Servidor PHP
   php artisan serve
   ```

2. **Revisar logs**:
   ```bash
   tail -f storage/logs/laravel.log
   ```

3. **Modo mantenimiento**:
   ```bash
   php artisan down
   # Fix el problema
   php artisan up
   ```

4. **Rollback si deployment reciente**:
   ```bash
   git checkout [commit-anterior]
   composer install
   php artisan migrate:rollback
   ```

---

Mantén este catálogo actualizado cuando encuentres nuevos errores recurrentes.
