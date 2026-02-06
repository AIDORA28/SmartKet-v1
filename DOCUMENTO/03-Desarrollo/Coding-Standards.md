# Coding Standards - SmartKet ERP

Convenciones y estándares de código para mantener calidad y consistencia.

---

## 📋 Estándares Generales

### Idioma del Código
- **Código (PHP/JS)**: 100% Inglés
  - Variables, funciones, clases, comentarios
- **Documentación de negocio**: Español permitido
- **Base de datos**: Inglés (tablas, columnas)

**Razón**: Internacionalización futura, estándares de industria.

---

## 🐘 PHP (Laravel)

### PSR Compliance
- **PSR-1**: Basic Coding Standard
- **PSR-4**: Autoloading
- **PSR-12**: Extended Coding Style

### Namespaces
```php
// ✅ Correcto - PSR-4
namespace App\Http\Controllers\Api\Core;

// ❌ Incorrecto
namespace app\controllers; // No respeta capitalización
```

### Naming Conventions

#### Classes
```php
// ✅ PascalCase
class ProductController {}
class TenantService {}
```

#### Methods
```php
// ✅ camelCase
public function createProduct() {}
public function getUserById() {}
```

#### Variables
```php
// ✅ camelCase
$productName = 'Example';
$isActive = true;
```

#### Constants
```php
// ✅ UPPER_SNAKE_CASE
const MAX_UPLOAD_SIZE = 5000000;
const DEFAULT_CURRENCY = 'PEN';
```

### Type Hints (Obligatorio)
```php
// ✅ Correcto
public function create(array $data): Product
{
    return Product::create($data);
}

// ❌ Incorrecto - Sin tipos
public function create($data)
{
    return Product::create($data);
}
```

### Docblocks
```php
/**
 * Create a new product in the system.
 * 
 * @param array $data Product data including name, price, stock
 * @return Product The created product instance
 * @throws \InvalidArgumentException If data validation fails
 */
public function create(array $data): Product
{
    // ...
}
```

---

## 🟨 JavaScript/Vue

### Naming Conventions

#### Components
```javascript
// ✅ PascalCase
export default {
  name: 'ProductCard',
}
```

#### Variables/Functions
```javascript
// ✅ camelCase
const productName = 'Example'
const fetchProducts = async () => {}
```

#### Constants
```javascript
// ✅ UPPER_SNAKE_CASE
const API_BASE_URL = 'http://localhost:8000'
const MAX_FILE_SIZE = 5000000
```

### Vue 3 Composition API
```vue
<script setup>
// ✅ Preferir Composition API
import { ref, computed } from 'vue'

const products = ref([])
const totalProducts = computed(() => products.value.length)
</script>
```

### Props Validation
```vue
<script setup>
// ✅ Siempre validar props
defineProps({
  productId: {
    type: Number,
    required: true
  },
  showDetails: {
    type: Boolean,
    default: false
  }
})
</script>
```

---

## 🗄️ Database

### Naming

#### Tables
```sql
-- ✅ Plural, snake_case
products
sale_items
cash_registers

-- ❌ Incorrecto
Product
SaleItem
```

#### Columns
```sql
-- ✅ snake_case, descriptivo
created_at
user_id
is_active
total_amount

-- ❌ Incorrecto
CreatedAt
userId
active (¿boolean?)
total (¿de qué?)
```

#### Foreign Keys
```sql
-- ✅ [tabla_singular]_id
category_id  -- references categories(id)
product_id   -- references products(id)

-- ❌ Incorrecto
category
productID
```

### Timestamps
```php
// ✅ Siempre incluir en migrations
$table->timestamps();          // created_at, updated_at
$table->softDeletes();         // deleted_at (si aplica)
```

---

## 📁 Estructura de Archivos

### Backend Controllers
```
app/Http/Controllers/Api/
├── Core/
│   ├── AuthController.php
│   ├── TenantController.php
│   └── SetupController.php
├── Admin/
│   └── StaffController.php
├── Compartido/
│   ├── ProductController.php
│   └── SaleController.php
└── [Vertical]/
    └── SpecificController.php
```

### Frontend Components
```
src/components/
├── compartido/
│   ├── layout/
│   │   ├── TheSidebar.vue
│   │   └── TheHeader.vue
│   └── ui/
│       ├── SKButton.vue
│       └── SKCard.vue
└── [vertical]/
    └── SpecificComponent.vue
```

---

## 🎨 Code Style

### Indentación
- **Espacios**: 4 (PHP), 2 (JS/Vue)
- **No tabs**

