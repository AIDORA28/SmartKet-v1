<?php

/**
 * Script de prueba: Verificar Login desde Tinker
 * 
 * Ejecutar: php artisan tinker
 * Luego pegar este código o ejecutar: include 'Pruebas/tinker-test-login.php';
 */

use App\Models\Core\User;
use App\Models\Core\Tenant;
use Illuminate\Support\Facades\Hash;

echo "\n=== PRUEBA DE LOGIN (TINKER) ===\n\n";

// 1. Verificar que existen usuarios
echo "📊 Verificando usuarios en la base de datos...\n";
$userCount = User::count();
echo "Total usuarios: $userCount\n";

if ($userCount > 0) {
    $users = User::select('id', 'name', 'email')->get();
    echo "\nUsuarios disponibles:\n";
    foreach ($users as $user) {
        echo "  - ID: {$user->id} | Email: {$user->email} | Nombre: {$user->name}\n";
    }
} else {
    echo "⚠️  No hay usuarios en la base de datos\n";
    echo "Crea uno con: php artisan tinker\n";
    echo "User::create(['name' => 'Admin', 'email' => 'admin@smartket.com', 'password' => bcrypt('password')]);\n";
    exit;
}

// 2. Probar autenticación con credenciales
echo "\n🔐 Probando autenticación...\n";
$testEmail = 'admin@smartket.com';
$testPassword = 'password';

$user = User::where('email', $testEmail)->first();

if ($user) {
    echo "Usuario encontrado: {$user->email}\n";
    
    // Verificar password
    if (Hash::check($testPassword, $user->password)) {
        echo "✅ Password correcto!\n";
        
        // Verificar tenants asociados
        $tenants = $user->tenants()->get();
        echo "\n🏢 Tenants asociados: {$tenants->count()}\n";
        
        if ($tenants->count() > 0) {
            foreach ($tenants as $tenant) {
                echo "  - ID: {$tenant->id} | Negocio: {$tenant->business_name} | Slug: {$tenant->slug}\n";
            }
        } else {
            echo "⚠️  Usuario no tiene tenants asociados\n";
        }
        
        // Crear token de prueba
        echo "\n🎫 Generando token de prueba...\n";
        $token = $user->createToken('test-login')->plainTextToken;
        echo "Token generado: {$token}\n";
        
        echo "\n✅ LOGIN TEST EXITOSO!\n";
        
    } else {
        echo "❌ Password incorrecto\n";
    }
} else {
    echo "❌ Usuario no encontrado: {$testEmail}\n";
}

echo "\n=== FIN DE PRUEBA ===\n\n";
