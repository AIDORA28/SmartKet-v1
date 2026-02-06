<?php
/**
 * Script de Validación: Namespaces PSR-4
 * Verifica que los namespaces coincidan con las ubicaciones físicas
 * 
 * Ejecutar: php ../.agent/skills/architecture/scripts/check-namespaces.php
 */

echo "\n🔍 VALIDANDO NAMESPACES PSR-4 - SmartKet API\n";
echo str_repeat("=", 60) . "\n";

$errors = 0;
$warnings = 0;
$checked = 0;

$baseDir = __DIR__ . '/../../../smartket-api/app';

// Mapeo esperado: namespace => directorio físico
$expectedMappings = [
    'App\\Http\\Controllers\\Api\\Core' => 'Http/Controllers/Api/Core',
    'App\\Http\\Controllers\\Api\\Admin' => 'Http/Controllers/Api/Admin',
    'App\\Http\\Controllers\\Api\\Compartido' => 'Http/Controllers/Api/Compartido',
    'App\\Http\\Controllers\\Api\\Polleria' => 'Http/Controllers/Api/Polleria',
    'App\\Models\\Core' => 'Models/Core',
    'App\\Models\\Compartido' => 'Models/Compartido',
    'App\\Models\\Polleria' => 'Models/Polleria',
    'App\\Services\\Core' => 'Services/Core',
    'App\\Services\\Polleria' => 'Services/Polleria',
];

function extractNamespace($filePath): ?string
{
    $content = file_get_contents($filePath);
    if (preg_match('/namespace\s+([^;]+);/', $content, $matches)) {
        return trim($matches[1]);
    }
    return null;
}

function checkDirectory($dir, $expectedNamespace)
{
    global $errors, $warnings, $checked;
    
    if (!is_dir($dir)) {
        echo "  ⚠️  Directorio no existe: $dir\n";
        $warnings++;
        return;
    }
    
    $files = glob($dir . '/*.php');
    
    foreach ($files as $file) {
        $checked++;
        $actualNamespace = extractNamespace($file);
        $filename = basename($file);
        
        if ($actualNamespace === null) {
            echo "  ⚠️  Sin namespace: $filename\n";
            $warnings++;
            continue;
        }
        
        if ($actualNamespace !== $expectedNamespace) {
            echo "  ❌ INCORRECTO: $filename\n";
            echo "     Esperado: $expectedNamespace\n";
            echo "     Actual:   $actualNamespace\n";
            $errors++;
        } else {
            echo "  ✅ $filename\n";
        }
    }
}

// Validar cada mapeo
foreach ($expectedMappings as $namespace => $path) {
    $fullPath = $baseDir . '/' . $path;
    echo "\n📂 Validando: $namespace\n";
    echo "   Ubicación: $path\n";
    checkDirectory($fullPath, $namespace);
}

// Resumen
echo "\n" . str_repeat("=", 60) . "\n";
echo "📊 RESUMEN DE VALIDACIÓN\n";
echo str_repeat("=", 60) . "\n";

echo "Archivos verificados: $checked\n";
echo "Errores: " . ($errors > 0 ? "❌ $errors" : "✅ 0") . "\n";
echo "Advertencias: " . ($warnings > 0 ? "⚠️  $warnings" : "✅ 0") . "\n";

if ($errors === 0 && $warnings === 0) {
    echo "\n✅ PERFECTO - Todos los namespaces son PSR-4 compliant\n\n";
    exit(0);
} elseif ($errors === 0) {
    echo "\n⚠️  ACEPTABLE - Revisar advertencias\n\n";
    exit(0);
} else {
    echo "\n❌ CRÍTICO - Corregir namespaces antes de continuar\n\n";
    echo "💡 TIP: Ejecuta composer dump-autoload después de corregir\n\n";
    exit(1);
}
