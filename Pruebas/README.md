# Scripts de Prueba - SmartKet API

Esta carpeta contiene scripts de prueba para debuggear y verificar el funcionamiento de la API.

## Scripts Disponibles

### 1. `test-login.ps1` (PowerShell)
Simula una petición de login completa desde el frontend.

**Uso:**
```powershell
cd d:\TRAE\smartket-v1
.\Pruebas\test-login.ps1
```

**Qué prueba:**
- Petición OPTIONS (CORS preflight)
- Petición POST con credenciales
- Verifica headers CORS
- Muestra respuesta del servidor

**Configurar:**
- Editar líneas 9-10 con tus credenciales de prueba

---

### 2. `test-cors.ps1` (PowerShell)
Verifica que el middleware CORS esté funcionando correctamente.

**Uso:**
```powershell
.\Pruebas\test-cors.ps1
```

**Qué prueba:**
- Headers CORS para múltiples orígenes
- Peticiones OPTIONS
- Verifica presencia de todos los headers necesarios

---

### 3. `tinker-test-login.php` (Laravel Tinker)
Prueba la lógica de autenticación directamente en Laravel.

**Uso Opción 1 (desde tinker):**
```bash
cd smartket-api
php artisan tinker
```
Luego pegar el contenido del archivo.

**Uso Opción 2 (ejecutar directamente):**
```bash
cd smartket-api
php artisan tinker < ../Pruebas/tinker-test-login.php
```

**Qué prueba:**
- Verifica usuarios en BD
- Prueba hash de password
- Verifica tenants asociados
- Genera token de prueba

---

## Orden Recomendado de Pruebas

1. **Primero**: `tinker-test-login.php` - Verifica que la lógica de backend funciona
2. **Segundo**: `test-cors.ps1` - Verifica que CORS está configurado
3. **Tercero**: `test-login.ps1` - Simula login completo desde el frontend

---

## Troubleshooting

### Error: "Usuario no encontrado"
Crear usuario de prueba:
```bash
php artisan tinker
User::create(['name' => 'Admin', 'email' => 'admin@smartket.com', 'password' => bcrypt('password')]);
```

### Error CORS persiste
1. Verificar que el servidor Laravel esté corriendo (`npm run dev`)
2. Verificar archivo `app/Http/Middleware/Cors.php` existe
3. Limpiar cache: `php artisan config:clear`
4. Reiniciar servidor

### Scripts no ejecutan
PowerShell puede requerir habilitar ejecución:
```powershell
Set-ExecutionPolicy -ExecutionPolicy RemoteSigned -Scope CurrentUser
```