### Line Length
- **Máximo**: 120 caracteres
- **Preferido**: 80-100 caracteres

### Brackets
```php
// ✅ PHP - Same line
if ($condition) {
    // code
}

// ✅ JavaScript - Same line
if (condition) {
  // code
}
```

### Imports Order
```php
// PHP
use Illuminate\...;  // Framework
use App\Models\...;  // Proyecto
use Exception;       // Built-in
```

```javascript
// JavaScript
import { ref } from 'vue'        // Framework
import ProductCard from '@/...'  // Proyecto
```

---

## ✅ Best Practices

### 1. DRY (Don't Repeat Yourself)
```php
// ❌ Repetición
if ($user->role === 'admin') { /* ... */ }
if ($user->role === 'admin') { /* ... */ }

// ✅ Método reutilizable
if ($user->isAdmin()) { /* ... */ }
```

### 2. Single Responsibility
```php
// ❌ Hace demasiado
class UserController {
    public function create() {
        // Validar
        // Crear usuario
        // Enviar email
        // Crear tenant
        // Asignar permisos
    }
}

// ✅ Delega
class UserController {
    public function create() {
        $validated = $this->validate();
        $user = $this->service->create($validated);
        return response()->json($user);
    }
}
```

### 3. Early Returns
```php
// ✅ Preferir early returns
public function process($data) {
    if (!$data) {
        return null;
    }
    
    if (!$data->isValid()) {
        throw new Exception('Invalid');
    }
    
    return $this->doProcess($data);
}

// ❌ Evitar nesting profundo
public function process($data) {
    if ($data) {
        if ($data->isValid()) {
            return $this->doProcess($data);
        } else {
            throw new Exception('Invalid');
        }
    }
    return null;
}
```

### 4. Guard Clauses
```php
// ✅ Guard clauses al inicio
public function updateProduct(Product $product, array $data) {
    if (!auth()->user()->can('products.update')) {
        abort(403);
    }
    
    if ($product->isArchived()) {
        throw new Exception('Cannot update archived product');
    }
    
    return $product->update($data);
}
```

---

## 🧪 Testing Standards

### Naming
```php
// ✅ Descriptivo en inglés
test('user can create product when authenticated')
test('product creation fails with invalid data')

// ❌ Vago
test('test 1')
test('product test')
```

### Arrange-Act-Assert
```php
test('user can purchase product', function () {
    // Arrange
    $user = User::factory()->create();
    $product = Product::factory()->create(['stock' => 10]);
    
    // Act
    $response = $this->actingAs($user)
                     ->postJson('/api/purchase', ['product_id' => $product->id]);
    
    // Assert
    $response->assertStatus(201);
    expect($product->fresh()->stock)->toBe(9);
});
```

---

## 📝 Comments

### When to Comment
```php
// ✅ Explicar "por qué", no "qué"
// Workaround for PostgreSQL specific behavior
// TODO: Refactor after upgrading to Laravel 12

// ❌ Comentar lo obvio
// Create a product
$product = Product::create($data);
```

### TODO Comments
```php
// ✅ Formato estándar
// TODO(username): Implement caching here
// FIXME: Race condition possible
// HACK: Temporary solution until API v2
```

---

## 🔒 Security Standards

### Never Hardcode Secrets
```php
// ❌ NUNCA
$apiKey = 'sk_live_1234567890';

// ✅ Usar .env
$apiKey = config('services.stripe.key');
```

### Validate Input
```php
// ✅ Siempre validar
$validated = $request->validate([
    'email' => 'required|email',
    'password' => 'required|min:8'
]);
```

### Sanitize Output
```blade
{{-- ✅ Blade auto-escapa --}}
{{ $user->name }}

{{-- ⚠️ Solo si confías en el HTML --}}
{!! $trustedHtml !!}
```

---

## 🛠️ Tools

### Linters
```bash
# PHP
composer require --dev phpstan/phpstan
vendor/bin/phpstan analyse

# JavaScript
npm install --save-dev eslint
npx eslint src/
```

### Formatters
```bash
# PHP
composer require --dev friendsofphp/php-cs-fixer
vendor/bin/php-cs-fixer fix

# JavaScript
npm install --save-dev prettier
npx prettier --write src/
```

---

## 📖 Referencias

- [PSR-12](https://www.php-fig.org/psr/psr-12/)
- [Vue Style Guide](https://vuejs.org/style-guide/)
- [Laravel Best Practices](https://github.com/alexeymezenin/laravel-best-practices)

---

**Última actualización**: 2026-02-02
**Mantenedor**: Equipo SmartKet
