<?php
/**
 * Script de Diagnóstico: Verificar Usuario en Base de Datos
 * Ejecutar: cd smartket-api && php ../Pruebas/diagnostico-usuario.php
 */

require __DIR__ . '/../smartket-api/vendor/autoload.php';

$app = require_once __DIR__ . '/../smartket-api/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Core\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

echo "\n=== DIAGNÓSTICO DE USUARIO ===\n\n";

// Datos a buscar
$emailBuscado = 'bkneedless@gmail.com';
$passwordPrueba = '+Joe1234';

echo "📧 Buscando usuario: {$emailBuscado}\n";
echo "🔑 Password a probar: {$passwordPrueba}\n\n";

// 1. Verificar conexión a BD
try {
    DB::connection()->getPdo();
    echo "✅ Conexión a base de datos exitosa\n";
    echo "📊 Base de datos: " . DB::connection()->getDatabaseName() . "\n\n";
} catch (\Exception $e) {
    echo "❌ Error de conexión a BD: " . $e->getMessage() . "\n";
    exit(1);
}

// 2. Buscar usuario
echo "🔍 Buscando en tabla 'users'...\n";
$usuario = User::where('email', $emailBuscado)->first();

if ($usuario) {
    echo "✅ Usuario encontrado!\n";
    echo "   ID: {$usuario->id}\n";
    echo "   Nombre: {$usuario->name}\n";
    echo "   Email: {$usuario->email}\n";
    echo "   Creado: {$usuario->created_at}\n";
    echo "   Password Hash: " . substr($usuario->password, 0, 50) . "...\n\n";
    
    // 3. Verificar password
    echo "🔐 Verificando password...\n";
    if (Hash::check($passwordPrueba, $usuario->password)) {
        echo "✅ ¡PASSWORD CORRECTO!\n";
        echo "   El hash coincide con '{$passwordPrueba}'\n\n";
        
        // 4. Verificar tenants asociados
        echo "🏢 Verificando tenants asociados...\n";
        $tenants = $usuario->tenants()->get();
        
        if ($tenants->count() > 0) {
            echo "✅ Tenants encontrados: {$tenants->count()}\n";
            foreach ($tenants as $tenant) {
                echo "   - ID: {$tenant->id}\n";
                echo "     Negocio: {$tenant->business_name}\n";
                echo "     Slug: {$tenant->slug}\n";
                echo "     Tipo: {$tenant->business_type}\n\n";
            }
        } else {
            echo "⚠️  El usuario NO tiene tenants asociados\n";
            echo "   Esto puede causar problemas en el login\n\n";
        }
        
        // 5. Generar token de prueba
        echo "🎫 Generando token de prueba...\n";
        try {
            $token = $usuario->createToken('diagnostico-test')->plainTextToken;
            echo "✅ Token generado exitosamente:\n";
            echo "   {$token}\n\n";
        } catch (\Exception $e) {
            echo "❌ Error al generar token: " . $e->getMessage() . "\n\n";
        }
        
        echo "✅ DIAGNÓSTICO COMPLETO - Usuario válido\n";
        
    } else {
        echo "❌ PASSWORD INCORRECTO\n";
        echo "   El hash NO coincide con '{$passwordPrueba}'\n\n";
        
        // Intentar con bcrypt directo
        echo "🔧 Intentando verificación manual...\n";
        $hashManual = password_verify($passwordPrueba, $usuario->password);
        echo "   password_verify(): " . ($hashManual ? "✅ CORRECTO" : "❌ INCORRECTO") . "\n\n";
        
        echo "💡 SUGERENCIA: Resetear password\n";
        echo "   Ejecuta en tinker:\n";
        echo "   \$user = User::where('email', '{$emailBuscado}')->first();\n";
        echo "   \$user->password = bcrypt('{$passwordPrueba}');\n";
        echo "   \$user->save();\n\n";
    }
    
} else {
    echo "❌ Usuario NO encontrado en la base de datos\n\n";
    
    // Listar usuarios existentes
    echo "📋 Usuarios existentes en la base de datos:\n";
    $usuarios = User::select('id', 'name', 'email')->get();
    
    if ($usuarios->count() > 0) {
        foreach ($usuarios as $u) {
            echo "   - ID {$u->id}: {$u->email} ({$u->name})\n";
        }
        echo "\n";
    } else {
        echo "   (No hay usuarios en la base de datos)\n\n";
    }
    
    echo "💡 SUGERENCIA: Crear usuario\n";
    echo "   Ejecuta en tinker:\n";
    echo "   User::create(['name' => 'Tu Nombre', 'email' => '{$emailBuscado}', 'password' => bcrypt('{$passwordPrueba}')]);\n\n";
}

echo "=== FIN DEL DIAGNÓSTICO ===\n\n";
