# Anti-Patrones Comunes en SmartKet

Esta documentación identifica patrones problemáticos que debes **evitar** durante el desarrollo. Si encuentras código con estos problemas, refactoriza.

---

## 1. 🚫 Fat Controllers (Controllers Gordos)

### Problema
Controllers con lógica de negocio, acceso directo a DB, loops complejos.

### Ejemplo Incorrecto
```php
class ProductController {
    public function store(Request $request) {
        $product = Product::create($request->all());
        
        // ❌ Lógica de negocio en controller
        foreach ($product->variants as $variant) {
            $variant->stock = $request->input("stock_{$variant->id}");
            $variant->save();
        }
        
        // ❌ Envío de emails
        Mail::to($product->owner)->send(new ProductCreated($product));
        
        return response()->json($product);
    }
}
```

### Solución
```php
class ProductController {
    public function store(Request $request) {
        $validated = $request->validate([...]);
        $product = $this->productService->create($validated);
        return response()->json($product, 201);
    }
}

class ProductService {
    public function create(array $data) {
        return DB::transaction(function() use ($data) {
            $product = Product::create($data);
            $this->processVariants($product, $data['variants'] ?? []);
            $this->notifyOwner($product);
            return $product;
        });
    }
}
```

---

## 2. 🚫 God Classes (Clases Dios)

### Problema
Un solo Service que hace TODO (AuthService que también gestiona productos, ventas, etc.).

### Ejemplo Incorrecto
```php
class SystemService {
    public function login() { ... }
    public function createProduct() { ... }
    public function processSale() { ... }
    public function generateReport() { ... }
    public function sendEmail() { ... }
    // ❌ Demasiadas responsabilidades
}
```

### Solución
```php
// ✅ Separar por dominio
class AuthService { ... }
class ProductService { ... }
class SaleService { ... }
class ReportService { ... }
class EmailService { ... }
```

---

## 3. 🚫 Namespace Pollution (Contaminación de Namespaces)

### Problema
Imports innecesarios, uso de clases globales cuando hay alternativas.

### Ejemplo Incorrecto
```php
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use App\Models\User;
use App\Models\Product;
use App\Models\Sale;
// ❌ Imports que nunca se usan

class Controller {
    public function index() {
        // Solo usa Product
        return Product::all();
    }
}
```

### Solución
```php
use App\Models\Compartido\Product;
// ✅ Solo importar lo que se usa

class Controller {
    public function index() {
        return Product::all();
    }
}
```

---

## 4. 🚫 Tight Coupling (Acoplamiento Fuerte)

### Problema
Modelos que dependen directamente de Services, o Controllers que instancian objetos manualmente.

### Ejemplo Incorrecto
```php
class Product {
    public function calculatePrice() {
        // ❌ Modelo llamando a Service
        $service = new PricingService();
        return $service->calculate($this);
    }
}

class ProductController {
    public function index() {
        // ❌ Instanciación manual
        $service = new ProductService();
        return $service->list();
    }
}
```

### Solución
```php
// ✅ Inyección de dependencias
class ProductController {
    public function __construct(
        private ProductService $service
    ) {}
    
    public function index() {
        return $this->service->list();
    }
}

// ✅ Modelo sin lógica de negocio
class Product {
    // Solo relaciones, scopes, accessors
}
```

---

## 5. 🚫 Feature Envy (Envidia de Características)

### Problema
Clase que accede excesivamente a propiedades/métodos de otra clase.

### Ejemplo Incorrecto
```php
class OrderController {
    public function show(Order $order) {
        // ❌ Acceso directo a propiedades internas
        $tax = $order->total * $order->taxRate;
        $discount = $order->items->sum('discount');
        $final = $order->total - $discount + $tax;
        
        return response()->json(['total' => $final]);
    }
}
```

### Solución
```php
// ✅ Lógica en el Modelo
class Order {
    public function getFinalTotal(): float {
        $tax = $this->total * $this->taxRate;
        $discount = $this->items->sum('discount');
        return $this->total - $discount + $tax;
    }
}

class OrderController {
    public function show(Order $order) {
        return response()->json(['total' => $order->getFinalTotal()]);
    }
}
```

---

## 6. 🚫 Magic Numbers/Strings (Números/Strings Mágicos)

