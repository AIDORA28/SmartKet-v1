<?php
/**
 * Script: Simular Login Completo
 * Ejecutar: cd smartket-api && php ../Pruebas/simular-login.php
 */

require __DIR__ . '/../smartket-api/vendor/autoload.php';

$app = require_once __DIR__ . '/../smartket-api/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Services\Core\AuthService;
use Illuminate\Support\Facades\Log;

echo "\n=== SIMULACIÓN DE LOGIN ===\n\n";

$loginId = 'bkneedless@gmail.com';
$password = '+Joe1234';

echo "📧 Login ID: {$loginId}\n";
echo "🔑 Password: {$password}\n\n";

try {
    $authService = new AuthService();
    
    echo "🔐 Intentando login...\n";
    $resultado = $authService->login($loginId, $password);
    
    echo "✅ ¡LOGIN EXITOSO!\n\n";
    echo "📄 Resultado:\n";
    echo "   Token: " . substr($resultado['token'], 0, 50) . "...\n";
    echo "   User Type: {$resultado['user_type']}\n";
    echo "   User ID: {$resultado['user']->id}\n";
    echo "   User Name: {$resultado['user']->name}\n";
    echo "   User Email: {$resultado['user']->email}\n";
    
    if (isset($resultado['tenant'])) {
        echo "\n   Tenant ID: {$resultado['tenant']->id}\n";
        echo "   Tenant Name: {$resultado['tenant']->business_name}\n";
        echo "   Tenant Slug: {$resultado['tenant']->slug}\n";
    } else {
        echo "\n   ⚠️  No hay tenant asociado\n";
    }
    
    echo "\n✅ El login funciona correctamente en el backend\n";
    echo "   El problema debe estar en el frontend o CORS\n\n";
    
} catch (\Illuminate\Validation\ValidationException $e) {
    echo "❌ CREDENCIALES INVÁLIDAS\n";
    echo "   Mensaje: " . $e->getMessage() . "\n";
    echo "   Errores: " . json_encode($e->errors(), JSON_PRETTY_PRINT) . "\n\n";
    
} catch (\Exception $e) {
    echo "❌ ERROR EN LOGIN\n";
    echo "   Tipo: " . get_class($e) . "\n";
    echo "   Mensaje: " . $e->getMessage() . "\n";
    echo "   Archivo: " . $e->getFile() . ":" . $e->getLine() . "\n\n";
}

echo "=== FIN DE SIMULACIÓN ===\n\n";
