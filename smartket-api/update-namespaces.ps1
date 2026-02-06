# Script para actualizar namespaces de Models
$modelsCore = @("User", "Tenant", "Branch", "Role", "Permission", "Module", "Staff")
$modelsCompartido = @("Product", "Sale", "SaleItem", "Category", "CashRegister")
$modelsPolleria = @("Order", "OrderItem", "Table")

# Actualizar Models Core
foreach ($model in $modelsCore) {
    $file = "d:\TRAE\smartket-v1\smartket-api\app\Models\Core\$model.php"
    if (Test-Path $file) {
        $content = Get-Content $file -Raw
        $content = $content -replace 'namespace App\\Models;', 'namespace App\Models\Core;'
        $content = $content -replace 'use App\\Models\\Tenant;', 'use App\Models\Core\Tenant;'
        $content = $content -replace 'use App\\Models\\Branch;', 'use App\Models\Core\Branch;'
        $content = $content -replace 'use App\\Models\\Role;', 'use App\Models\Core\Role;'
        $content = $content -replace 'use App\\Models\\Staff;', 'use App\Models\Core\Staff;'
        $content = $content -replace 'use App\\Models\\User;', 'use App\Models\Core\User;'
        $content = $content -replace 'use App\\Models\\Permission;', 'use App\Models\Core\Permission;'
        Set-Content $file -Value $content -NoNewline
        Write-Host "✓ Updated $model (Core)" -ForegroundColor Green
    }
}

# Actualizar Models Compartido
foreach ($model in $modelsCompartido) {
    $file = "d:\TRAE\smartket-v1\smartket-api\app\Models\Compartido\$model.php"
    if (Test-Path $file) {
        $content = Get-Content $file -Raw
        $content = $content -replace 'namespace App\\Models;', 'namespace App\Models\Compartido;'
        $content = $content -replace 'use App\\Models\\Product;', 'use App\Models\Compartido\Product;'
        $content = $content -replace 'use App\\Models\\Sale;', 'use App\Models\Compartido\Sale;'
        $content = $content -replace 'use App\\Models\\Category;', 'use App\Models\Compartido\Category;'
        Set-Content $file -Value $content -NoNewline
        Write-Host "✓ Updated $model (Compartido)" -ForegroundColor Green
    }
}

# Actualizar Models Polleria
foreach ($model in $modelsPolleria) {
    $file = "d:\TRAE\smartket-v1\smartket-api\app\Models\Polleria\$model.php"
    if (Test-Path $file) {
        $content = Get-Content $file -Raw
        $content = $content -replace 'namespace App\\Models;', 'namespace App\Models\Polleria;'
        $content = $content -replace 'use App\\Models\\Product;', 'use App\Models\Compartido\Product;'
         $content = $content -replace 'use App\\Models\\Order;', 'use App\Models\Polleria\Order;'
        Set-Content $file -Value $content -NoNewline
        Write-Host "✓ Updated $model (Polleria)" -ForegroundColor Green
    }
}

# Actualizar Services Core
$servicesCore = @("TenantService", "AuthService", "RolePermissionService", "StaffService", "AuditService")
foreach ($service in $servicesCore) {
    $file = "d:\TRAE\smartket-v1\smartket-api\app\Services\Core\$service.php"
    if (Test-Path $file) {
        $content = Get-Content $file -Raw
        $content = $content -replace 'namespace App\\Services;', 'namespace App\Services\Core;'
        # Actualizar imports comunes
        $content = $content -replace 'use App\\Models\\User;', 'use App\Models\Core\User;'
        $content = $content -replace 'use App\\Models\\Tenant;', 'use App\Models\Core\Tenant;'
        $content = $content -replace 'use App\\Models\\Branch;', 'use App\Models\Core\Branch;'
        $content = $content -replace 'use App\\Models\\Role;', 'use App\Models\Core\Role;'
        $content = $content -replace 'use App\\Models\\Staff;', 'use App\Models\Core\Staff;'
        $content = $content -replace 'use App\\Models\\Permission;', 'use App\Models\Core\Permission;'
        $content = $content -replace 'use App\\Services\\AuditService;', 'use App\Services\Core\AuditService;'
        $content = $content -replace '\\App\\Models\\Branch::', '\\App\\Models\\Core\\Branch::'
        $content = $content -replace '\\App\\Models\\Staff', '\\App\\Models\\Core\\Staff'
        Set-Content $file -Value $content -NoNewline
        Write-Host "✓ Updated $service (Core)" -ForegroundColor Green
    }
}

# Actualizar Services Polleria
$file = "d:\TRAE\smartket-v1\smartket-api\app\Services\Polleria\PolleriaService.php"
if (Test-Path $file) {
    $content = Get-Content $file -Raw
    $content = $content -replace 'namespace App\\Services;', 'namespace App\Services\Polleria;'
    $content = $content -replace 'use App\\Models\\Product;', 'use App\Models\Compartido\Product;'
    $content = $content -replace 'use App\\Models\\Order;', 'use App\Models\Polleria\Order;'
    $content = $content -replace 'use App\\Models\\Sale;', 'use App\Models\Compartido\Sale;'
    Set-Content $file -Value $content -NoNewline
    Write-Host "✓ Updated PolleriaService (Polleria)" -ForegroundColor Green
}

Write-Host "`n✅ Namespaces actualizados completamente!" -ForegroundColor Cyan