### Problema
Valores hardcodeados sin contexto.

### Ejemplo Incorrecto
```php
if ($order->status === 3) { // ❌ ¿Qué es 3?
    // ...
}

if ($user->role === 'admin_super_master') { // ❌ Typo-prone
    // ...
}
```

### Solución
```php
// ✅ Constantes o Enums
class OrderStatus {
    const PENDING = 1;
    const PROCESSING = 2;
    const COMPLETED = 3;
}

if ($order->status === OrderStatus::COMPLETED) {
    // ...
}

// ✅ O mejor aún, Enums PHP 8.1+
enum OrderStatus: int {
    case PENDING = 1;
    case PROCESSING = 2;
    case COMPLETED = 3;
}
```

---

## 7. 🚫 N+1 Queries (Consultas N+1)

### Problema
Realizar queries en loops.

### Ejemplo Incorrecto
```php
$products = Product::all();

foreach ($products as $product) {
    // ❌ Query por cada producto
    $categoryName = $product->category->name;
}
```

### Solución
```php
// ✅ Eager loading
$products = Product::with('category')->get();

foreach ($products as $product) {
    $categoryName = $product->category->name; // No query adicional
}
```

---

## 8. 🚫 Mixed Concerns (Mezcla de Responsabilidades)

### Problema
Código de presentación mezclado con lógica de negocio.

### Ejemplo Incorrecto
```php
class ProductService {
    public function create(array $data) {
        $product = Product::create($data);
        
        // ❌ Formateo de presentación en Service
        $product->formatted_price = '$' . number_format($product->price, 2);
        
        return $product;
    }
}
```

### Solución
```php
// ✅ Service solo lógica
class ProductService {
    public function create(array $data) {
        return Product::create($data);
    }
}

// ✅ Formateo en Accessor del Modelo
class Product {
    public function getFormattedPriceAttribute(): string {
        return '$' . number_format($this->price, 2);
    }
}

// ✅ O en el frontend
// Vue component muestra {{ formatCurrency(product.price) }}
```

---

## 9. 🚫 Anemic Domain Model (Modelo Anémico)

### Problema
Modelos que son solo getters/setters sin comportamiento.

### Ejemplo Incorrecto
```php
class Product {
    // ❌ Solo propiedades, sin métodos de negocio
}

class ProductService {
    public function isAvailable(Product $product) {
        return $product->stock > 0 && $product->is_active;
    }
}
```

### Solución
```php
// ✅ Comportamiento en el Modelo
class Product {
    public function isAvailable(): bool {
        return $this->stock > 0 && $this->is_active;
    }
    
    public function decrementStock(int $quantity): void {
        if ($quantity > $this->stock) {
            throw new \DomainException('Insufficient stock');
        }
        $this->decrement('stock', $quantity);
    }
}

class ProductService {
    public function reserveStock(Product $product, int $quantity) {
        if (!$product->isAvailable()) {
            throw new \DomainException('Product not available');
        }
        $product->decrementStock($quantity);
    }
}
```

---

## 10. 🚫 Premature Optimization (Optimización Prematura)

### Problema
Código complejo "por si acaso" cuando no hay problema de performance.

### Ejemplo Incorrecto
```php
// ❌ Cache innecesario para 10 registros
public function getAllCategories() {
    return Cache::remember('categories', 3600, function() {
        return Category::all();
    });
}
```

### Solución
```php
// ✅ Simple primero, optimiza cuando haya evidencia
public function getAllCategories() {
    return Category::all();
}

// Si luego hay 10,000 categorías, ENTONCES agregar cache
```

---

## 📋 Checklist Anti-Patrones

Antes de hacer commit, pregúntate:

- [ ] ¿Mi controller tiene más de 50 líneas en un método?
- [ ] ¿Estoy usando `DB::` directamente en el controller?
- [ ] ¿Tengo loops anidados de más de 2 niveles?
- [ ] ¿Hay números o strings "mágicos" sin constantes?
- [ ] ¿Estoy mezclando presentación con lógica?
- [ ] ¿Hay queries dentro de loops?
- [ ] ¿Mis modelos son solo propiedades vacías?

Si respondiste SÍ a alguna: **Refactoriza antes de continuar**.
