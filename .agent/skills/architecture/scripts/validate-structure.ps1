# Script de Validación: Estructura de Carpetas
# Verifica que los archivos estén en las ubicaciones correctas

Write-Host "`n🏗️ VALIDANDO ESTRUCTURA DE CARPETAS - SmartKet" -ForegroundColor Cyan
Write-Host "=" * 60

$errores = 0
$advertencias = 0

# 1. Verificar estructura backend
Write-Host "`n📂 Backend (smartket-api)..." -ForegroundColor Yellow

$backendPath = "smartket-api/app"
$requiredDirs = @(
    "Http/Controllers/Api/Core",
    "Http/Controllers/Api/Admin",
    "Http/Controllers/Api/Compartido",
    "Models/Core",
    "Models/Compartido",
    "Services/Core"
)

foreach ($dir in $requiredDirs) {
    $fullPath = Join-Path $backendPath $dir
    if (Test-Path $fullPath) {
        Write-Host "  ✅ $dir" -ForegroundColor Green
    } else {
        Write-Host "  ❌ FALTA: $dir" -ForegroundColor Red
        $errores++
    }
}

# 2. Verificar que no haya controllers en raíz de Api/
Write-Host "`n🔍 Verificando controllers en ubicación correcta..." -ForegroundColor Yellow

$controllersRaiz = Get-ChildItem "smartket-api/app/Http/Controllers/Api" -Filter "*.php" -ErrorAction SilentlyContinue
if ($controllersRaiz.Count -gt 0) {
    Write-Host "  ⚠️  Controllers en raíz de Api/ (deberían estar en Core/Admin/Compartido):" -ForegroundColor Yellow
    foreach ($file in $controllersRaiz) {
        Write-Host "    - $($file.Name)" -ForegroundColor Yellow
        $advertencias++
    }
} else {
    Write-Host "  ✅ Todos los controllers están organizados" -ForegroundColor Green
}

# 3. Verificar estructura frontend
Write-Host "`n📂 Frontend (smartket-app)..." -ForegroundColor Yellow

$frontendDirs = @(
    "smartket-app/src/components/compartido/layout",
    "smartket-app/src/components/compartido/ui",
    "smartket-app/src/views/compartido",
    "smartket-app/src/views/core",
    "smartket-app/src/views/admin"
)

foreach ($dir in $frontendDirs) {
    if (Test-Path $dir) {
        Write-Host "  ✅ $dir" -ForegroundColor Green
    } else {
        Write-Host "  ❌ FALTA: $dir" -ForegroundColor Red
        $errores++
    }
}

# 4. Verificar que no haya vistas en raíz
Write-Host "`n🔍 Verificando vistas en ubicación correcta..." -ForegroundColor Yellow

$vistasRaiz = Get-ChildItem "smartket-app/src/views" -Filter "*.vue" -ErrorAction SilentlyContinue
$vistasPermitidas = @("ComingSoon.vue")  # Excepciones permitidas

foreach ($vista in $vistasRaiz) {
    if ($vista.Name -notin $vistasPermitidas) {
        Write-Host "  ⚠️  Vista en raíz: $($vista.Name) (debería estar en compartido/core/admin)" -ForegroundColor Yellow
        $advertencias++
    }
}

if ($advertencias -eq 0) {
    Write-Host "  ✅ Todas las vistas están organizadas" -ForegroundColor Green
}

# 5. Verificar archivos de prueba
Write-Host "`n🧪 Verificando carpeta de pruebas..." -ForegroundColor Yellow

if (Test-Path "Pruebas") {
    $archivos = Get-ChildItem "Pruebas" -File | Measure-Object
    Write-Host "  ✅ Carpeta Pruebas existe ($($archivos.Count) scripts)" -ForegroundColor Green
} else {
    Write-Host "  ⚠️  Carpeta Pruebas no existe" -ForegroundColor Yellow
    $advertencias++
}

# Resumen
Write-Host "`n" + ("=" * 60)
Write-Host "📊 RESUMEN DE VALIDACIÓN" -ForegroundColor Cyan
Write-Host "=" * 60

if ($errores -eq 0 -and $advertencias -eq 0) {
    Write-Host "✅ PERFECTO - Estructura 100% correcta" -ForegroundColor Green
} elseif ($errores -eq 0) {
    Write-Host "⚠️  ACEPTABLE - $advertencias advertencia(s) menor(es)" -ForegroundColor Yellow
} else {
    Write-Host "❌ CRÍTICO - $errores error(es) estructurales" -ForegroundColor Red
    Write-Host "   Corrige antes de continuar desarrollo" -ForegroundColor Red
}

Write-Host "`n"
