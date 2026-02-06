<?php
/**
 * Script de Análisis: Detectar Acoplamiento
 * Busca Controllers con lógica de negocio (violación del patrón)
 * 
 * Ejecutar: php ../.agent/skills/architecture/scripts/analyze-coupling.php
 */

echo "\n🔬 ANALIZANDO ACOPLAMIENTO - Controllers\n";
echo str_repeat("=", 60) . "\n";

$baseDir = __DIR__ . '/../../../smartket-api/app/Http/Controllers/Api';
$fatControllers = [];
$warnings = [];

// Patrones que indican lógica de negocio en controller
$businessLogicPatterns = [
    'DB::' => 'Acceso directo a DB',
    '::create(' => 'Creación directa de modelos',
    '::update(' => 'Actualización directa',
    '::delete(' => 'Eliminación directa',
    'foreach' => 'Loops complejos',
    'if.*{.*{.*{' => 'Lógica anidada profunda (3+ niveles)',
];

function analyzeFile($filePath)
{
    global $businessLogicPatterns, $fatControllers, $warnings;
    
    $content = file_get_contents($filePath);
    $filename = basename($filePath);
    $issues = [];
    
    // Contar líneas de código (excluir comentarios y blancos)
    $lines = array_filter(
        explode("\n", $content),
        fn($line) => trim($line) !== '' && !str_starts_with(trim($line), '//')
    );
    $loc = count($lines);
    
    // Controllers largos son sospechosos
    if ($loc > 150) {
        $issues[] = "Archivo largo ($loc líneas) - considerar refactorizar";
    }
    
    // Buscar patrones problemáticos
    foreach ($businessLogicPatterns as $pattern => $description) {
        if (preg_match("/$pattern/", $content)) {
            $issues[] = $description;
        }
    }
    
    // Detectar métodos largos
    preg_match_all('/public function \w+\([^)]*\).*?(?=public function|\Z)/s', $content, $methods);
    foreach ($methods[0] as $method) {
        $methodLines = count(explode("\n", $method));
        if ($methodLines > 50) {
            $issues[] = "Método largo detectado ($methodLines líneas)";
        }
    }
    
    if (!empty($issues)) {
        $fatControllers[$filename] = $issues;
    }
    
    return $loc;
}

// Escanear directorios
$directories = [
    'Core',
    'Admin', 
    'Compartido',
    'Polleria'
];

$totalFiles = 0;
$totalLoc = 0;

foreach ($directories as $dir) {
    $fullPath = $baseDir . '/' . $dir;
    
    if (!is_dir($fullPath)) {
        continue;
    }
    
    echo "\n📂 Analizando: $dir\n";
    
    $files = glob($fullPath . '/*.php');
    foreach ($files as $file) {
        $totalFiles++;
        $loc = analyzeFile($file);
        $totalLoc += $loc;
        
        $filename = basename($file);
        if (isset($fatControllers[$filename])) {
            echo "  ⚠️  $filename ($loc líneas)\n";
            foreach ($fatControllers[$filename] as $issue) {
                echo "     - $issue\n";
            }
        } else {
            echo "  ✅ $filename ($loc líneas)\n";
        }
    }
}

// Resumen
echo "\n" . str_repeat("=", 60) . "\n";
echo "📊 RESUMEN DE ANÁLISIS\n";
echo str_repeat("=", 60) . "\n";

echo "Controllers analizados: $totalFiles\n";
echo "Líneas de código total: $totalLoc\n";
echo "Promedio por controller: " . round($totalLoc / max($totalFiles, 1)) . " líneas\n";
echo "Fat Controllers detectados: " . count($fatControllers) . "\n";

if (count($fatControllers) === 0) {
    echo "\n✅ EXCELENTE - Controllers delgados y bien estructurados\n\n";
} else {
    echo "\n⚠️  ATENCIÓN - Controllers con posible lógica de negocio\n";
    echo "💡 RECOMENDACIÓN: Mover lógica a Services\n\n";
    
    echo "📋 Controllers a refactorizar:\n";
    foreach ($fatControllers as $filename => $issues) {
        echo "  • $filename\n";
    }
    echo "\n";
}
